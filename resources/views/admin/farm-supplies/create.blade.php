@extends('layouts.admin')

@section('page-title', 'Add Supply Item')
@section('breadcrumb', 'Farm ERP · Supplies · Add New')

@section('content')
<div class="max-w-2xl">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.farm-supplies.index') }}" class="btn-outline text-sm p-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="font-display text-2xl font-bold text-content-primary">Add Supply Item</h2>
            <p class="text-sm text-content-muted">Register a new farm supply for inventory tracking</p>
        </div>
    </div>

    <div class="card p-6">
        <form action="{{ route('admin.farm-supplies.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Name --}}
            <div>
                <label class="label" for="name">Supply Name <span class="text-red-400">*</span></label>
                <input type="text" id="name" name="name"
                       value="{{ old('name') }}"
                       placeholder="e.g. Broiler Starter Feed, Newcastle Vaccine, Diesel..."
                       class="input @error('name') border-red-500 @enderror">
                @error('name')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Category --}}
            <div>
                <label class="label" for="category">Category <span class="text-red-400">*</span></label>
                <select id="category" name="category"
                        class="input @error('category') border-red-500 @enderror">
                    <option value="">— Select category —</option>
                    <option value="feed"        {{ old('category') === 'feed'        ? 'selected' : '' }}>🌾 Feed</option>
                    <option value="medication"  {{ old('category') === 'medication'  ? 'selected' : '' }}>💊 Medication</option>
                    <option value="vaccination" {{ old('category') === 'vaccination' ? 'selected' : '' }}>💉 Vaccination</option>
                    <option value="equipment"   {{ old('category') === 'equipment'   ? 'selected' : '' }}>🔧 Equipment</option>
                    <option value="seeds"       {{ old('category') === 'seeds'       ? 'selected' : '' }}>🌱 Seeds</option>
                    <option value="chemicals"   {{ old('category') === 'chemicals'   ? 'selected' : '' }}>🧪 Chemicals</option>
                    <option value="fuel"        {{ old('category') === 'fuel'        ? 'selected' : '' }}>⛽ Fuel</option>
                    <option value="other"       {{ old('category') === 'other'       ? 'selected' : '' }}>📦 Other</option>
                </select>
                @error('category')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Unit + Threshold --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="label" for="unit">Unit of Measurement <span class="text-red-400">*</span></label>
                    <input type="text" id="unit" name="unit"
                           value="{{ old('unit', 'kg') }}"
                           placeholder="kg / liters / bags / units / doses..."
                           class="input @error('unit') border-red-500 @enderror">
                    @error('unit')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-content-muted mt-1">How the quantity will be measured (e.g. kg, bags, liters)</p>
                </div>
                <div>
                    <label class="label" for="low_stock_threshold">Low Stock Threshold <span class="text-red-400">*</span></label>
                    <input type="number" id="low_stock_threshold" name="low_stock_threshold"
                           value="{{ old('low_stock_threshold', 10) }}"
                           min="0" step="0.01"
                           placeholder="10"
                           class="input @error('low_stock_threshold') border-red-500 @enderror">
                    @error('low_stock_threshold')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-content-muted mt-1">Alert when stock falls at or below this level</p>
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label class="label" for="notes">Notes <span class="text-content-muted text-xs">(optional)</span></label>
                <textarea id="notes" name="notes" rows="3"
                          placeholder="Additional information about this supply item..."
                          class="input resize-none @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Info note --}}
            <div class="px-4 py-3 rounded-xl text-xs text-content-muted"
                 style="background: rgba(59,130,246,0.06); border: 1px solid rgba(59,130,246,0.15);">
                <svg class="w-3.5 h-3.5 inline mr-1 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Current stock starts at zero. Use "Add Stock" on the item page to record initial stock quantity after creation.
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Create Supply Item
                </button>
                <a href="{{ route('admin.farm-supplies.index') }}" class="btn-outline">Cancel</a>
            </div>
        </form>
    </div>

</div>
@endsection
