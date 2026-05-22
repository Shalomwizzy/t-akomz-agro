@extends('layouts.admin')

@section('page-title', 'Promotions & Coupons')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    {{-- Coupon List --}}
    <div>
        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-surface-border">
                <h2 class="font-semibold text-content-primary">Active Coupons</h2>
            </div>
            <div class="divide-y divide-surface-border">
                @forelse($coupons as $coupon)
                <div class="px-5 py-4">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <span class="font-mono font-bold text-brand-green text-sm">{{ $coupon->code }}</span>
                            @if(!$coupon->is_active)
                            <span class="badge bg-red-500/15 text-red-400 text-xs ml-2">Inactive</span>
                            @endif
                        </div>
                        <div class="flex gap-3">
                            <form action="{{ route('admin.promotions.update', $coupon) }}" method="POST">
                                @csrf @method('PUT')
                                <button type="submit" class="text-xs {{ $coupon->is_active ? 'text-yellow-400' : 'text-brand-green' }} hover:underline">
                                    {{ $coupon->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.promotions.destroy', $coupon) }}" method="POST"
                                  onsubmit="return confirm('Delete coupon {{ $coupon->code }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:underline">Delete</button>
                            </form>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs text-content-muted">
                        <span>Type: <strong class="text-content-secondary">{{ $coupon->type }}</strong></span>
                        <span>Value: <strong class="text-brand-green">
                            @if($coupon->type === 'PERCENTAGE'){{ $coupon->value }}%
                            @elseif($coupon->type === 'FREE_DELIVERY')Free Delivery
                            @else₦{{ number_format($coupon->value, 0) }}
                            @endif
                        </strong></span>
                        @if($coupon->min_order_amount)
                        <span>Min Order: ₦{{ number_format($coupon->min_order_amount, 0) }}</span>
                        @endif
                        @if($coupon->max_uses)
                        <span>Uses: {{ $coupon->used_count }}/{{ $coupon->max_uses }}</span>
                        @endif
                        @if($coupon->expires_at)
                        <span class="col-span-2">Expires: {{ $coupon->expires_at->format('d M Y') }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-content-muted text-sm">No coupons yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Create Coupon --}}
    <div>
        <div class="card p-5">
            <h2 class="font-semibold text-content-primary mb-5">Create Coupon</h2>
            <form action="{{ route('admin.promotions.store') }}" method="POST" class="space-y-4"
                  x-data="{ type: 'PERCENTAGE' }">
                @csrf
                <div>
                    <label class="label">Coupon Code <span class="text-red-400">*</span></label>
                    <div class="flex gap-2">
                        <input type="text" name="code" value="{{ old('code') }}"
                               class="input uppercase font-mono @error('code') border-red-500 @enderror flex-1"
                               placeholder="SUMMER20" required
                               style="text-transform: uppercase">
                        <button type="button" class="btn-ghost py-2 px-3 text-xs"
                                onclick="this.previousElementSibling.value = Math.random().toString(36).substr(2,8).toUpperCase()">
                            Random
                        </button>
                    </div>
                    @error('code')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label">Discount Type</label>
                    <select name="type" x-model="type" class="input">
                        <option value="PERCENTAGE">Percentage (%)</option>
                        <option value="FIXED">Fixed Amount (₦)</option>
                        <option value="FREE_DELIVERY">Free Delivery</option>
                    </select>
                </div>

                <div x-show="type !== 'FREE_DELIVERY'">
                    <label class="label">Value</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-content-muted text-sm" x-text="type === 'PERCENTAGE' ? '%' : '₦'"></span>
                        <input type="number" step="0.01" name="value" value="{{ old('value') }}"
                               class="input pl-8 @error('value') border-red-500 @enderror">
                    </div>
                    @error('value')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="label">Min Order (₦)</label>
                        <input type="number" name="min_order_value" value="{{ old('min_order_value') }}" class="input text-sm">
                    </div>
                    <div>
                        <label class="label">Max Uses</label>
                        <input type="number" name="max_uses" value="{{ old('max_uses') }}" placeholder="Unlimited" class="input text-sm">
                    </div>
                    <div>
                        <label class="label">Valid From</label>
                        <input type="date" name="valid_from" value="{{ old('valid_from', now()->format('Y-m-d')) }}" class="input text-sm">
                    </div>
                    <div>
                        <label class="label">Valid Until</label>
                        <input type="date" name="valid_until" value="{{ old('valid_until', now()->addYear()->format('Y-m-d')) }}" class="input text-sm">
                    </div>
                </div>

                <label class="flex items-center justify-between cursor-pointer">
                    <span class="text-sm text-content-secondary">Active</span>
                    <input type="checkbox" name="is_active" value="1" class="w-4 h-4 accent-green-500" checked>
                </label>

                <button type="submit" class="btn-primary w-full py-3 text-sm">Create Coupon</button>
            </form>
        </div>
    </div>
</div>
@endsection
