@extends('layouts.admin')

@section('page-title', 'New Livestock Batch')
@section('breadcrumb', 'Farm ERP · Livestock · Create')

@section('content')
<div class="max-w-2xl">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.livestock.index') }}" class="btn-outline text-sm p-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="font-display text-2xl font-bold text-content-primary">New Livestock Batch</h2>
            <p class="text-sm text-content-muted">Register a new livestock group for cost tracking</p>
        </div>
    </div>

    <div class="card p-6">
        <form action="{{ route('admin.livestock.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Batch Name --}}
            <div>
                <label class="label" for="batch_name">Batch Name <span class="text-red-400">*</span></label>
                <input type="text" id="batch_name" name="batch_name"
                       value="{{ old('batch_name') }}"
                       placeholder="e.g. Pig Batch A - May 2026"
                       class="input @error('batch_name') border-red-500 @enderror">
                @error('batch_name')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Animal Type --}}
            <div>
                <label class="label" for="animal_type">Animal Type <span class="text-red-400">*</span></label>
                <select id="animal_type" name="animal_type"
                        class="input @error('animal_type') border-red-500 @enderror">
                    <option value="">— Select animal type —</option>
                    <option value="pig"     {{ old('animal_type') === 'pig'     ? 'selected' : '' }}>🐷 Pig</option>
                    <option value="chicken" {{ old('animal_type') === 'chicken' ? 'selected' : '' }}>🐔 Chicken</option>
                    <option value="cattle"  {{ old('animal_type') === 'cattle'  ? 'selected' : '' }}>🐄 Cattle</option>
                    <option value="goat"    {{ old('animal_type') === 'goat'    ? 'selected' : '' }}>🐐 Goat</option>
                    <option value="sheep"   {{ old('animal_type') === 'sheep'   ? 'selected' : '' }}>🐑 Sheep</option>
                    <option value="fish"    {{ old('animal_type') === 'fish'    ? 'selected' : '' }}>🐟 Fish</option>
                    <option value="turkey"  {{ old('animal_type') === 'turkey'  ? 'selected' : '' }}>🦃 Turkey</option>
                    <option value="other"   {{ old('animal_type') === 'other'   ? 'selected' : '' }}>🐾 Other</option>
                </select>
                @error('animal_type')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Quantity Purchased --}}
            <div>
                <label class="label" for="quantity_purchased">Quantity Purchased <span class="text-red-400">*</span></label>
                <input type="number" id="quantity_purchased" name="quantity_purchased"
                       value="{{ old('quantity_purchased') }}"
                       placeholder="e.g. 100"
                       min="1" step="1"
                       class="input @error('quantity_purchased') border-red-500 @enderror">
                @error('quantity_purchased')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Purchase Cost --}}
            <div>
                <label class="label" for="purchase_cost">Purchase Cost (₦) <span class="text-red-400">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-content-muted font-semibold text-sm">₦</span>
                    <input type="number" id="purchase_cost" name="purchase_cost"
                           value="{{ old('purchase_cost') }}"
                           placeholder="0.00"
                           min="0" step="0.01"
                           class="input pl-7 @error('purchase_cost') border-red-500 @enderror">
                </div>
                @error('purchase_cost')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Dates Row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="label" for="purchase_date">Purchase Date <span class="text-red-400">*</span></label>
                    <input type="date" id="purchase_date" name="purchase_date"
                           value="{{ old('purchase_date', now()->toDateString()) }}"
                           class="input @error('purchase_date') border-red-500 @enderror">
                    @error('purchase_date')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="label" for="expected_sale_date">Expected Sale Date <span class="text-content-muted text-xs">(optional)</span></label>
                    <input type="date" id="expected_sale_date" name="expected_sale_date"
                           value="{{ old('expected_sale_date') }}"
                           class="input @error('expected_sale_date') border-red-500 @enderror">
                    @error('expected_sale_date')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label class="label" for="notes">Notes <span class="text-content-muted text-xs">(optional)</span></label>
                <textarea id="notes" name="notes" rows="3"
                          placeholder="Additional notes about this batch..."
                          class="input resize-none @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Create Batch
                </button>
                <a href="{{ route('admin.livestock.index') }}" class="btn-outline">Cancel</a>
            </div>
        </form>
    </div>

</div>
@endsection
