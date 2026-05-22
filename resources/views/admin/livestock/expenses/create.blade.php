@extends('layouts.admin')

@section('page-title', 'Add Expense — ' . $batch->batch_name)
@section('breadcrumb', 'Farm ERP · Livestock · ' . $batch->batch_name . ' · Add Expense')

@section('content')
<div class="max-w-2xl">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.livestock.show', $batch) }}" class="btn-outline text-sm p-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="font-display text-2xl font-bold text-content-primary">Add Expense</h2>
            <p class="text-sm text-content-muted">
                <span class="text-xl mr-1">{{ $batch->animal_type_icon }}</span>
                {{ $batch->batch_name }} · {{ $batch->animal_type_label }}
            </p>
        </div>
    </div>

    <div class="card p-6">
        <form action="{{ route('admin.livestock.expenses.store', $batch) }}" method="POST"
              enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- Type --}}
            <div>
                <label class="label" for="type">Expense Type <span class="text-red-400">*</span></label>
                <select id="type" name="type"
                        class="input @error('type') border-red-500 @enderror">
                    <option value="">— Select type —</option>
                    <option value="feed"        {{ old('type') === 'feed'        ? 'selected' : '' }}>🌾 Feed</option>
                    <option value="treatment"   {{ old('type') === 'treatment'   ? 'selected' : '' }}>💊 Treatment</option>
                    <option value="vaccination" {{ old('type') === 'vaccination' ? 'selected' : '' }}>💉 Vaccination</option>
                    <option value="labor"       {{ old('type') === 'labor'       ? 'selected' : '' }}>👷 Labor</option>
                    <option value="transport"   {{ old('type') === 'transport'   ? 'selected' : '' }}>🚛 Transport</option>
                    <option value="equipment"   {{ old('type') === 'equipment'   ? 'selected' : '' }}>🔧 Equipment</option>
                    <option value="other"       {{ old('type') === 'other'       ? 'selected' : '' }}>📦 Other</option>
                </select>
                @error('type')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Amount + Date --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="label" for="amount">Amount (₦) <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-content-muted font-semibold text-sm">₦</span>
                        <input type="number" id="amount" name="amount"
                               value="{{ old('amount') }}"
                               placeholder="0.00"
                               min="1" step="0.01"
                               class="input pl-7 @error('amount') border-red-500 @enderror">
                    </div>
                    @error('amount')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="label" for="expense_date">Expense Date <span class="text-red-400">*</span></label>
                    <input type="date" id="expense_date" name="expense_date"
                           value="{{ old('expense_date', now()->toDateString()) }}"
                           class="input @error('expense_date') border-red-500 @enderror">
                    @error('expense_date')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Description --}}
            <div x-data="{ chars: {{ strlen(old('description', '')) }} }">
                <label class="label" for="description">
                    Description <span class="text-red-400">*</span>
                    <span class="ml-auto text-content-muted font-normal" :class="chars < 20 ? 'text-red-400' : 'text-brand-green'">
                        <span x-text="chars"></span>/20 min
                    </span>
                </label>
                <textarea id="description" name="description" rows="3"
                          @input="chars = $el.value.length"
                          placeholder="Describe what was purchased, why it was needed, and where it will be used (minimum 20 characters)..."
                          class="input resize-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-content-muted mt-1">Be specific — include what was bought, purpose, and location of use.</p>
            </div>

            {{-- Vendor Name --}}
            <div>
                <label class="label" for="vendor_name">Vendor / Supplier Name <span class="text-content-muted text-xs">(optional)</span></label>
                <input type="text" id="vendor_name" name="vendor_name"
                       value="{{ old('vendor_name') }}"
                       placeholder="e.g. Agro Feeds Ltd"
                       class="input @error('vendor_name') border-red-500 @enderror">
                @error('vendor_name')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Receipt Upload --}}
            <div>
                <label class="label" for="receipt">Receipt / Invoice <span class="text-content-muted text-xs">(optional — JPG, PNG or PDF, max 5MB)</span></label>
                <input type="file" id="receipt" name="receipt"
                       accept=".jpg,.jpeg,.png,.pdf"
                       class="block w-full text-sm text-content-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-green/10 file:text-brand-green hover:file:bg-brand-green/20 file:cursor-pointer cursor-pointer @error('receipt') border border-red-500 rounded-xl @enderror">
                @error('receipt')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Expense
                </button>
                <a href="{{ route('admin.livestock.show', $batch) }}" class="btn-outline">Cancel</a>
            </div>
        </form>
    </div>

</div>
@endsection
