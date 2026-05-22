@extends('layouts.admin')

@section('page-title', 'New Expense Request')
@section('breadcrumb', 'Financial Control — Submit a new expense for approval')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.finance.dashboard') }}"
           class="w-9 h-9 rounded-xl flex items-center justify-center text-content-muted hover:text-content-primary transition-all"
           style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="font-display text-2xl font-bold text-content-primary">New Expense Request</h2>
            <p class="text-sm text-content-muted mt-0.5">Submit a farm expense for admin approval</p>
        </div>
    </div>

    {{-- Wallet Balance Reference --}}
    <div class="rounded-xl px-5 py-3.5 flex items-center gap-4"
         style="background: rgba(184,243,151,0.05); border: 1px solid rgba(184,243,151,0.12);">
        <svg class="w-4 h-4 text-brand-green flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
        </svg>
        <div class="flex-1">
            <span class="text-xs text-content-muted">Current Wallet Balance: </span>
            <span class="text-sm font-bold text-brand-green font-mono">{{ $wallet->formatted_balance }}</span>
        </div>
        <span class="text-xs text-content-muted">Admin will approve if funds are available</span>
    </div>

    {{-- Form --}}
    <div class="rounded-2xl overflow-hidden" style="background: rgba(17,17,17,0.8); border: 1px solid rgba(255,255,255,0.06);">
        <div class="px-6 py-4" style="border-bottom: 1px solid rgba(255,255,255,0.05); background: linear-gradient(90deg, rgba(184,243,151,0.04) 0%, transparent 100%);">
            <h3 class="text-sm font-bold text-content-primary">Expense Details</h3>
            <p class="text-xs text-content-muted mt-0.5">Provide complete and accurate information. All requests are reviewed by admin before funds are released.</p>
        </div>

        <form action="{{ route('admin.finance.expenses.store') }}" method="POST"
              x-data="{ charCount: {{ strlen(old('description', '')) }}, descVal: '{{ addslashes(old('description', '')) }}' }"
              class="p-6 space-y-5">
            @csrf

            {{-- Short Title --}}
            <div class="space-y-2">
                <label for="short_title" class="block text-sm font-semibold text-content-secondary">
                    Expense Title <span class="text-red-400">*</span>
                </label>
                <input type="text"
                       id="short_title"
                       name="short_title"
                       value="{{ old('short_title') }}"
                       maxlength="100"
                       placeholder="e.g. Weekly poultry feed purchase — June 2026"
                       class="w-full px-4 py-3 rounded-xl text-sm text-content-primary transition-all outline-none focus:ring-2
                              @error('short_title') ring-2 ring-red-500/50 @else ring-brand-green/0 @enderror"
                       style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1);"
                       required>
                @error('short_title')
                <p class="text-xs text-red-400 flex items-center gap-1.5">
                    <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            {{-- Category + Amount row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Category --}}
                <div class="space-y-2">
                    <label for="category" class="block text-sm font-semibold text-content-secondary">
                        Category <span class="text-red-400">*</span>
                    </label>
                    <select id="category"
                            name="category"
                            class="w-full px-4 py-3 rounded-xl text-sm text-content-primary transition-all outline-none focus:ring-2
                                   @error('category') ring-2 ring-red-500/50 @else ring-brand-green/0 @enderror"
                            style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1);"
                            required>
                        <option value="" disabled {{ !old('category') ? 'selected' : '' }}>Select category</option>
                        @foreach(['feed' => 'Feed & Nutrition', 'labor' => 'Labor & Wages', 'fuel' => 'Fuel & Energy', 'veterinary' => 'Veterinary & Health', 'equipment' => 'Equipment & Tools', 'maintenance' => 'Maintenance & Repairs', 'logistics' => 'Logistics & Transport', 'other' => 'Other'] as $val => $label)
                        <option value="{{ $val }}" {{ old('category') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category')
                    <p class="text-xs text-red-400 flex items-center gap-1.5">
                        <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Amount --}}
                <div class="space-y-2">
                    <label for="amount" class="block text-sm font-semibold text-content-secondary">
                        Amount (₦) <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-brand-green font-bold select-none">₦</span>
                        <input type="number"
                               id="amount"
                               name="amount"
                               value="{{ old('amount') }}"
                               min="1"
                               step="0.01"
                               placeholder="0.00"
                               class="w-full pl-9 pr-4 py-3 rounded-xl text-sm text-content-primary font-mono font-bold transition-all outline-none focus:ring-2
                                      @error('amount') ring-2 ring-red-500/50 @else ring-brand-green/0 @enderror"
                               style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1);"
                               required>
                    </div>
                    @error('amount')
                    <p class="text-xs text-red-400 flex items-center gap-1.5">
                        <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>
            </div>

            {{-- Expense Date + Vendor --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Expense Date --}}
                <div class="space-y-2">
                    <label for="expense_date" class="block text-sm font-semibold text-content-secondary">
                        Expense Date <span class="text-red-400">*</span>
                    </label>
                    <input type="date"
                           id="expense_date"
                           name="expense_date"
                           value="{{ old('expense_date', now()->toDateString()) }}"
                           class="w-full px-4 py-3 rounded-xl text-sm text-content-primary transition-all outline-none focus:ring-2
                                  @error('expense_date') ring-2 ring-red-500/50 @else ring-brand-green/0 @enderror"
                           style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1);"
                           required>
                    @error('expense_date')
                    <p class="text-xs text-red-400 flex items-center gap-1.5">
                        <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Vendor Name --}}
                <div class="space-y-2">
                    <label for="vendor_name" class="block text-sm font-semibold text-content-secondary">
                        Vendor / Supplier <span class="text-content-muted text-xs">(optional)</span>
                    </label>
                    <input type="text"
                           id="vendor_name"
                           name="vendor_name"
                           value="{{ old('vendor_name') }}"
                           maxlength="255"
                           placeholder="e.g. AgriSupply Nigeria Ltd"
                           class="w-full px-4 py-3 rounded-xl text-sm text-content-primary transition-all outline-none focus:ring-2
                                  @error('vendor_name') ring-2 ring-red-500/50 @else ring-brand-green/0 @enderror"
                           style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1);">
                    @error('vendor_name')
                    <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Description --}}
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label for="description" class="block text-sm font-semibold text-content-secondary">
                        Full Description <span class="text-red-400">*</span>
                    </label>
                    <span class="text-xs font-mono" :class="charCount < 20 ? 'text-red-400' : 'text-brand-green'">
                        <span x-text="charCount"></span> chars
                        <span x-show="charCount < 20" class="text-red-400">(need {{ 20 }})&nbsp;</span>
                        <span x-show="charCount >= 20" class="text-brand-green">&#10003;</span>
                    </span>
                </div>
                <textarea id="description"
                          name="description"
                          rows="5"
                          x-on:input="charCount = $event.target.value.length"
                          placeholder="Describe what will be bought, why it is needed, and how/where it will be used. Example: Purchase of 500kg of broiler starter feed from AgriSupply Nigeria for the batch of 1,000 birds at Pen A. Feed will be used for the first 2 weeks of growth cycle starting June 2026."
                          class="w-full px-4 py-3 rounded-xl text-sm text-content-primary resize-y transition-all outline-none focus:ring-2
                                 @error('description') ring-2 ring-red-500/50 @else ring-brand-green/0 @enderror"
                          style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); min-height: 120px;"
                          required>{{ old('description') }}</textarea>
                @error('description')
                <p class="text-xs text-red-400 flex items-center gap-1.5">
                    <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
                @enderror
                <div class="rounded-lg p-3 flex gap-2.5" style="background: rgba(234,179,8,0.06); border: 1px solid rgba(234,179,8,0.12);">
                    <svg class="w-3.5 h-3.5 text-yellow-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xs text-yellow-400/80">
                        <strong>Required:</strong> Describe <em>what</em> will be bought, <em>why</em> it is needed, and <em>where</em> it will be used. Vague descriptions (e.g. "food", "misc", "expense") will be rejected. Minimum 20 characters.
                    </p>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm text-surface-bg transition-all hover:scale-[1.01] active:scale-[0.99]"
                        style="background: linear-gradient(135deg, #B8F397 0%, #6FAE4B 100%); box-shadow: 0 4px 20px rgba(184,243,151,0.2);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Submit Expense Request
                </button>
                <a href="{{ route('admin.finance.dashboard') }}"
                   class="px-6 py-3 rounded-xl font-semibold text-sm text-content-secondary hover:text-content-primary transition-all"
                   style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
