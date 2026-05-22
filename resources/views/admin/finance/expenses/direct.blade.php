@extends('layouts.admin')

@section('page-title', 'Log Expense')
@section('breadcrumb', 'Financial Control — Log and deduct an expense immediately')

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
            <h2 class="font-display text-2xl font-bold text-content-primary">Log Expense</h2>
            <p class="text-sm text-content-muted mt-0.5">Record a spend — funds are deducted from the wallet immediately</p>
        </div>
    </div>

    {{-- Error flash --}}
    @if(session('error'))
    <div class="rounded-xl px-5 py-3.5 flex items-center gap-3"
         style="background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2);">
        <svg class="w-4 h-4 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <p class="text-sm text-red-400">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Form --}}
    <div class="rounded-2xl overflow-hidden" style="background: rgba(17,17,17,0.8); border: 1px solid rgba(255,255,255,0.06);">
        <div class="px-6 py-4" style="border-bottom: 1px solid rgba(255,255,255,0.05); background: linear-gradient(90deg, rgba(239,68,68,0.05) 0%, transparent 100%);">
            <h3 class="text-sm font-bold text-content-primary">Expense Details</h3>
            <p class="text-xs text-content-muted mt-0.5">This expense will be recorded and the amount deducted from the wallet balance right away.</p>
        </div>

        <form action="{{ route('admin.finance.expenses.direct') }}" method="POST"
              x-data="{
                  charCount: {{ strlen(old('description', '')) }},
                  category: '{{ old('category', '') }}'
              }"
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
                       placeholder="e.g. Diesel for water pump — May 2026"
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

            {{-- Category + Amount --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Category --}}
                <div class="space-y-2">
                    <label for="category" class="block text-sm font-semibold text-content-secondary">
                        Category <span class="text-red-400">*</span>
                    </label>
                    <select id="category"
                            name="category"
                            x-model="category"
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

                    {{-- Other: specify field --}}
                    <div x-show="category === 'other'" x-cloak class="mt-3">
                        <label class="block text-xs font-semibold text-content-muted mb-1.5">
                            Specify — what is this "Other" expense? <span class="text-red-400">*</span>
                        </label>
                        <input type="text"
                               name="other_specify"
                               value="{{ old('other_specify') }}"
                               placeholder="e.g. Borehole repair, Office supplies, Security fee..."
                               class="w-full px-4 py-2.5 rounded-xl text-sm text-content-primary outline-none
                                      @error('other_specify') ring-2 ring-red-500/50 @enderror"
                               style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15);">
                        @error('other_specify')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
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
                        Description <span class="text-red-400">*</span>
                    </label>
                    <span class="text-xs font-mono" :class="charCount < 10 ? 'text-red-400' : 'text-brand-green'">
                        <span x-text="charCount"></span> chars
                        <span x-show="charCount < 10" class="text-red-400">(need 10)&nbsp;</span>
                        <span x-show="charCount >= 10" class="text-brand-green">&#10003;</span>
                    </span>
                </div>
                <textarea id="description"
                          name="description"
                          rows="4"
                          x-on:input="charCount = $event.target.value.length"
                          placeholder="Briefly describe what was purchased and where it will be used."
                          class="w-full px-4 py-3 rounded-xl text-sm text-content-primary resize-y transition-all outline-none focus:ring-2
                                 @error('description') ring-2 ring-red-500/50 @else ring-brand-green/0 @enderror"
                          style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); min-height: 100px;"
                          required>{{ old('description') }}</textarea>
                @error('description')
                <p class="text-xs text-red-400 flex items-center gap-1.5">
                    <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        onclick="return confirm('This will immediately deduct the entered amount from the wallet. Proceed?')"
                        class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm text-surface-bg transition-all hover:scale-[1.01] active:scale-[0.99]"
                        style="background: linear-gradient(135deg, #B8F397 0%, #6FAE4B 100%); box-shadow: 0 4px 20px rgba(184,243,151,0.2);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Submit & Deduct Now
                </button>
                <a href="{{ route('admin.finance.dashboard') }}"
                   class="px-6 py-3 rounded-xl font-semibold text-sm text-content-secondary hover:text-content-primary transition-all"
                   style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    {{-- Notice --}}
    <div class="rounded-xl p-4 flex gap-3"
         style="background: rgba(234,179,8,0.06); border: 1px solid rgba(234,179,8,0.15);">
        <svg class="w-4 h-4 text-yellow-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="text-xs text-yellow-400/80 space-y-1">
            <p class="font-semibold">Immediate Deduction</p>
            <p>This form logs an expense that is deducted from the wallet balance instantly — no approval step. Use it for cash purchases already made. For planned or future expenses requiring admin sign-off, use <a href="{{ route('admin.finance.expenses.create') }}" class="underline">New Expense Request</a> instead.</p>
        </div>
    </div>
</div>
@endsection
