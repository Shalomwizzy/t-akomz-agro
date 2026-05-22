@extends('layouts.admin')
@section('page-title', 'Register Worker')

@section('content')
<div class="p-6 lg:p-8 max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('admin.workers.index') }}" class="text-content-muted hover:text-brand-green text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Workers
        </a>
        <h1 class="text-2xl font-display font-bold text-content-primary mt-3">Register New Worker</h1>
    </div>

    <form method="POST" action="{{ route('admin.workers.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Personal Info --}}
        <div class="bg-surface-card border border-border rounded-2xl p-6 space-y-5">
            <h2 class="font-semibold text-content-primary flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Personal Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Full Name <span class="text-red-400">*</span></label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" required
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                    @error('full_name')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">ID Number (NIN / Voter Card)</label>
                    <input type="text" name="id_number" value="{{ old('id_number') }}"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Home Address</label>
                    <input type="text" name="address" value="{{ old('address') }}"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Emergency Contact</label>
                    <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}"
                           placeholder="Name — Phone"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary placeholder-content-muted focus:outline-none focus:border-brand-green transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Photo <span class="text-content-muted font-normal">(optional)</span></label>
                    <input type="file" name="photo" accept="image/*"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2 text-sm text-content-secondary focus:outline-none focus:border-brand-green transition-colors file:mr-3 file:bg-brand-green file:text-surface-bg file:border-none file:rounded-lg file:px-3 file:py-1 file:text-xs file:font-semibold">
                </div>
            </div>
        </div>

        {{-- Employment Details --}}
        <div class="bg-surface-card border border-border rounded-2xl p-6 space-y-5">
            <h2 class="font-semibold text-content-primary flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Employment Details
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Job Role <span class="text-red-400">*</span></label>
                    <input type="text" name="role" value="{{ old('role', 'farmhand') }}" required
                           placeholder="e.g. Farmhand, Supervisor, Vet"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary placeholder-content-muted focus:outline-none focus:border-brand-green transition-colors">
                    @error('role')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Status <span class="text-red-400">*</span></label>
                    <select name="status" required class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Employment Type <span class="text-red-400">*</span></label>
                    <select name="employment_type" required class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                        <option value="monthly" {{ old('employment_type', 'monthly') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="weekly" {{ old('employment_type') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="daily" {{ old('employment_type') === 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="contract" {{ old('employment_type') === 'contract' ? 'selected' : '' }}>Contract</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Salary Type <span class="text-red-400">*</span></label>
                    <select name="salary_type" required class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                        <option value="fixed" {{ old('salary_type', 'fixed') === 'fixed' ? 'selected' : '' }}>Fixed (per period)</option>
                        <option value="daily_rate" {{ old('salary_type') === 'daily_rate' ? 'selected' : '' }}>Daily Rate (per day present)</option>
                        <option value="hourly" {{ old('salary_type') === 'hourly' ? 'selected' : '' }}>Hourly Rate</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Salary Amount (₦) <span class="text-red-400">*</span></label>
                    <input type="number" name="salary_amount" value="{{ old('salary_amount') }}" min="0" step="0.01" required
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                    @error('salary_amount')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Start Date <span class="text-red-400">*</span></label>
                    <input type="date" name="start_date" value="{{ old('start_date', now()->toDateString()) }}" required
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">End Date <span class="text-content-muted font-normal">(contract end, leave blank for permanent)</span></label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                </div>
            </div>
        </div>

        {{-- Bank Details --}}
        <div class="bg-surface-card border border-border rounded-2xl p-6 space-y-4">
            <h2 class="font-semibold text-content-primary flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Bank Details <span class="text-xs text-content-muted font-normal ml-1">(optional)</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Bank Name</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}"
                           placeholder="e.g. First Bank, GTB"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary placeholder-content-muted focus:outline-none focus:border-brand-green transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Account Number</label>
                    <input type="text" name="bank_account" value="{{ old('bank_account') }}"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                </div>
            </div>
        </div>

        {{-- Notes --}}
        <div class="bg-surface-card border border-border rounded-2xl p-6">
            <label class="block text-xs font-medium text-content-secondary mb-1.5">Notes <span class="text-content-muted font-normal">(optional)</span></label>
            <textarea name="notes" rows="3"
                      class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors resize-none">{{ old('notes') }}</textarea>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                    class="bg-brand-green text-surface-bg font-semibold px-6 py-3 rounded-xl hover:bg-brand-dark transition-colors text-sm">
                Register Worker
            </button>
            <a href="{{ route('admin.workers.index') }}" class="text-content-muted hover:text-content-secondary text-sm px-4 py-3">Cancel</a>
        </div>
    </form>
</div>
@endsection
