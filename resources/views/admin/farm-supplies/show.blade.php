@extends('layouts.admin')

@section('page-title', $farmSupply->name)
@section('breadcrumb', 'Farm ERP · Supplies · ' . $farmSupply->name)

@section('content')
<div class="space-y-6" x-data="{ stockInForm: false, stockOutForm: false }">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div class="flex items-start gap-4">
            <a href="{{ route('admin.farm-supplies.index') }}" class="btn-outline text-sm p-2 mt-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="text-3xl">{{ $farmSupply->category_icon }}</span>
                    <h2 class="font-display text-2xl font-bold text-content-primary">{{ $farmSupply->name }}</h2>
                    <span class="badge text-xs px-2.5 py-1 rounded-lg border {{ $farmSupply->status_color }}">{{ $farmSupply->status_label }}</span>
                </div>
                <p class="text-sm text-content-muted mt-1">{{ $farmSupply->category_label }} · Low stock alert at {{ number_format($farmSupply->low_stock_threshold, 2) }} {{ $farmSupply->unit }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <button @click="stockInForm = !stockInForm; stockOutForm = false"
                    class="btn-primary text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Stock
            </button>
            <button @click="stockOutForm = !stockOutForm; stockInForm = false"
                    class="btn-outline text-sm text-red-400 border-red-400/30 hover:bg-red-400/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                Record Usage
            </button>
        </div>
    </div>

    {{-- Current Stock Hero --}}
    <div class="card p-6 text-center" style="background: linear-gradient(135deg, rgba(184,243,151,0.06) 0%, transparent 60%);">
        <p class="text-xs text-content-muted uppercase tracking-wider mb-2">Current Stock</p>
        <p class="text-5xl font-display font-bold {{ $farmSupply->stock_quantity <= 0 ? 'text-red-400' : ($farmSupply->stock_quantity <= $farmSupply->low_stock_threshold ? 'text-yellow-400' : 'text-brand-green') }}">
            {{ number_format($farmSupply->stock_quantity, 2) }}
        </p>
        <p class="text-lg text-content-muted mt-1">{{ $farmSupply->unit }}</p>
        @if($farmSupply->notes)
        <p class="text-sm text-content-muted mt-3 max-w-md mx-auto">{{ $farmSupply->notes }}</p>
        @endif
    </div>

    {{-- Stock In Form --}}
    <div x-show="stockInForm" x-cloak x-transition
         class="card p-5" style="border-color: rgba(184,243,151,0.2); background: rgba(184,243,151,0.03);">
        <h3 class="text-sm font-bold text-brand-green mb-4 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Stock (Stock In)
        </h3>
        <form action="{{ route('admin.farm-supplies.stock-in', $farmSupply) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="label text-xs" for="si_quantity">Quantity ({{ $farmSupply->unit }}) <span class="text-red-400">*</span></label>
                    <input type="number" id="si_quantity" name="quantity"
                           min="0.01" step="0.01" placeholder="e.g. 100"
                           class="input @error('quantity') border-red-500 @enderror">
                    @error('quantity') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label text-xs" for="si_unit_cost">Unit Cost (₦) <span class="text-content-muted text-xs">(optional)</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-content-muted text-sm">₦</span>
                        <input type="number" id="si_unit_cost" name="unit_cost"
                               min="0" step="0.01" placeholder="0.00"
                               class="input pl-7">
                    </div>
                </div>
                <div>
                    <label class="label text-xs" for="si_linked_batch">Link to Batch <span class="text-content-muted text-xs">(optional)</span></label>
                    <select id="si_linked_batch" name="linked_batch_id" class="input">
                        <option value="">— None —</option>
                        @foreach($activeBatches as $b)
                        <option value="{{ $b->id }}">{{ $b->animal_type_icon }} {{ $b->batch_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label text-xs" for="si_linked_tx">Link to Wallet Tx <span class="text-content-muted text-xs">(optional)</span></label>
                    <select id="si_linked_tx" name="linked_wallet_transaction_id" class="input">
                        <option value="">— None —</option>
                        @foreach($walletTransactions as $tx)
                        <option value="{{ $tx->id }}">{{ $tx->expense_date?->format('M d') }} · {{ Str::limit($tx->short_title ?? $tx->description, 30) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <label class="label text-xs" for="si_notes">Notes <span class="text-content-muted text-xs">(optional)</span></label>
                <input type="text" id="si_notes" name="notes" placeholder="e.g. Purchased from XYZ supplier..." class="input">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="btn-primary text-sm">Confirm Stock In</button>
                <button type="button" @click="stockInForm = false" class="btn-outline text-sm">Cancel</button>
            </div>
        </form>
    </div>

    {{-- Stock Out Form --}}
    <div x-show="stockOutForm" x-cloak x-transition
         class="card p-5" style="border-color: rgba(239,68,68,0.2); background: rgba(239,68,68,0.03);">
        <h3 class="text-sm font-bold text-red-400 mb-4 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
            Record Usage (Stock Out)
        </h3>
        <form action="{{ route('admin.farm-supplies.stock-out', $farmSupply) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="label text-xs" for="so_quantity">Quantity Used ({{ $farmSupply->unit }}) <span class="text-red-400">*</span></label>
                    <input type="number" id="so_quantity" name="quantity"
                           min="0.01" step="0.01"
                           max="{{ $farmSupply->stock_quantity }}"
                           placeholder="e.g. 50"
                           class="input @error('quantity') border-red-500 @enderror">
                    @error('quantity') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-content-muted mt-1">Available: {{ number_format($farmSupply->stock_quantity, 2) }} {{ $farmSupply->unit }}</p>
                </div>
                <div>
                    <label class="label text-xs" for="so_linked_batch">Link to Batch <span class="text-content-muted text-xs">(optional)</span></label>
                    <select id="so_linked_batch" name="linked_batch_id" class="input">
                        <option value="">— None —</option>
                        @foreach($activeBatches as $b)
                        <option value="{{ $b->id }}">{{ $b->animal_type_icon }} {{ $b->batch_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label text-xs" for="so_notes">Reason / Notes <span class="text-red-400">*</span></label>
                    <input type="text" id="so_notes" name="notes"
                           placeholder="e.g. Fed to Pig Batch A on 20 May..."
                           class="input @error('notes') border-red-500 @enderror">
                    @error('notes') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="btn-danger text-sm">Confirm Usage</button>
                <button type="button" @click="stockOutForm = false" class="btn-outline text-sm">Cancel</button>
            </div>
        </form>
    </div>

    {{-- Movement History --}}
    <div class="card overflow-hidden" id="history">
        <div class="px-5 py-4" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            <h3 class="text-sm font-bold text-content-primary">Movement History</h3>
            <p class="text-xs text-content-muted mt-0.5">Last {{ $farmSupply->movements->count() }} records</p>
        </div>

        @if($farmSupply->movements->isEmpty())
        <div class="p-8 text-center">
            <p class="text-sm text-content-muted">No movements recorded yet. Use the buttons above to add stock or record usage.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="background: rgba(255,255,255,0.02); border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <th class="px-4 py-3 text-left text-xs font-bold text-content-muted uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-content-muted uppercase">Type</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-content-muted uppercase">Quantity</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-content-muted uppercase">Notes</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-content-muted uppercase">Linked Batch</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-content-muted uppercase">By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($farmSupply->movements as $movement)
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="px-4 py-3 text-sm text-content-muted font-mono">{{ $movement->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-0.5 rounded-md border {{ $movement->type_color }}">{{ $movement->type_label }}</span>
                        </td>
                        <td class="px-4 py-3 text-right text-sm font-mono font-bold text-content-primary">
                            {{ $movement->type === 'stock_out' ? '-' : '+' }}{{ number_format($movement->quantity, 2) }} {{ $farmSupply->unit }}
                        </td>
                        <td class="px-4 py-3 text-sm text-content-secondary max-w-xs truncate">{{ $movement->notes ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-content-muted">
                            @if($movement->linked_batch_id)
                            @php $linkedBatch = $activeBatches->firstWhere('id', $movement->linked_batch_id); @endphp
                            {{ $linkedBatch ? $linkedBatch->batch_name : 'Batch #' . $movement->linked_batch_id }}
                            @else
                            —
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-content-muted">{{ $movement->creator?->name ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
@endsection
