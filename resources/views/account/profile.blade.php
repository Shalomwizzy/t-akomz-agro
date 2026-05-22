@extends('layouts.app')

@section('title', 'Profile Settings - T-Akomz Agro Estates')

@section('content')
<div class="bg-surface-card border-b border-surface-border">
    <div class="container-custom py-8">
        <h1 class="font-display text-3xl font-bold text-content-primary">Profile Settings</h1>
    </div>
</div>

<div class="container-custom py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        @include('account.partials.sidebar')

        <div class="flex-1 min-w-0 space-y-6 max-w-xl">

            {{-- Personal Info --}}
            <div class="card p-6">
                <h2 class="font-semibold text-content-primary mb-5">Personal Information</h2>
                <form action="{{ route('account.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PATCH')

                    {{-- Avatar --}}
                    <div class="flex items-center gap-4 mb-6">
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                             class="w-16 h-16 rounded-full object-cover">
                        <div>
                            <label class="btn-outline text-xs py-2 px-4 cursor-pointer">
                                Change Photo
                                <input type="file" name="avatar" accept="image/*" class="sr-only"
                                       onchange="this.labels[0].textContent = this.files[0]?.name || 'Change Photo'">
                            </label>
                            <p class="text-xs text-content-muted mt-1">JPG, PNG up to 2MB</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="label">First Name</label>
                                <input type="text" name="first_name" value="{{ old('first_name', explode(' ', auth()->user()->name)[0]) }}"
                                       class="input @error('first_name') border-red-500 @enderror">
                                @error('first_name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="label">Last Name</label>
                                <input type="text" name="last_name" value="{{ old('last_name', explode(' ', auth()->user()->name)[1] ?? '') }}"
                                       class="input @error('last_name') border-red-500 @enderror">
                                @error('last_name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div>
                            <label class="label">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                   class="input @error('email') border-red-500 @enderror">
                            @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="label">Phone Number</label>
                            <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                                   placeholder="+234 800 000 0000"
                                   class="input @error('phone') border-red-500 @enderror">
                            @error('phone')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <button type="submit" class="btn-primary py-2.5 px-6 text-sm mt-5">Save Changes</button>
                </form>
            </div>

            {{-- Change Password --}}
            <div class="card p-6">
                <h2 class="font-semibold text-content-primary mb-5">Change Password</h2>
                <form action="{{ route('account.password.update') }}" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="label">Current Password</label>
                        <input type="password" name="current_password"
                               class="input @error('current_password') border-red-500 @enderror">
                        @error('current_password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label">New Password</label>
                        <input type="password" name="password"
                               class="input @error('password') border-red-500 @enderror">
                        @error('password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="input">
                    </div>
                    <button type="submit" class="btn-outline py-2.5 px-6 text-sm">Update Password</button>
                </form>
            </div>

            {{-- Danger Zone --}}
            <div class="card p-6 border-red-500/20">
                <h2 class="font-semibold text-red-400 mb-2">Delete Account</h2>
                <p class="text-content-muted text-sm mb-4">Once deleted, all your data will be permanently removed. This cannot be undone.</p>
                <button onclick="document.getElementById('delete-modal').classList.remove('hidden')"
                        class="btn-danger text-sm py-2.5 px-5">
                    Delete My Account
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="delete-modal" class="hidden fixed inset-0 bg-surface-bg/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="card p-6 max-w-sm w-full">
        <h3 class="font-display font-bold text-content-primary mb-2">Delete Account?</h3>
        <p class="text-content-muted text-sm mb-5">This will permanently delete your account, orders, and all associated data.</p>
        <form action="{{ route('account.destroy') }}" method="POST" class="space-y-3">
            @csrf @method('DELETE')
            <input type="password" name="password" placeholder="Enter your password to confirm"
                   class="input text-sm" required>
            <div class="flex gap-3">
                <button type="submit" class="btn-danger flex-1 py-2.5 text-sm">Yes, Delete</button>
                <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')"
                        class="btn-ghost flex-1 py-2.5 text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
