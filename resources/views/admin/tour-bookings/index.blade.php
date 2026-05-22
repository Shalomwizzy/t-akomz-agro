@extends('layouts.admin')
@section('page-title', 'Tour Bookings')
@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between gap-4 flex-wrap">
        <div>
            <h1 class="font-display text-2xl font-bold text-content-primary">Farm Tour Bookings</h1>
            <p class="text-content-muted text-sm mt-0.5">Manage, approve and reject farm tour reservations.</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('admin.tour-bookings.index') }}" class="text-xs px-3 py-2 rounded-lg border border-surface-border text-content-muted hover:text-content-primary transition-colors {{ !request('status') ? 'border-brand-green/40 text-brand-green bg-brand-green/5' : '' }}">All</a>
            @foreach(['pending'=>'⏳ Pending','approved'=>'✅ Approved','rejected'=>'❌ Rejected'] as $s=>$label)
            <a href="{{ route('admin.tour-bookings.index', ['status'=>$s]) }}"
               class="text-xs px-3 py-2 rounded-lg border transition-colors {{ request('status')===$s ? 'border-brand-green/40 bg-brand-green/10 text-brand-green' : 'border-surface-border text-content-muted hover:text-content-primary' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-xl text-sm font-medium" style="background:rgba(184,243,151,0.08);border:1px solid rgba(184,243,151,0.2);color:#B8F397;">{{ session('success') }}</div>
    @endif

    @if($bookings->count())
    <div class="space-y-4">
        @foreach($bookings as $booking)
        @php
            $badge = $booking->status_badge;
            $isPaid = $booking->payment_status === 'paid';
            $isPending = $booking->booking_status === 'pending';
            $pc = $booking->payment_status === 'paid' ? 'bg-green-500/15 text-green-400 border-green-500/20' : ($booking->payment_status === 'failed' ? 'bg-red-500/15 text-red-400 border-red-500/20' : 'bg-yellow-500/15 text-yellow-400 border-yellow-500/20');
        @endphp
        <div class="card p-0 overflow-hidden" x-data="{ showApprove: false, showReject: false }">
            <div class="p-5 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="font-mono text-brand-green font-bold text-sm">{{ $booking->reference }}</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full border {{ $pc }}">{{ ucfirst($booking->payment_status) }}</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full border {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                        </div>
                        <h3 class="font-semibold text-content-primary text-base">{{ $booking->name }}</h3>
                        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1 text-xs text-content-muted">
                            <span>📧 {{ $booking->email }}</span>
                            <span>📞 {{ $booking->phone }}</span>
                            <span>📦 {{ ucfirst($booking->package) }} · {{ $booking->persons }} person{{ $booking->persons > 1 ? 's' : '' }}</span>
                            <span>📅 Preferred: <strong class="text-content-secondary">{{ $booking->preferred_date?->format('d M Y') }}</strong></span>
                            @if($booking->confirmed_date)<span>✅ Confirmed: <strong class="text-brand-green">{{ $booking->confirmed_date->format('d M Y') }}</strong></span>@endif
                            @if($booking->alternative_date)<span>🔄 Alt Date Suggested: <strong class="text-yellow-400">{{ $booking->alternative_date->format('d M Y') }}</strong></span>@endif
                        </div>
                        <div class="flex items-center justify-between mt-3">
                            <span class="font-bold text-brand-green text-lg">{{ $booking->formatted_amount }}</span>
                            <span class="text-[11px] text-content-muted">Booked {{ $booking->created_at->diffForHumans() }}</span>
                        </div>
                        @if($booking->admin_note)
                        <div class="mt-3 text-xs text-content-muted px-3 py-2 rounded-lg" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                            <strong class="text-content-secondary">Admin note:</strong> {{ $booking->admin_note }}
                        </div>
                        @endif
                        @if($booking->notes)
                        <div class="mt-2 text-xs text-content-muted px-3 py-2 rounded-lg" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                            <strong class="text-content-secondary">Customer notes:</strong> {{ $booking->notes }}
                        </div>
                        @endif
                    </div>
                    <div class="flex flex-row sm:flex-col gap-2 flex-shrink-0">
                        @if($isPaid && $isPending)
                        <button @click="showApprove=!showApprove;showReject=false" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold" style="background:rgba(184,243,151,0.1);border:1px solid rgba(184,243,151,0.25);color:#B8F397;">✅ Approve</button>
                        <button @click="showReject=!showReject;showApprove=false" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);color:#f87171;">❌ Reject</button>
                        @endif
                        <a href="{{ route('admin.tour-bookings.edit', $booking) }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-content-muted hover:text-content-primary border border-surface-border hover:border-brand-green/30 transition-all">✏️ Edit</a>
                        <form action="{{ route('admin.tour-bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Delete this booking?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-red-400/60 hover:text-red-400 border border-transparent hover:border-red-500/20 transition-all">🗑 Delete</button>
                        </form>
                    </div>
                </div>
            </div>

            <div x-show="showApprove" x-cloak class="border-t px-5 sm:px-6 py-5" style="border-color:rgba(184,243,151,0.15);background:rgba(184,243,151,0.03);">
                <p class="text-sm font-semibold text-brand-green mb-3">✅ Approve Booking — an email will be sent to {{ $booking->email }}</p>
                <form action="{{ route('admin.tour-bookings.approve', $booking) }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="label text-xs">Confirmed Tour Date</label>
                            <input type="date" name="confirmed_date" value="{{ $booking->preferred_date?->format('Y-m-d') }}" class="input text-sm">
                            <p class="text-xs text-content-muted mt-1">Leave as preferred date or change it.</p>
                        </div>
                        <div>
                            <label class="label text-xs">Message to Guest <span class="font-normal text-content-muted">(optional)</span></label>
                            <textarea name="admin_note" rows="2" class="input resize-none text-sm" placeholder="e.g. Please arrive by 8am. Parking available at gate."></textarea>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="btn-primary text-xs px-5 py-2">Send Approval Email</button>
                        <button type="button" @click="showApprove=false" class="btn-outline text-xs px-4 py-2">Cancel</button>
                    </div>
                </form>
            </div>

            <div x-show="showReject" x-cloak class="border-t px-5 sm:px-6 py-5" style="border-color:rgba(239,68,68,0.15);background:rgba(239,68,68,0.03);">
                <p class="text-sm font-semibold text-red-400 mb-3">❌ Reject Booking — an email will be sent to {{ $booking->email }}</p>
                <form action="{{ route('admin.tour-bookings.reject', $booking) }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="label text-xs">Reason / Note <span class="text-red-400">*</span></label>
                            <textarea name="admin_note" rows="2" class="input resize-none text-sm" required placeholder="e.g. The selected date is fully booked. Please choose another date."></textarea>
                        </div>
                        <div>
                            <label class="label text-xs">Suggest Alternative Date <span class="font-normal text-content-muted">(optional)</span></label>
                            <input type="date" name="alternative_date" min="{{ now()->addDays(3)->format('Y-m-d') }}" class="input text-sm">
                            <p class="text-xs text-content-muted mt-1">Guest will see this suggested date in the rejection email.</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="text-xs px-5 py-2 rounded-xl font-semibold text-red-400" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);">Send Rejection Email</button>
                        <button type="button" @click="showReject=false" class="btn-outline text-xs px-4 py-2">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $bookings->links() }}</div>
    @else
    <div class="card p-16 text-center">
        <div class="text-5xl mb-4">🏕</div>
        <h3 class="font-display text-xl font-semibold text-content-primary mb-2">No bookings yet</h3>
        <p class="text-content-muted text-sm">Farm tour bookings will appear here once customers start booking.</p>
    </div>
    @endif
</div>
@endsection
