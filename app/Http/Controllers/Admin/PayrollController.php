<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SalaryPaid;
use App\Models\Payroll;
use App\Models\SalaryPayment;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::with('worker')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('worker_id')) {
            $query->where('worker_id', $request->worker_id);
        }

        $payrolls = $query->paginate(25)->withQueryString();
        $workers  = Worker::active()->orderBy('full_name')->get();

        $stats = [
            'draft'          => Payroll::where('status', 'draft')->count(),
            'approved'       => Payroll::where('status', 'approved')->count(),
            'paid_this_month'=> Payroll::where('status', 'paid')
                ->whereMonth('updated_at', now()->month)
                ->sum('total_payable'),
            'unpaid_total'   => Payroll::whereIn('status', ['draft', 'approved'])->sum('total_payable'),
        ];

        return view('admin.payroll.index', compact('payrolls', 'workers', 'stats'));
    }

    public function generate(Request $request)
    {
        $data = $request->validate([
            'worker_id'       => ['required', 'exists:workers,id'],
            'period_start'    => ['required', 'date'],
            'period_end'      => ['required', 'date', 'after_or_equal:period_start'],
            'override_amount' => ['nullable', 'numeric', 'min:0'],
            'bonuses'         => ['nullable', 'numeric', 'min:0'],
            'deductions'      => ['nullable', 'numeric', 'min:0'],
            'notes'           => ['nullable', 'string', 'max:500'],
        ]);

        $worker = Worker::findOrFail($data['worker_id']);
        $start  = Carbon::parse($data['period_start']);
        $end    = Carbon::parse($data['period_end']);

        // Check no duplicate payroll for this exact period
        $exists = Payroll::where('worker_id', $worker->id)
            ->where('period_start', $data['period_start'])
            ->where('period_end', $data['period_end'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'A payroll already exists for this worker and period.');
        }

        $workingDays = $start->diffInWeekdays($end) + 1;
        $present     = $worker->daysPresent($start, $end);
        $halfDays    = $worker->halfDays($start, $end);
        $absent      = $workingDays - $present - $halfDays;

        // Use manual override if provided, otherwise calculate from attendance
        $baseSalary  = isset($data['override_amount']) && $data['override_amount'] !== null
            ? (float) $data['override_amount']
            : $worker->calculateBaseSalary($start, $end);

        $bonuses     = (float) ($data['bonuses'] ?? 0);
        $deductions  = (float) ($data['deductions'] ?? 0);
        $total       = max(0, $baseSalary + $bonuses - $deductions);

        $payroll = Payroll::create([
            'worker_id'          => $worker->id,
            'period_start'       => $data['period_start'],
            'period_end'         => $data['period_end'],
            'total_days_in_period'=> $workingDays,
            'days_present'       => $present,
            'days_absent'        => max(0, $absent),
            'half_days'          => $halfDays,
            'base_salary'        => $baseSalary,
            'deductions'         => $deductions,
            'bonuses'            => $bonuses,
            'total_payable'      => $total,
            'notes'              => $data['notes'] ?? null,
            'status'             => 'draft',
            'generated_by'       => auth()->id(),
        ]);

        return redirect()->route('admin.payroll.show', $payroll)
            ->with('success', 'Payroll generated for ' . $worker->full_name . '.');
    }

    public function show(Payroll $payroll)
    {
        $payroll->load(['worker', 'payment.paidBy', 'payment.walletTransaction', 'generator', 'approver']);

        // Load attendance for the payroll period
        $attendance = $payroll->worker->attendances()
            ->whereBetween('date', [$payroll->period_start, $payroll->period_end])
            ->orderBy('date')
            ->get();

        $wallet = Wallet::main();

        return view('admin.payroll.show', compact('payroll', 'attendance', 'wallet'));
    }

    public function approve(Payroll $payroll)
    {
        if ($payroll->status !== 'draft') {
            return back()->with('error', 'Only draft payrolls can be approved.');
        }

        $payroll->update([
            'status'      => 'approved',
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Payroll approved. Ready for payment processing.');
    }

    public function pay(Request $request, Payroll $payroll)
    {
        if ($payroll->status !== 'approved') {
            return back()->with('error', 'Only approved payrolls can be paid. Please approve first.');
        }

        if ($payroll->payment()->exists()) {
            return back()->with('error', 'This payroll has already been paid.');
        }

        $data = $request->validate([
            'payment_method' => ['required', 'in:cash,bank_transfer'],
            'reference'      => ['nullable', 'string', 'max:100'],
            'notes'          => ['nullable', 'string', 'max:300'],
        ]);

        $wallet = Wallet::main();

        if ($wallet->balance < $payroll->total_payable) {
            return back()->with('error',
                'Insufficient wallet balance. Current: ' . $wallet->formatted_balance .
                ' — Required: ' . $payroll->formatted_payable
            );
        }

        DB::transaction(function () use ($payroll, $data, $wallet) {
            // 1. Create wallet transaction (expense_spent — labor category)
            $txn = WalletTransaction::create([
                'wallet_id'    => $wallet->id,
                'type'         => 'expense_spent',
                'status'       => 'completed',
                'category'     => 'labor',
                'short_title'  => 'Salary: ' . $payroll->worker->full_name,
                'description'  => 'Salary payment for ' . $payroll->worker->full_name .
                                  ' — Period: ' . $payroll->period_label,
                'amount'       => $payroll->total_payable,
                'expense_date' => now()->toDateString(),
                'vendor_name'  => $payroll->worker->full_name,
                'created_by'   => auth()->id(),
                'approved_by'  => auth()->id(),
            ]);

            // 2. Deduct from wallet
            $wallet->decrement('balance', (float) $payroll->total_payable);
            $wallet->increment('total_spent', (float) $payroll->total_payable);

            // 3. Create salary payment record
            SalaryPayment::create([
                'payroll_id'           => $payroll->id,
                'worker_id'            => $payroll->worker_id,
                'amount_paid'          => $payroll->total_payable,
                'payment_method'       => $data['payment_method'],
                'date_paid'            => now()->toDateString(),
                'paid_by'              => auth()->id(),
                'wallet_transaction_id'=> $txn->id,
                'reference'            => $data['reference'] ?? null,
                'notes'                => $data['notes'] ?? null,
            ]);

            // 4. Mark payroll as paid
            $payroll->update(['status' => 'paid']);
        });

        // Notify worker by email if they have one
        $payroll->load(['worker', 'payment']);
        if ($payroll->worker->email) {
            try { Mail::to($payroll->worker->email, $payroll->worker->full_name)->send(new SalaryPaid($payroll)); } catch (\Throwable) {}
        }

        return redirect()->route('admin.payroll.show', $payroll)
            ->with('success', 'Salary of ' . $payroll->formatted_payable . ' paid to ' . $payroll->worker->full_name . '. Wallet deducted and transaction logged.');
    }

    public function destroy(Payroll $payroll)
    {
        if ($payroll->status === 'paid') {
            return back()->with('error', 'Cannot delete a payroll that has already been paid.');
        }

        $payroll->delete();

        return redirect()->route('admin.payroll.index')
            ->with('success', 'Payroll deleted.');
    }
}
