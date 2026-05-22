@extends('layouts.admin')
@section('page-title', 'Edit Worker')

@section('content')
<div class="p-6 lg:p-8 max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('admin.workers.show', $worker) }}" class="text-content-muted hover:text-brand-green text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to {{ $worker->full_name }}
        </a>
        <h1 class="text-2xl font-display font-bold text-content-primary mt-3">Edit Worker</h1>
    </div>

    <form method="POST" action="{{ route('admin.workers.update', $worker) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        <div class="bg-surface-card border border-border rounded-2xl p-6 space-y-4">
            <h2 class="font-semibold text-content-primary">Personal Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Full Name <span class="text-red-400">*</span></label>
                    <input type="text" name="full_name" value="{{ old('full_name', $worker->full_name) }}" required
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $worker->phone) }}"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">ID Number</label>
                    <input type="text" name="id_number" value="{{ old('id_number', $worker->id_number) }}"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Address</label>
                    <input type="text" name="address" value="{{ old('address', $worker->address) }}"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Emergency Contact</label>
                    <input type="text" name="emergency_contact" value="{{ old('emergency_contact', $worker->emergency_contact) }}"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Photo</label>
                    @if($worker->photo)
                    <div class="flex items-center gap-3 mb-2">
                        <img src="{{ Storage::url($worker->photo) }}" alt="" class="w-12 h-12 rounded-xl object-cover">
                        <span class="text-xs text-content-muted">Upload new to replace</span>
                    </div>
                    @endif
                    <input type="file" name="photo" accept="image/*"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2 text-sm text-content-secondary focus:outline-none focus:border-brand-green transition-colors file:mr-3 file:bg-brand-green file:text-surface-bg file:border-none file:rounded-lg file:px-3 file:py-1 file:text-xs file:font-semibold">
                </div>
            </div>
        </div>

        <div class="bg-surface-card border border-border rounded-2xl p-6 space-y-4">
            <h2 class="font-semibold text-content-primary">Employment Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Role <span class="text-red-400">*</span></label>
                    <input type="text" name="role" value="{{ old('role', $worker->role) }}" required
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Status</label>
                    <select name="status" class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                        @foreach(['active','inactive','suspended'] as $s)
                        <option value="{{ $s }}" {{ old('status', $worker->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Employment Type</label>
                    <select name="employment_type" class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                        @foreach(['monthly','weekly','daily','contract'] as $t)
                        <option value="{{ $t }}" {{ old('employment_type', $worker->employment_type) === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Salary Type</label>
                    <select name="salary_type" class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                        <option value="fixed" {{ old('salary_type', $worker->salary_type) === 'fixed' ? 'selected' : '' }}>Fixed (per period)</option>
                        <option value="daily_rate" {{ old('salary_type', $worker->salary_type) === 'daily_rate' ? 'selected' : '' }}>Daily Rate</option>
                        <option value="hourly" {{ old('salary_type', $worker->salary_type) === 'hourly' ? 'selected' : '' }}>Hourly Rate</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Salary Amount (₦)</label>
                    <input type="number" name="salary_amount" value="{{ old('salary_amount', $worker->salary_amount) }}" min="0" step="0.01"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $worker->start_date->toDateString()) }}"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $worker->end_date?->toDateString()) }}"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                </div>
            </div>
        </div>

        <div class="bg-surface-card border border-border rounded-2xl p-6 space-y-4">
            <h2 class="font-semibold text-content-primary">Bank Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Bank Name</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name', $worker->bank_name) }}"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Account Number</label>
                    <input type="text" name="bank_account" value="{{ old('bank_account', $worker->bank_account) }}"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors">
                </div>
            </div>
        </div>

        <div class="bg-surface-card border border-border rounded-2xl p-6">
            <label class="block text-xs font-medium text-content-secondary mb-1.5">Notes</label>
            <textarea name="notes" rows="3"
                      class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green transition-colors resize-none">{{ old('notes', $worker->notes) }}</textarea>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-brand-green text-surface-bg font-semibold px-6 py-3 rounded-xl hover:bg-brand-dark transition-colors text-sm">Save Changes</button>
            <a href="{{ route('admin.workers.show', $worker) }}" class="text-content-muted hover:text-content-secondary text-sm px-4 py-3">Cancel</a>
            <form method="POST" action="{{ route('admin.workers.destroy', $worker) }}" class="ml-auto"
                  onsubmit="return confirm('Delete {{ $worker->full_name }}? This cannot be undone.')">
                @csrf @method('DELETE')
                <button type="submit" class="text-red-400 hover:text-red-300 text-sm px-4 py-3">Delete Worker</button>
            </form>
        </div>
    </form>
</div>
@endsection
