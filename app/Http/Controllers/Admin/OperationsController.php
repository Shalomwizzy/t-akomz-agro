<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FarmSupply;
use App\Models\LivestockBatch;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\View\View;

class OperationsController extends Controller
{
    public function dashboard(): View
    {
        // Load all batches with expenses for the dashboard
        $batches = LivestockBatch::with('batchExpenses')
            ->orderByRaw("CASE status WHEN 'active' THEN 0 WHEN 'partially_sold' THEN 1 WHEN 'sold' THEN 2 ELSE 3 END")
            ->orderBy('created_at', 'desc')
            ->get();

        // Summary stats
        $totalInvested  = $batches->sum(fn ($b) => $b->total_cost);
        $totalAnimals   = $batches->sum('quantity_current');
        $activeBatches  = $batches->where('status', 'active')->count();
        $totalBatches   = $batches->count();

        // Financial snapshot
        $wallet         = Wallet::main();
        $walletBalance  = $wallet ? (float) $wallet->balance : 0.0;
        $totalBatchExp  = $batches->sum(fn ($b) => $b->total_expenses);

        // Pending expense approvals
        $pendingExpenses = WalletTransaction::where('status', 'pending')->count();

        // Low stock supplies (load all and filter in PHP)
        $allSupplies       = FarmSupply::with('movements')->get();
        $lowStockCount     = $allSupplies->filter(fn ($s) => in_array($s->status, ['low_stock', 'out_of_stock']))->count();

        return view('admin.operations.dashboard', compact(
            'batches',
            'totalInvested',
            'totalAnimals',
            'activeBatches',
            'totalBatches',
            'walletBalance',
            'totalBatchExp',
            'pendingExpenses',
            'lowStockCount'
        ));
    }
}
