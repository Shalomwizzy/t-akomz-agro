@extends('layouts.app')

@section('title', 'My Addresses - T-Akomz Agro Estates')

@section('content')
<div class="bg-surface-card border-b border-surface-border">
    <div class="container-custom py-8">
        <h1 class="font-display text-3xl font-bold text-content-primary">Saved Addresses</h1>
    </div>
</div>

<div class="container-custom py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        @include('account.partials.sidebar')

        <div class="flex-1 min-w-0" x-data="{ addingNew: {{ $errors->any() ? 'true' : 'false' }} }">

            {{-- Add New Form --}}
            <div class="card p-5 mb-6">
                <button @click="addingNew = !addingNew"
                        class="flex items-center gap-2 text-sm font-semibold text-brand-green hover:underline w-full text-left">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add New Address
                </button>

                <form action="{{ route('account.addresses.store') }}" method="POST" x-show="addingNew" x-transition class="mt-5 space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="label">Label</label>
                            <input type="text" name="label" value="{{ old('label') }}" placeholder="Home, Work, etc."
                                   class="input @error('label') border-red-500 @enderror">
                            @error('label')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="label">Phone</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+234 800 000 0000"
                                   class="input @error('phone') border-red-500 @enderror">
                            @error('phone')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="label">Street Address</label>
                            <input type="text" name="street" value="{{ old('street') }}"
                                   class="input @error('street') border-red-500 @enderror">
                            @error('street')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="label">City / LGA</label>
                            <input type="text" name="city" value="{{ old('city') }}"
                                   class="input @error('city') border-red-500 @enderror">
                            @error('city')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="label">State</label>
                            <select name="state" class="input @error('state') border-red-500 @enderror">
                                <option value="">Select state...</option>
                                @foreach(['Abia','Adamawa','Akwa Ibom','Anambra','Bauchi','Bayelsa','Benue','Borno','Cross River','Delta','Ebonyi','Edo','Ekiti','Enugu','FCT (Abuja)','Gombe','Imo','Jigawa','Kaduna','Kano','Katsina','Kebbi','Kogi','Kwara','Lagos','Nasarawa','Niger','Ogun','Ondo','Osun','Oyo','Plateau','Rivers','Sokoto','Taraba','Yobe','Zamfara'] as $state)
                                <option value="{{ $state }}" {{ old('state') === $state ? 'selected' : '' }}>{{ $state }}</option>
                                @endforeach
                            </select>
                            @error('state')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_default" value="1" class="w-4 h-4 accent-green-500">
                        <span class="text-sm text-content-secondary">Set as default address</span>
                    </label>
                    <div class="flex gap-3">
                        <button type="submit" class="btn-primary py-2.5 px-6 text-sm">Save Address</button>
                        <button type="button" @click="addingNew = false" class="btn-ghost py-2.5 px-6 text-sm">Cancel</button>
                    </div>
                </form>
            </div>

            {{-- Saved Addresses --}}
            @if($addresses->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($addresses as $address)
                <div class="card p-5 relative {{ $address->is_default ? 'border-brand-green/40' : '' }}">
                    @if($address->is_default)
                    <span class="badge-green text-xs absolute top-3 right-3">Default</span>
                    @endif
                    <p class="font-semibold text-content-primary text-sm mb-1">{{ $address->label ?? 'Address' }}</p>
                    <p class="text-content-secondary text-sm">{{ $address->street }}</p>
                    <p class="text-content-secondary text-sm">{{ $address->city }}, {{ $address->state }}</p>
                    @if($address->phone)<p class="text-content-muted text-xs mt-1">{{ $address->phone }}</p>@endif
                    <div class="flex gap-3 mt-4 pt-3 border-t border-surface-border">
                        @if(!$address->is_default)
                        <form action="{{ route('account.addresses.default', $address) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs text-brand-green hover:underline">Set Default</button>
                        </form>
                        @endif
                        <form action="{{ route('account.addresses.destroy', $address) }}" method="POST"
                              onsubmit="return confirm('Delete this address?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:underline">Delete</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12 text-content-muted">
                <p class="text-4xl mb-3">📍</p>
                <p class="text-sm">No saved addresses yet. Add one above.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
