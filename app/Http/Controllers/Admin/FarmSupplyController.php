<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FarmSupply;
use App\Models\SupplyMovement;
use App\Models\LivestockBatch;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FarmSupplyController extends Controller
{
    public function index(): View
    {
        $supplies = FarmSupply::with(['movements'])->orderBy('name')->get();

        // Compute stats in PHP (stock is a computed field)
        $totalItems   = $supplies->count();
        $lowStockCount = $supplies->filter(fn ($s) => $s->status === 'low_stock')->count();
        $outOfStockCount = $supplies->filter(fn ($s) => $s->status === 'out_of_stock')->count();

        return view('admin.farm-supplies.index', compact(
            'supplies',
            'totalItems',
            'lowStockCount',
            'outOfStockCount'
        ));
    }

    public function create(): View
    {
        return view('admin.farm-supplies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'category'            => 'required|in:feed,medication,vaccination,equipment,seeds,chemicals,fuel,other',
            'unit'                => 'required|string|max:50',
            'low_stock_threshold' => 'required|numeric|min:0',
            'notes'               => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        FarmSupply::create($validated);

        return redirect()->route('admin.farm-supplies.index')
            ->with('success', 'Farm supply item "' . $validated['name'] . '" created successfully.');
    }

    public function show(FarmSupply $farmSupply): View
    {
        $farmSupply->load(['movements' => function ($query) {
            $query->with('creator')->latest()->limit(30);
        }]);

        $activeBatches = LivestockBatch::active()->orderBy('batch_name')->get();
        $walletTransactions = WalletTransaction::where('status', 'completed')
            ->orderBy('expense_date', 'desc')
            ->limit(50)
            ->get();

        return view('admin.farm-supplies.show', compact('farmSupply', 'activeBatches', 'walletTransactions'));
    }

    public function stockIn(Request $request, FarmSupply $farmSupply): RedirectResponse
    {
        $validated = $request->validate([
            'quantity'                      => 'required|numeric|min:0.01',
            'unit_cost'                     => 'nullable|numeric|min:0',
            'notes'                         => 'nullable|string|max:500',
            'linked_wallet_transaction_id'  => 'nullable|exists:wallet_transactions,id',
            'linked_batch_id'               => 'nullable|exists:livestock_batches,id',
        ]);

        SupplyMovement::create([
            'farm_supply_id'               => $farmSupply->id,
            'type'                         => 'stock_in',
            'quantity'                     => $validated['quantity'],
            'unit_cost'                    => $validated['unit_cost'] ?? null,
            'notes'                        => $validated['notes'] ?? null,
            'linked_wallet_transaction_id' => $validated['linked_wallet_transaction_id'] ?? null,
            'linked_batch_id'              => $validated['linked_batch_id'] ?? null,
            'created_by'                   => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', number_format($validated['quantity'], 2) . ' ' . $farmSupply->unit . ' added to ' . $farmSupply->name . '.');
    }

    public function stockOut(Request $request, FarmSupply $farmSupply): RedirectResponse
    {
        $validated = $request->validate([
            'quantity'        => 'required|numeric|min:0.01',
            'notes'           => 'required|string|max:500',
            'linked_batch_id' => 'nullable|exists:livestock_batches,id',
        ]);

        // Reload movements to compute current stock
        $farmSupply->load('movements');
        $currentStock = $farmSupply->stock_quantity;

        if ($validated['quantity'] > $currentStock) {
            return redirect()->back()
                ->withErrors(['quantity' => 'Cannot remove ' . $validated['quantity'] . ' ' . $farmSupply->unit . '. Current stock is only ' . number_format($currentStock, 2) . ' ' . $farmSupply->unit . '.'])
                ->withInput();
        }

        SupplyMovement::create([
            'farm_supply_id'  => $farmSupply->id,
            'type'            => 'stock_out',
            'quantity'        => $validated['quantity'],
            'notes'           => $validated['notes'],
            'linked_batch_id' => $validated['linked_batch_id'] ?? null,
            'created_by'      => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', number_format($validated['quantity'], 2) . ' ' . $farmSupply->unit . ' recorded as used from ' . $farmSupply->name . '.');
    }
}
