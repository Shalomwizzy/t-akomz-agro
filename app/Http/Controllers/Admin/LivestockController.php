<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LivestockBatch;
use App\Models\BatchExpense;
use App\Rules\MeaningfulDescription;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LivestockController extends Controller
{
    public function index(): View
    {
        $batches = LivestockBatch::with('batchExpenses')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalBatches      = $batches->count();
        $activeBatches     = $batches->where('status', 'active')->count();
        $totalAnimals      = $batches->sum('quantity_current');
        $totalInvested     = $batches->sum(fn ($b) => $b->total_cost);
        $totalPurchaseCost = $batches->sum('purchase_cost');
        $totalExpenses     = $batches->sum(fn ($b) => $b->total_expenses);

        return view('admin.livestock.index', compact(
            'batches',
            'totalBatches',
            'activeBatches',
            'totalAnimals',
            'totalInvested',
            'totalPurchaseCost',
            'totalExpenses'
        ));
    }

    public function create(): View
    {
        return view('admin.livestock.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'batch_name'         => 'required|string|max:255',
            'animal_type'        => 'required|in:pig,chicken,cattle,goat,sheep,fish,turkey,other',
            'quantity_purchased' => 'required|integer|min:1',
            'purchase_cost'      => 'required|numeric|min:0',
            'purchase_date'      => 'required|date',
            'expected_sale_date' => 'nullable|date|after:purchase_date',
            'notes'              => 'nullable|string',
        ]);

        $validated['quantity_current'] = $validated['quantity_purchased'];
        $validated['created_by']       = auth()->id();

        $batch = LivestockBatch::create($validated);

        return redirect()->route('admin.livestock.show', $batch)
            ->with('success', 'Livestock batch "' . $batch->batch_name . '" created successfully.');
    }

    public function show(LivestockBatch $batch): View
    {
        $batch->load(['batchExpenses.creator', 'creator']);

        return view('admin.livestock.show', compact('batch'));
    }

    public function recordMortality(Request $request, LivestockBatch $batch): RedirectResponse
    {
        $validated = $request->validate([
            'count'  => 'required|integer|min:1|max:' . $batch->quantity_current,
            'notes'  => 'nullable|string|max:500',
        ]);

        $batch->decrement('quantity_current', $validated['count']);

        BatchExpense::create([
            'livestock_batch_id' => $batch->id,
            'type'               => 'other',
            'amount'             => 0,
            'description'        => 'Mortality record: ' . $validated['count'] . ' animal(s) lost' . (isset($validated['notes']) && $validated['notes'] ? '. Notes: ' . $validated['notes'] : '.'),
            'expense_date'       => now()->toDateString(),
            'created_by'         => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', $validated['count'] . ' mortality record(s) logged. Current count updated to ' . ($batch->quantity_current - $validated['count']) . '.');
    }

    public function createExpense(LivestockBatch $batch): View
    {
        return view('admin.livestock.expenses.create', compact('batch'));
    }

    public function storeExpense(Request $request, LivestockBatch $batch): RedirectResponse
    {
        $validated = $request->validate([
            'type'         => 'required|in:feed,treatment,vaccination,labor,transport,equipment,other',
            'amount'       => 'required|numeric|min:1',
            'description'  => ['required', 'string', new MeaningfulDescription()],
            'vendor_name'  => 'nullable|string|max:255',
            'expense_date' => 'required|date',
            'receipt'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('batch-receipts', 'public');
        }

        BatchExpense::create([
            'livestock_batch_id' => $batch->id,
            'type'               => $validated['type'],
            'amount'             => $validated['amount'],
            'description'        => $validated['description'],
            'vendor_name'        => $validated['vendor_name'] ?? null,
            'expense_date'       => $validated['expense_date'],
            'receipt_file'       => $receiptPath,
            'created_by'         => auth()->id(),
        ]);

        return redirect()->route('admin.livestock.show', $batch)
            ->with('success', 'Expense of ₦' . number_format($validated['amount'], 2) . ' recorded for ' . $batch->batch_name . '.');
    }
}
