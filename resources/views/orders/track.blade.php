@extends('layouts.app')

@section('title', 'Track Your Order - T-Akomz Agro Estates')

@section('content')
<div class="bg-surface-card border-b border-surface-border">
    <div class="container-custom py-8">
        <h1 class="font-display text-3xl font-bold text-content-primary">Track Order</h1>
        <p class="text-content-muted mt-1">Enter your order number and email or phone to check your delivery status.</p>
    </div>
</div>

<div class="container-custom py-12">
    <div class="max-w-xl mx-auto">

        {{-- Search Form --}}
        <div class="card p-6 mb-8">
            <form action="{{ route('orders.track') }}" method="GET" class="space-y-4">
                <div>
                    <label class="label">Order Number</label>
                    <input type="text" name="order_number"
                           value="{{ request('order_number') }}"
                           placeholder="TAK-2024-00001"
                           class="input font-mono text-sm @error('order_number') border-red-500 @enderror"
                           required>
                    @error('order_number')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="label">Email Address or Phone Number</label>
                    <input type="text" name="email"
                           value="{{ request('email') }}"
                           placeholder="your@email.com or 08012345678"
                           class="input text-sm @error('email') border-red-500 @enderror"
                           required>
                    @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn-primary w-full py-3">Track Order</button>
            </form>
        </div>

        {{-- Order Found --}}
        @if($order)
        <div class="space-y-5">

            {{-- Status Header --}}
            <div class="card p-5">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <p class="text-xs text-content-muted mb-1">Order Number</p>
                        <p class="font-mono font-bold text-brand-green text-lg">{{ $order->order_number }}</p>
                        <p class="text-xs text-content-muted mt-1">Placed {{ $order->created_at->format('d M Y') }}</p>
                    </div>
                    @php
                        $statusColors = [
                            'PENDING'    => 'bg-yellow-500/15 text-yellow-400 border border-yellow-500/30',
                            'CONFIRMED'  => 'bg-blue-500/15 text-blue-400 border border-blue-500/30',
                            'PROCESSING' => 'bg-purple-500/15 text-purple-400 border border-purple-500/30',
                            'SHIPPED'    => 'bg-brand-green/15 text-brand-green border border-brand-green/30',
                            'DELIVERED'  => 'bg-brand-green/20 text-brand-green border border-brand-green/40',
                            'CANCELLED'  => 'bg-red-500/15 text-red-400 border border-red-500/30',
                        ];
                        $statusClass = $statusColors[$order->status] ?? 'bg-surface-elevated text-content-muted';
                    @endphp
                    <span class="text-xs font-semibold px-3 py-1.5 rounded-full {{ $statusClass }}">
                        {{ $order->status_label }}
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm border-t border-surface-border pt-4">
                    <div>
                        <p class="text-content-muted text-xs mb-0.5">Total</p>
                        <p class="text-content-primary font-semibold">{{ $order->formatted_total }}</p>
                    </div>
                    <div>
                        <p class="text-content-muted text-xs mb-0.5">Payment</p>
                        <p class="text-content-primary capitalize">{{ str_replace('_', ' ', $order->payment_status) }}</p>
                    </div>
                    <div>
                        <p class="text-content-muted text-xs mb-0.5">Delivery Type</p>
                        <p class="text-content-primary capitalize">{{ ucfirst(strtolower(str_replace('_', ' ', $order->delivery_type))) }}</p>
                    </div>
                    <div>
                        <p class="text-content-muted text-xs mb-0.5">Payment Method</p>
                        <p class="text-content-primary capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</p>
                    </div>
                </div>
            </div>

            {{-- Progress Steps --}}
            @php
                $steps = [
                    'PENDING'    => ['Pending',    '📋', 0],
                    'CONFIRMED'  => ['Confirmed',  '✅', 1],
                    'PROCESSING' => ['Processing', '📦', 2],
                    'SHIPPED'    => ['Shipped',    '🚚', 3],
                    'DELIVERED'  => ['Delivered',  '🏠', 4],
                ];
                $statuses   = array_keys($steps);
                $currentIdx = array_search($order->status, $statuses);
                $currentIdx = $currentIdx !== false ? $currentIdx : 0;
                $barWidth   = count($steps) > 1 ? min(100, ($currentIdx / (count($steps) - 1)) * 100) : 0;
            @endphp

            @if($order->status !== 'CANCELLED')
            <div class="card p-5">
                <h3 class="font-semibold text-content-primary text-sm mb-6">Delivery Progress</h3>
                <div class="flex items-start justify-between relative px-2">
                    {{-- Background line --}}
                    <div class="absolute top-5 left-7 right-7 h-0.5 bg-surface-border"></div>
                    {{-- Progress line --}}
                    <div class="absolute top-5 left-7 h-0.5 bg-brand-green transition-all duration-500"
                         style="width: calc({{ $barWidth }}% * (100% - 3.5rem) / 100)"></div>

                    @foreach($steps as $key => [$label, $icon, $idx])
                    <div class="flex flex-col items-center z-10 flex-1 {{ !$loop->first && !$loop->last ? 'px-1' : '' }}">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-base transition-all
                            {{ $idx <= $currentIdx ? 'bg-brand-green/20 ring-2 ring-brand-green' : 'bg-surface-elevated ring-1 ring-surface-border' }}">
                            {{ $icon }}
                        </div>
                        <span class="text-xs mt-2 text-center leading-tight
                            {{ $idx <= $currentIdx ? 'text-brand-green font-medium' : 'text-content-muted' }}">
                            {{ $label }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="card p-5 border border-red-500/20 bg-red-500/5">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">❌</span>
                    <div>
                        <p class="font-semibold text-red-400">Order Cancelled</p>
                        <p class="text-xs text-content-muted mt-0.5">This order has been cancelled. Contact us if you have questions.</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Timeline --}}
            @if($order->timeline && $order->timeline->count())
            <div class="card p-5">
                <h3 class="font-semibold text-content-primary text-sm mb-5">Order Timeline</h3>
                <div class="space-y-0">
                    @foreach($order->timeline as $event)
                    <div class="flex gap-4 {{ !$loop->last ? 'pb-4' : '' }}">
                        <div class="flex flex-col items-center flex-shrink-0">
                            <div class="w-3 h-3 rounded-full bg-brand-green ring-2 ring-brand-green/30 mt-0.5"></div>
                            @if(!$loop->last)
                            <div class="w-px flex-1 bg-surface-border mt-1 mb-0 min-h-[1.5rem]"></div>
                            @endif
                        </div>
                        <div class="{{ !$loop->last ? 'pb-1' : '' }}">
                            <p class="text-sm text-content-primary font-semibold">
                                {{ ucwords(strtolower(str_replace('_', ' ', $event->status))) }}
                            </p>
                            @if($event->note)
                            <p class="text-xs text-content-secondary mt-0.5">{{ $event->note }}</p>
                            @endif
                            <p class="text-xs text-content-muted mt-1">{{ $event->created_at->format('d M Y, g:i A') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Items --}}
            <div class="card p-5">
                <h3 class="font-semibold text-content-primary text-sm mb-4">
                    Order Items <span class="text-content-muted font-normal">({{ $order->items->count() }})</span>
                </h3>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-3 text-sm">
                        <span class="w-8 h-8 bg-surface-elevated rounded-lg flex items-center justify-center text-xs text-content-muted font-bold flex-shrink-0">
                            ×{{ $item->quantity }}
                        </span>
                        <span class="flex-1 text-content-secondary">{{ $item->product_name }}</span>
                        <span class="text-content-primary font-medium">₦{{ number_format($item->total_price, 2) }}</span>
                    </div>
                    @endforeach
                    <div class="border-t border-surface-border pt-3 flex justify-between text-sm font-bold">
                        <span class="text-content-primary">Total</span>
                        <span class="text-brand-green">{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>

            {{-- Delivery Address --}}
            @if($order->delivery_type !== 'PICKUP' && $order->delivery_address)
            <div class="card p-5">
                <h3 class="font-semibold text-content-primary text-sm mb-3">Delivery Address</h3>
                <p class="text-sm text-content-secondary">{{ $order->delivery_address }}</p>
                <p class="text-sm text-content-secondary">{{ $order->delivery_city }}, {{ $order->delivery_state }}</p>
                @if($order->delivery_notes)
                <p class="text-xs text-content-muted mt-2 italic">Note: {{ $order->delivery_notes }}</p>
                @endif
            </div>
            @endif

            {{-- Help --}}
            <div class="card p-4 flex items-center justify-between gap-4">
                <p class="text-sm text-content-muted">Need help with this order?</p>
                @php $wa = \App\Models\SiteSetting::get('whatsapp_number', ''); @endphp
                @if($wa)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $wa) }}?text={{ urlencode('Hi! I need help with order ' . $order->order_number) }}"
                   target="_blank" rel="noopener noreferrer" 
                   class="btn-primary py-2 px-4 text-xs flex items-center gap-2 flex-shrink-0">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp Us
                </a>
                @else
                <a href="{{ route('contact') }}" class="btn-outline py-2 px-4 text-xs flex-shrink-0">Contact Us</a>
                @endif
            </div>
        </div>

        {{-- Not Found --}}
        @elseif($searched)
        <div class="card p-8 text-center">
            <div class="text-5xl mb-4">🔍</div>
            <h3 class="font-display text-xl font-semibold text-content-primary mb-2">Order Not Found</h3>
            <p class="text-content-muted text-sm">No order matches that number and email/phone. Double-check and try again.</p>
            <p class="text-content-muted text-xs mt-3">Order numbers look like: <span class="font-mono text-brand-green">TAK-2024-00001</span></p>
        </div>
        @endif

    </div>
</div>
@endsection
