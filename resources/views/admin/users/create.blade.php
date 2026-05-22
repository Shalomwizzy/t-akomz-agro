@extends('layouts.admin')

@section('page-title', 'Add User')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-content-muted hover:text-brand-green transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h2 class="font-display font-semibold text-content-primary">Add New User / Staff</h2>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
        @csrf

        <div class="card p-5 space-y-4">
            <h3 class="font-semibold text-content-primary">Account Details</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">Full Name <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="input @error('name') border-red-500 @enderror" required>
                    @error('name')<p class="error-text">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="label">Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" class="input" placeholder="+234...">
                </div>
            </div>

            <div>
                <label class="label">Email Address <span class="text-red-400">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" class="input @error('email') border-red-500 @enderror" required>
                @error('email')<p class="error-text">{{ $message }}</p>@enderror
            </div>

            <div x-data="{ show: false }">
                <label class="label">Password <span class="text-red-400">*</span></label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password"
                           class="input pr-10 @error('password') border-red-500 @enderror" required>
                    <button type="button" @click="show = !show"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-content-muted hover:text-content-primary">
                        <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
                @error('password')<p class="error-text">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="card p-5 space-y-4">
            <h3 class="font-semibold text-content-primary">Role & Access</h3>
            <p class="text-xs text-content-muted">Choose the role that determines what this user can access in the admin panel.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($roles as $role)
                @php
                    $roleInfo = [
                        'SUPER_ADMIN' => ['icon' => '👑', 'desc' => 'Full system access including settings and user management'],
                        'ADMIN'       => ['icon' => '🛡️', 'desc' => 'Full admin panel access'],
                        'MANAGER'     => ['icon' => '📋', 'desc' => 'Orders, inventory, customers, and reports'],
                        'SALES'       => ['icon' => '🛒', 'desc' => 'Products, orders, and customer support'],
                        'CUSTOMER'    => ['icon' => '👤', 'desc' => 'Customer-facing account only (no admin access)'],
                    ][$role->name] ?? ['icon' => '👤', 'desc' => ''];
                @endphp
                <label class="relative flex items-start gap-3 p-3.5 rounded-xl border cursor-pointer transition-all
                              hover:border-brand-green/40 has-[:checked]:border-brand-green has-[:checked]:bg-brand-green/5">
                    <input type="radio" name="role" value="{{ $role->name }}"
                           {{ old('role') === $role->name ? 'checked' : '' }}
                           class="mt-0.5 accent-green-500">
                    <div>
                        <div class="flex items-center gap-1.5 text-sm font-semibold text-content-primary">
                            <span>{{ $roleInfo['icon'] }}</span> {{ $role->name }}
                        </div>
                        <p class="text-xs text-content-muted mt-0.5">{{ $roleInfo['desc'] }}</p>
                    </div>
                </label>
                @endforeach
            </div>
            @error('role')<p class="error-text">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn-primary py-3 px-8">Create User</button>
            <a href="{{ route('admin.users.index') }}" class="btn-ghost py-3 px-6">Cancel</a>
        </div>
    </form>
</div>
@endsection
