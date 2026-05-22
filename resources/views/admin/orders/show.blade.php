@extends('layouts.admin')

@section('page-title', 'Order ' . $order->order_number)

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.orders.index') }}" class="text-content-muted hover:text-brand-green transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h2 class="font-mono font-bold text-brand-green text-lg">{{ $order->order_number }}</h2>
        <span class="status-{{ $order->status }}">{{ $order->status_label }}</span>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.orders.invoice', $order) }}"
           class="btn-ghost py-2 px-4 text-xs flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Invoice PDF
        </a>
        <span class="text-sm text-content-muted">{{ $order->created_at->format('d M Y, g:i A') }}</span>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Left Column --}}
    <div class="xl:col-span-2 space-y-5">

        {{-- Items --}}
        <div class="card p-5">
            <h3 class="font-semibold text-content-primary mb-4">Items ({{ $order->items->count() }})</h3>
            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex items-center gap-4 text-sm">
                    <div class="w-12 h-12 bg-surface-elevated rounded-xl flex items-center justify-center text-xs text-content-muted font-bold flex-shrink-0">x{{ $item->quantity }}</div>
                    <div class="flex-1">
                        <p class="text-content-primary font-medium">{{ $item->product_name }}</p>
                        <p class="text-content-muted text-xs">{{ $item->unit }} · ₦{{ number_format($item->unit_price, 2) }} each</p>
                    </div>
                    <span class="font-semibold text-content-primary">₦{{ number_format($item->line_total, 2) }}</span>
                </div>
                @endforeach
            </div>
            <div class="border-t border-surface-border mt-5 pt-4 space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-content-muted">Subtotal</span><span>₦{{ number_format($order->subtotal, 2) }}</span></div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between text-brand-green"><span>Discount</span><span>-₦{{ number_format($order->discount_amount, 2) }}</span></div>
                @endif
                <div class="flex justify-between"><span class="text-content-muted">Delivery Fee</span><span>₦{{ number_format($order->delivery_fee, 2) }}</span></div>
                <div class="flex justify-between font-bold text-base border-t border-surface-border pt-2">
                    <span class="text-content-primary">Total</span>
                    <span class="text-brand-green">{{ $order->formatted_total }}</span>
                </div>
            </div>
        </div>

        {{-- Timeline --}}
        <div class="card p-5">
            <h3 class="font-semibold text-content-primary mb-4">Order Timeline</h3>
            @if($order->timeline->count())
            <div class="space-y-3 mb-5">
                @foreach($order->timeline->sortByDesc('created_at') as $timeline)
                <div class="flex gap-3 text-sm">
                    <div class="w-2.5 h-2.5 rounded-full bg-brand-green mt-1.5 flex-shrink-0"></div>
                    <div>
                        <span class="font-medium text-content-primary capitalize">{{ str_replace('_', ' ', $timeline->status) }}</span>
                        @if($timeline->note)<span class="text-content-muted ml-2">— {{ $timeline->note }}</span>@endif
                        <p class="text-xs text-content-muted mt-0.5">{{ $timeline->created_at->format('d M Y, g:i A') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Update Status --}}
            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="flex gap-3 flex-wrap border-t border-surface-border pt-5">
                @csrf
                <select name="status" class="input py-2 text-sm flex-1 min-w-40">
                    @foreach(['PENDING','CONFIRMED','PROCESSING','PACKED','DISPATCHED','OUT_FOR_DELIVERY','SHIPPED','DELIVERED','CANCELLED','REFUNDED'] as $s)
                    <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ str_replace('_', ' ', $s) }}</option>
                    @endforeach
                </select>
                <select name="payment_status" class="input py-2 text-sm w-auto">
                    @foreach(['PENDING','PAID','FAILED','REFUNDED'] as $p)
                    <option value="{{ $p }}" {{ $order->payment_status === $p ? 'selected' : '' }}>Payment: {{ $p }}</option>
                    @endforeach
                </select>
                <input type="text" name="note" placeholder="Status note (optional)" class="input py-2 text-sm flex-1 min-w-40">
                <button type="submit" class="btn-primary py-2 px-5 text-sm">Update</button>
            </form>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="space-y-5">
        {{-- Customer --}}
        <div class="card p-5">
            <h3 class="font-semibold text-content-primary mb-3">Customer</h3>
            @if($order->user)
            <div class="flex items-center gap-3 mb-3">
                <img src="{{ $order->user->avatar_url }}" alt="{{ $order->user->name }}" class="w-10 h-10 rounded-full object-cover">
                <div>
                    <p class="text-sm font-medium text-content-primary">{{ $order->user->name }}</p>
                    <a href="{{ route('admin.customers.show', $order->user) }}" class="text-xs text-brand-green hover:underline">View profile</a>
                </div>
            </div>
            @endif
            <div class="space-y-1.5 text-sm">
                <div class="flex gap-3"><span class="text-content-muted w-16">Name</span><span class="text-content-secondary">{{ $order->customer_name }}</span></div>
                <div class="flex gap-3"><span class="text-content-muted w-16">Email</span><a href="mailto:{{ $order->customer_email }}" class="text-brand-green text-xs hover:underline">{{ $order->customer_email }}</a></div>
                <div class="flex gap-3"><span class="text-content-muted w-16">Phone</span><span class="text-content-secondary">{{ $order->customer_phone }}</span></div>
            </div>
        </div>

        {{-- Delivery --}}
        <div class="card p-5">
            <h3 class="font-semibold text-content-primary mb-3">Delivery</h3>
            <div class="space-y-1.5 text-sm">
                <div class="flex gap-3"><span class="text-content-muted w-16">Type</span><span class="text-content-secondary capitalize">{{ str_replace('_', ' ', $order->delivery_type) }}</span></div>
                @if($order->delivery_address)
                <div class="flex gap-3"><span class="text-content-muted w-16">Address</span><span class="text-content-secondary">{{ $order->delivery_address }}</span></div>
                @endif
                @if($order->notes)
                <div class="flex gap-3"><span class="text-content-muted w-16">Notes</span><span class="text-content-secondary text-xs">{{ $order->notes }}</span></div>
                @endif
            </div>
        </div>

        {{-- Payment --}}
        <div class="card p-5">
            <h3 class="font-semibold text-content-primary mb-3">Payment</h3>
            <div class="space-y-1.5 text-sm">
                <div class="flex gap-3"><span class="text-content-muted w-20">Method</span><span class="text-content-secondary capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span></div>
                <div class="flex gap-3"><span class="text-content-muted w-20">Status</span>
                    <span class="{{ $order->payment_status === 'PAID' ? 'text-brand-green' : 'text-yellow-400' }} font-medium">{{ $order->payment_status }}</span>
                </div>
                @if($order->transaction_ref)
                <div class="flex gap-3"><span class="text-content-muted w-20">Ref</span><span class="text-content-muted font-mono text-xs">{{ $order->transaction_ref }}</span></div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
