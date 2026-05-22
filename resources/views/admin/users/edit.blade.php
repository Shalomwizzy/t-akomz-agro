@extends('layouts.admin')

@section('page-title', 'Edit: ' . $user->name)

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-content-muted hover:text-brand-green transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div class="flex items-center gap-3">
            <img src="{{ $user->avatar_url }}" class="w-9 h-9 rounded-full object-cover">
            <h2 class="font-display font-semibold text-content-primary">{{ $user->name }}</h2>
        </div>
    </div>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-5">
        @csrf @method('PUT')

        <div class="card p-5 space-y-4">
            <h3 class="font-semibold text-content-primary">Account Details</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="input @error('name') border-red-500 @enderror" required>
                    @error('name')<p class="error-text">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="label">Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" class="input">
                </div>
            </div>

            <div>
                <label class="label">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input @error('email') border-red-500 @enderror" required>
                @error('email')<p class="error-text">{{ $message }}</p>@enderror
            </div>

            <div x-data="{ show: false }">
                <label class="label">New Password <span class="text-content-muted font-normal">(leave blank to keep current)</span></label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password"
                           class="input pr-10 @error('password') border-red-500 @enderror"
                           placeholder="••••••••">
                    <button type="button" @click="show = !show"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-content-muted hover:text-content-primary">
                        <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
                @error('password')<p class="error-text">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="card p-5 space-y-5" x-data="{ role: '{{ old('role', $currentRole) }}' }">
            <div>
                <h3 class="font-semibold text-content-primary">Role & Access</h3>
                <p class="text-xs text-content-muted mt-0.5">Choose what level of access this staff member has. Roles cannot be downgraded below your own level.</p>
            </div>

            {{-- Role cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($roles as $role)
                @php
                    $roleMap = [
                        'ADMIN'    => [
                            'icon'  => '🛡️',
                            'label' => 'Admin',
                            'desc'  => 'Full access to every section of the admin panel — products, orders, customers, blog, farm ERP, HR, finance, and communications.',
                            'color' => 'blue',
                        ],
                        'MANAGER'  => [
                            'icon'  => '📋',
                            'label' => 'Manager',
                            'desc'  => 'Always sees the Financial Control section. You can additionally grant access to specific modules (orders, products, HR, etc.) below.',
                            'color' => 'yellow',
                        ],
                        'SALES'    => [
                            'icon'  => '🛒',
                            'label' => 'Sales Staff',
                            'desc'  => 'No default access. You choose exactly which modules this person can see by ticking them below.',
                            'color' => 'green',
                        ],
                        'CUSTOMER' => [
                            'icon'  => '👤',
                            'label' => 'Customer',
                            'desc'  => 'Regular customer account. Can shop, track orders, and manage their profile — no admin panel access at all.',
                            'color' => 'gray',
                        ],
                    ];
                    $info = $roleMap[$role->name] ?? ['icon' => '👤', 'label' => $role->name, 'desc' => '', 'color' => 'gray'];
                @endphp
                <label class="relative flex items-start gap-3 p-3.5 rounded-xl border cursor-pointer transition-all
                              hover:border-brand-green/40 has-[:checked]:border-brand-green has-[:checked]:bg-brand-green/5"
                       style="border-color: rgba(255,255,255,0.08);">
                    <input type="radio" name="role" value="{{ $role->name }}" x-model="role"
                           {{ (old('role', $currentRole)) === $role->name ? 'checked' : '' }}
                           class="mt-1 accent-green-500 flex-shrink-0">
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5 text-sm font-semibold text-content-primary">
                            <span>{{ $info['icon'] }}</span>
                            {{ $info['label'] }}
                        </div>
                        <p class="text-xs text-content-muted mt-0.5 leading-relaxed">{{ $info['desc'] }}</p>
                    </div>
                </label>
                @endforeach
            </div>
            @error('role')<p class="error-text mt-1">{{ $message }}</p>@enderror

            {{-- Module access — only relevant for MANAGER / SALES --}}
            <div x-show="role === 'MANAGER' || role === 'SALES'" x-cloak>
                <div class="pt-4" style="border-top: 1px solid rgba(255,255,255,0.06);">
                    <div class="flex items-start justify-between gap-4 mb-3">
                        <div>
                            <p class="text-sm font-semibold text-content-primary">Module Access</p>
                            <p class="text-xs text-content-muted mt-0.5">
                                <span x-show="role === 'MANAGER'">Managers always have Finance. Tick any additional sections they should see.</span>
                                <span x-show="role === 'SALES'">Sales staff have no default access. Choose exactly what they can see.</span>
                            </p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($modulePerms as $permName => $permLabel)
                        <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all
                                      hover:border-brand-green/30 has-[:checked]:border-brand-green has-[:checked]:bg-brand-green/5"
                               style="border-color: rgba(255,255,255,0.08);">
                            <input type="checkbox" name="permissions[]" value="{{ $permName }}"
                                   {{ in_array($permName, $userPermissions) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded accent-green-500 flex-shrink-0">
                            <span class="text-sm text-content-primary">{{ $permLabel }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- What Admin gets — informational --}}
            <div x-show="role === 'ADMIN'" x-cloak
                 class="text-xs text-content-muted bg-blue-500/5 border border-blue-500/15 rounded-xl px-4 py-3 leading-relaxed">
                <strong class="text-blue-400">Admin access includes:</strong>
                Dashboard, Orders, Products, Categories, Inventory, Promotions, Reviews, Customers, Staff, Farm ERP, HR & Payroll, Content, Analytics, Financial Control, and Communications. Everything except System Settings (Super Admin only).
            </div>

            {{-- What Customer gets — informational --}}
            <div x-show="role === 'CUSTOMER'" x-cloak
                 class="text-xs text-content-muted bg-surface-elevated border border-border rounded-xl px-4 py-3 leading-relaxed">
                This person will <strong class="text-content-secondary">not</strong> be able to access the admin panel. They can only log into the customer-facing side of the website to shop and track orders.
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn-primary py-3 px-8">Save Changes</button>
            <a href="{{ route('admin.users.index') }}" class="btn-ghost py-3 px-6">Cancel</a>
            @if($user->id !== auth()->id())
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="ml-auto"
                  onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger py-3 px-6 text-sm">Delete User</button>
            </form>
            @endif
        </div>
    </form>
</div>
@endsection
