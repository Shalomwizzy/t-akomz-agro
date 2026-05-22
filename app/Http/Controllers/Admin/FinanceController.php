<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DirectExpenseAlert;
use App\Mail\ExpenseApproved;
use App\Mail\ExpenseRejected;
use App\Mail\ExpenseRequestAlert;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Rules\MeaningfulDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class FinanceController extends Controller
{
    public function dashboard()
    {
        Gate::authorize('wallet.view');

        $wallet       = Wallet::main();
        $pendingCount = $wallet->pendingCount();
        $isManager    = auth()->user()->hasRole('MANAGER');

        // Recent transactions — admins see all, managers see their own
        $query = $wallet->transactions()->with(['creator', 'approver'])->latest();
        if ($isManager) {
            $query->where('created_by', auth()->id());
        }
        $recentTransactions = $query->limit(20)->get();

        // Pending expenses for admin quick-action
        $pendingExpenses = collect();
        if (!$isManager) {
            $pendingExpenses = $wallet->transactions()
                ->with('creator')
                ->pending()
                ->latest()
                ->limit(10)
                ->get();
        }

        // Category breakdown (completed only — actual spending)
        $categoryBreakdown = $wallet->transactions()
            ->completed()
            ->where('type', '!=', 'funding')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $totalSpentBreakdown = $categoryBreakdown->sum('total');

        // Manager budget stats
        $managers    = collect();
        $managerStats = collect();
        $myStats     = null;

        if (!$isManager) {
            $managers     = User::role('MANAGER')->get();
            $managerStats = $managers->mapWithKeys(fn($m) => [$m->id => $wallet->managerStats($m->id)]);
        } else {
            $myStats = $wallet->managerStats(auth()->id());
        }

        return view('admin.finance.dashboard', compact(
            'wallet',
            'pendingCount',
            'recentTransactions',
            'pendingExpenses',
            'categoryBreakdown',
            'totalSpentBreakdown',
            'isManager',
            'managers',
            'managerStats',
            'myStats'
        ));
    }

    public function showFund()
    {
        Gate::authorize('wallet.fund');

        $wallet   = Wallet::main();
        $managers = User::role('MANAGER')->get();

        return view('admin.finance.fund', compact('wallet', 'managers'));
    }

    public function fund(Request $request)
    {
        Gate::authorize('wallet.fund');

        $validated = $request->validate([
            'amount'       => ['required', 'numeric', 'min:100'],
            'description'  => ['required', 'string', 'min:20', new MeaningfulDescription()],
            'allocated_to' => ['nullable', 'exists:users,id'],
            'project_name' => ['nullable', 'string', 'max:150'],
        ]);

        $wallet = Wallet::main();
        $wallet->fund(
            (float) $validated['amount'],
            $validated['description'],
            auth()->id(),
            $validated['allocated_to'] ? (int) $validated['allocated_to'] : null,
            $validated['project_name'] ?? null,
        );

        return redirect()->route('admin.finance.dashboard')
            ->with('success', 'Wallet funded successfully with ₦' . number_format((float) $validated['amount'], 2) . '.');
    }

    public function createExpense()
    {
        Gate::authorize('expense.create');

        $wallet = Wallet::main();

        return view('admin.finance.expenses.create', compact('wallet'));
    }

    public function storeExpense(Request $request)
    {
        Gate::authorize('expense.create');

        $validated = $request->validate([
            'short_title'  => ['required', 'string', 'min:3', 'max:100'],
            'category'     => ['required', 'in:feed,labor,fuel,veterinary,equipment,maintenance,logistics,other'],
            'amount'       => ['required', 'numeric', 'min:1'],
            'expense_date' => ['required', 'date'],
            'vendor_name'  => ['nullable', 'string', 'max:255'],
            'description'  => ['required', 'string', new MeaningfulDescription()],
        ]);

        $wallet = Wallet::main();

        $txn = WalletTransaction::create([
            'wallet_id'    => $wallet->id,
            'type'         => 'expense_request',
            'status'       => 'pending',
            'created_by'   => auth()->id(),
            'short_title'  => $validated['short_title'],
            'category'     => $validated['category'],
            'amount'       => $validated['amount'],
            'expense_date' => $validated['expense_date'],
            'vendor_name'  => $validated['vendor_name'] ?? null,
            'description'  => $validated['description'],
        ]);

        $txn->load('creator');
        $this->notifyAdmins(fn($email) => Mail::to($email)->send(new ExpenseRequestAlert($txn)));

        return redirect()->route('admin.finance.dashboard')
            ->with('success', 'Expense request submitted and is pending approval.');
    }

    public function createDirectExpense()
    {
        Gate::authorize('expense.create');

        $wallet = Wallet::main();

        return view('admin.finance.expenses.direct', compact('wallet'));
    }

    public function storeDirectExpense(Request $request)
    {
        Gate::authorize('expense.create');

        $validated = $request->validate([
            'short_title'   => ['required', 'string', 'min:3', 'max:100'],
            'category'      => ['required', 'in:feed,labor,fuel,veterinary,equipment,maintenance,logistics,other'],
            'other_specify' => ['required_if:category,other', 'nullable', 'string', 'max:100'],
            'amount'        => ['required', 'numeric', 'min:1'],
            'expense_date'  => ['required', 'date'],
            'vendor_name'   => ['nullable', 'string', 'max:255'],
            'description'   => ['required', 'string', 'min:10'],
        ]);

        // Embed the "other" label into the title so records are self-explanatory
        $title = $validated['short_title'];
        if ($validated['category'] === 'other' && !empty($validated['other_specify'])) {
            $title = '[' . trim($validated['other_specify']) . '] ' . $title;
        }

        $wallet = Wallet::main();

        if ($wallet->balance < $validated['amount']) {
            return back()->withInput()->with('error', 'Insufficient wallet balance.');
        }

        DB::transaction(function () use ($validated, $wallet, $title) {
            WalletTransaction::create([
                'wallet_id'    => $wallet->id,
                'type'         => 'expense_spent',
                'status'       => 'completed',
                'created_by'   => auth()->id(),
                'approved_by'  => auth()->id(),
                'short_title'  => $title,
                'category'     => $validated['category'],
                'amount'       => $validated['amount'],
                'expense_date' => $validated['expense_date'],
                'vendor_name'  => $validated['vendor_name'] ?? null,
                'description'  => $validated['description'],
            ]);

            $wallet->decrement('balance', (float) $validated['amount']);
            $wallet->increment('total_spent', (float) $validated['amount']);
        });

        $directTxn = WalletTransaction::with('creator')
            ->where('wallet_id', $wallet->id)
            ->where('type', 'expense_spent')
            ->where('created_by', auth()->id())
            ->latest()
            ->first();

        if ($directTxn) {
            $this->notifyAdmins(fn($email) => Mail::to($email)->send(new DirectExpenseAlert($directTxn)));
        }

        return redirect()->route('admin.finance.dashboard')
            ->with('success', '₦' . number_format($validated['amount'], 2) . ' logged and deducted immediately.');
    }

    public function showExpense(WalletTransaction $transaction)
    {
        Gate::authorize('wallet.view');

        $isManager = auth()->user()->hasRole('MANAGER');

        // Manager can only see their own transactions
        if ($isManager && $transaction->created_by !== auth()->id()) {
            abort(403, 'You can only view your own expense requests.');
        }

        $transaction->load(['creator', 'approver', 'wallet']);

        return view('admin.finance.expenses.show', compact('transaction', 'isManager'));
    }

    public function approve(Request $request, WalletTransaction $transaction)
    {
        Gate::authorize('expense.approve');

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'This expense request is not in a pending state.');
        }

        $wallet = Wallet::main();

        if ($wallet->balance < $transaction->amount) {
            return back()->with('error', 'Insufficient wallet balance to approve this expense. Current balance: ' . $wallet->formatted_balance . '.');
        }

        $transaction->approve(auth()->id());
        $transaction->load(['creator', 'approver']);

        try { Mail::to($transaction->creator->email)->send(new ExpenseApproved($transaction)); } catch (\Throwable) {}

        return redirect()->route('admin.finance.expenses.show', $transaction)
            ->with('success', 'Expense request approved. ' . $transaction->formatted_amount . ' has been reserved from the wallet.');
    }

    public function reject(Request $request, WalletTransaction $transaction)
    {
        Gate::authorize('expense.approve');

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10'],
        ]);

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'This expense request is not in a pending state.');
        }

        $transaction->reject($validated['rejection_reason'], auth()->id());
        $transaction->load(['creator', 'approver']);

        try { Mail::to($transaction->creator->email)->send(new ExpenseRejected($transaction)); } catch (\Throwable) {}

        return redirect()->route('admin.finance.expenses.show', $transaction)
            ->with('success', 'Expense request rejected.');
    }

    public function confirmSpending(Request $request, WalletTransaction $transaction)
    {
        Gate::authorize('expense.create');

        if ($transaction->created_by !== auth()->id()) {
            abort(403, 'You can only confirm spending for your own expense requests.');
        }

        if ($transaction->status !== 'approved') {
            return back()->with('error', 'Only approved expenses can be confirmed as spent.');
        }

        $request->validate([
            'receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $receiptPath = null;

        if ($request->hasFile('receipt') && $request->file('receipt')->isValid()) {
            $receiptPath = $request->file('receipt')->store('receipts', 'local');
        }

        $transaction->confirmSpending($receiptPath);

        return redirect()->route('admin.finance.expenses.show', $transaction)
            ->with('success', 'Spending confirmed. Expense marked as completed.');
    }

    public function index(Request $request)
    {
        Gate::authorize('wallet.view');

        $isManager  = auth()->user()->hasRole('MANAGER');
        $canViewAll = auth()->user()->can('expense.view_all');

        $query = WalletTransaction::with(['creator', 'approver', 'wallet'])->latest();

        // Managers can only view their own; those without view_all also restricted
        if ($isManager || !$canViewAll) {
            $query->where('created_by', auth()->id());
        }

        // Filters
        if ($request->filled('manager_id') && $canViewAll) {
            $query->where('created_by', $request->manager_id);
        }

        if ($request->filled('type') && in_array($request->type, ['funding', 'expense_request', 'expense_approved', 'expense_spent'])) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status') && in_array($request->status, ['pending', 'approved', 'rejected', 'completed'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category') && in_array($request->category, ['feed', 'labor', 'fuel', 'veterinary', 'equipment', 'maintenance', 'logistics', 'other'])) {
            $query->where('category', $request->category);
        }

        if ($request->filled('date_from')) {
            $query->where('expense_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('expense_date', '<=', $request->date_to);
        }

        $transactions = $query->paginate(20)->withQueryString();
        $wallet       = Wallet::main();
        $managers     = $canViewAll ? User::role('MANAGER')->get() : collect();

        return view('admin.finance.expenses.index', compact('transactions', 'wallet', 'isManager', 'managers'));
    }

    public function serveReceipt(WalletTransaction $transaction)
    {
        Gate::authorize('wallet.view');

        $isManager = auth()->user()->hasRole('MANAGER');
        if ($isManager && $transaction->created_by !== auth()->id()) {
            abort(403);
        }

        abort_unless($transaction->receipt_file, 404);

        return Storage::disk('local')->download($transaction->receipt_file);
    }

    public function export(Request $request)
    {
        Gate::authorize('wallet.view');

        $isManager  = auth()->user()->hasRole('MANAGER');
        $canViewAll = auth()->user()->can('expense.view_all');

        $query = WalletTransaction::with(['creator', 'approver', 'allocatedTo'])->oldest('expense_date');

        if ($isManager || !$canViewAll) {
            $query->where('created_by', auth()->id());
        }

        if ($request->filled('manager_id') && $canViewAll) {
            $query->where('created_by', $request->manager_id);
        }
        if ($request->filled('type') && in_array($request->type, ['funding', 'expense_request', 'expense_approved', 'expense_spent'])) {
            $query->where('type', $request->type);
        }
        if ($request->filled('date_from')) {
            $query->where('expense_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('expense_date', '<=', $request->date_to);
        }

        $transactions = $query->get();

        $filename = 'finance-export-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date', 'Type', 'Title', 'Category', 'Description', 'Vendor', 'Amount (₦)', 'Status', 'Created By', 'Approved By', 'Allocated To', 'Project']);
            foreach ($transactions as $txn) {
                fputcsv($handle, [
                    $txn->expense_date->format('d/m/Y'),
                    $txn->type,
                    $txn->short_title,
                    $txn->category,
                    $txn->description,
                    $txn->vendor_name ?? '',
                    number_format($txn->amount, 2),
                    $txn->status,
                    $txn->creator->name ?? '',
                    $txn->approver->name ?? '',
                    $txn->allocatedTo->name ?? '',
                    $txn->project_name ?? '',
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function notifyAdmins(\Closure $send): void
    {
        $admins = User::whereHas('roles', fn($q) => $q->whereIn('name', ['SUPER_ADMIN', 'ADMIN']))->get();
        foreach ($admins as $admin) {
            try { $send($admin->email); } catch (\Throwable) {}
        }
    }
}
