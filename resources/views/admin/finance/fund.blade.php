@extends('layouts.admin')

@section('page-title', 'Fund Wallet')
@section('breadcrumb', 'Financial Control — Deposit funds into the farm wallet')

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
            <h2 class="font-display text-2xl font-bold text-content-primary">Fund Wallet</h2>
            <p class="text-sm text-content-muted mt-0.5">Deposit funds into the T-Akomz Farm Wallet</p>
        </div>
    </div>

    {{-- Current Balance Card --}}
    <div class="rounded-2xl p-5 flex items-center gap-5"
         style="background: linear-gradient(135deg, rgba(184,243,151,0.08) 0%, rgba(17,17,17,0.9) 100%); border: 1px solid rgba(184,243,151,0.2);">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
             style="background: rgba(184,243,151,0.15); border: 1px solid rgba(184,243,151,0.25);">
            <svg class="w-6 h-6 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-xs text-content-muted font-semibold uppercase tracking-wider">Current Balance</p>
            <p class="font-display text-3xl font-black text-brand-green">{{ $wallet->formatted_balance }}</p>
        </div>
        <div class="text-right hidden sm:block">
            <p class="text-xs text-content-muted">Total Funded</p>
            <p class="text-sm font-bold text-blue-400 font-mono">{{ $wallet->formatted_funded }}</p>
            <p class="text-xs text-content-muted mt-1">Total Spent</p>
            <p class="text-sm font-bold text-red-400 font-mono">{{ $wallet->formatted_spent }}</p>
        </div>
    </div>

    {{-- Fund Form --}}
    <div class="rounded-2xl overflow-hidden" style="background: rgba(17,17,17,0.8); border: 1px solid rgba(255,255,255,0.06);">
        <div class="px-6 py-4" style="border-bottom: 1px solid rgba(255,255,255,0.05); background: linear-gradient(90deg, rgba(184,243,151,0.05) 0%, transparent 100%);">
            <h3 class="text-sm font-bold text-content-primary">Deposit Details</h3>
            <p class="text-xs text-content-muted mt-0.5">All wallet funding is logged and auditable. Minimum deposit: ₦100.</p>
        </div>

        <form action="{{ route('admin.finance.fund.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            {{-- Amount --}}
            <div class="space-y-2">
                <label for="amount" class="block text-sm font-semibold text-content-secondary">
                    Deposit Amount <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-brand-green font-bold text-lg select-none">₦</span>
                    <input type="number"
                           id="amount"
                           name="amount"
                           value="{{ old('amount') }}"
                           min="100"
                           step="0.01"
                           placeholder="0.00"
                           class="w-full pl-9 pr-4 py-3 rounded-xl text-content-primary text-lg font-mono font-bold transition-all outline-none focus:ring-2
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

                {{-- Quick amount buttons --}}
                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach([10000, 50000, 100000, 250000, 500000] as $preset)
                    <button type="button"
                            onclick="document.getElementById('amount').value = {{ $preset }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold text-content-muted hover:text-brand-green transition-all"
                            style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                        ₦{{ number_format($preset) }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Description --}}
            <div class="space-y-2" x-data="{ charCount: {{ strlen(old('description', '')) }} }">
                <div class="flex items-center justify-between">
                    <label for="description" class="block text-sm font-semibold text-content-secondary">
                        Funding Description <span class="text-red-400">*</span>
                    </label>
                    <span class="text-xs font-mono" :class="charCount < 20 ? 'text-red-400' : 'text-content-muted'">
                        <span x-text="charCount"></span> chars
                        <span x-show="charCount < 20" class="text-red-400"> (min 20)</span>
                    </span>
                </div>
                <textarea id="description"
                          name="description"
                          rows="4"
                          x-on:input="charCount = $event.target.value.length"
                          placeholder="e.g. Monthly operational budget funding from business profits for Q2 2026. Covers feed, labor, and veterinary expenses."
                          class="w-full px-4 py-3 rounded-xl text-sm text-content-primary resize-none transition-all outline-none focus:ring-2
                                 @error('description') ring-2 ring-red-500/50 @else ring-brand-green/0 @enderror"
                          style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1);"
                          required>{{ old('description') }}</textarea>
                @error('description')
                <p class="text-xs text-red-400 flex items-center gap-1.5">
                    <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
                @enderror
                <p class="text-xs text-content-muted">Describe the source of funds and the purpose. This creates an audit trail. Minimum 20 characters required.</p>
            </div>

            {{-- Allocate to Manager --}}
            <div class="space-y-2">
                <label for="allocated_to" class="block text-sm font-semibold text-content-secondary">
                    Allocate to Manager <span class="text-xs font-normal text-content-muted">(optional)</span>
                </label>
                <select id="allocated_to"
                        name="allocated_to"
                        class="w-full px-4 py-3 rounded-xl text-sm text-content-primary transition-all outline-none focus:ring-2 ring-brand-green/0"
                        style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1);">
                    <option value="">— General wallet top-up —</option>
                    @foreach($managers as $m)
                    <option value="{{ $m->id }}" {{ old('allocated_to') == $m->id ? 'selected' : '' }}>
                        {{ $m->name }} ({{ $m->email }})
                    </option>
                    @endforeach
                </select>
                <p class="text-xs text-content-muted">If selected, this funding will be tracked under this manager's budget.</p>
            </div>

            {{-- Project / Purpose Name --}}
            <div class="space-y-2">
                <label for="project_name" class="block text-sm font-semibold text-content-secondary">
                    Project / Purpose Name <span class="text-xs font-normal text-content-muted">(optional)</span>
                </label>
                <input type="text"
                       id="project_name"
                       name="project_name"
                       value="{{ old('project_name') }}"
                       maxlength="150"
                       placeholder="e.g. Poultry Shed Construction Q2 2026"
                       class="w-full px-4 py-3 rounded-xl text-sm text-content-primary transition-all outline-none focus:ring-2 ring-brand-green/0"
                       style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1);">
                @error('project_name')
                <p class="text-xs text-red-400 flex items-center gap-1.5">
                    <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm text-surface-bg transition-all hover:scale-[1.01] active:scale-[0.99]"
                        style="background: linear-gradient(135deg, #B8F397 0%, #6FAE4B 100%); box-shadow: 0 4px 20px rgba(184,243,151,0.25);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Deposit Funds
                </button>
                <a href="{{ route('admin.finance.dashboard') }}"
                   class="px-6 py-3 rounded-xl font-semibold text-sm text-content-secondary hover:text-content-primary transition-all"
                   style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    {{-- Info Note --}}
    <div class="rounded-xl p-4 flex gap-3"
         style="background: rgba(59,130,246,0.06); border: 1px solid rgba(59,130,246,0.15);">
        <svg class="w-4 h-4 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="text-xs text-blue-400/90 space-y-1">
            <p class="font-semibold">Audit Notice</p>
            <p>Every deposit is recorded with a timestamp, the depositing admin's identity, and the description provided. Only ADMIN and SUPER_ADMIN roles can fund the wallet. This action cannot be reversed — contact support for adjustments.</p>
        </div>
    </div>
</div>
@endsection
