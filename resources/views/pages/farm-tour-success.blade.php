@extends('layouts.app')
@section('title', 'Booking Confirmed — T-Akomz Farm Tour')
@section('content')
<div class="container-custom py-20 max-w-lg mx-auto text-center">
    <div class="card p-10">
        <div class="w-20 h-20 rounded-full bg-brand-green/15 border border-brand-green/30 flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h1 class="font-display text-2xl font-bold text-content-primary mb-2">Booking Confirmed!</h1>
        <p class="text-content-muted text-sm mb-6">Your farm tour has been booked and payment received. We will contact you within 24 hours to confirm details.</p>

        <div class="rounded-xl p-5 text-left space-y-3 mb-8"
             style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07);">
            <div class="flex justify-between text-sm">
                <span class="text-content-muted">Reference</span>
                <span class="font-mono text-brand-green font-semibold">{{ $booking->reference }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-content-muted">Name</span>
                <span class="text-content-primary">{{ $booking->name }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-content-muted">Package</span>
                <span class="text-content-primary capitalize">{{ $booking->package }} ({{ $booking->persons }} person{{ $booking->persons > 1 ? 's' : '' }})</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-content-muted">Preferred Date</span>
                <span class="text-content-primary">{{ $booking->preferred_date?->format('d M Y') }}</span>
            </div>
            <div class="flex justify-between text-sm border-t border-surface-border pt-3">
                <span class="font-semibold text-content-primary">Amount Paid</span>
                <span class="font-bold text-brand-green text-base">{{ $booking->formatted_amount }}</span>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('home') }}" class="btn-outline flex-1 py-3 text-sm">Back to Home</a>
            <a href="{{ route('shop.index') }}" class="btn-primary flex-1 py-3 text-sm">Shop Our Products</a>
        </div>
    </div>
</div>
@endsection
