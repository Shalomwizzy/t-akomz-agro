@extends('layouts.admin')
@section('page-title', $message->subject)

@section('content')
<div class="p-6 lg:p-8 space-y-6">

    {{-- Back link --}}
    <a href="{{ route('admin.contact-messages.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-content-secondary hover:text-content-primary transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Messages
    </a>

    @if(session('success'))
    <div class="bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif

    {{-- Two-column layout --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Left: Sender info + message (spans 2 cols on xl) --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Sender info card --}}
            <div class="bg-surface-card border border-border rounded-2xl p-6">
                <h2 class="text-xs font-semibold text-content-muted uppercase tracking-wider mb-4">Sender Details</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-content-muted mb-0.5">Full Name</p>
                        <p class="text-content-primary font-semibold">{{ $message->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-content-muted mb-0.5">Email Address</p>
                        <a href="mailto:{{ $message->email }}"
                           class="text-brand-green hover:underline font-medium text-sm">{{ $message->email }}</a>
                    </div>
                    @if($message->phone)
                    <div>
                        <p class="text-xs text-content-muted mb-0.5">Phone</p>
                        <a href="tel:{{ $message->phone }}"
                           class="text-content-primary font-medium text-sm">{{ $message->phone }}</a>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs text-content-muted mb-0.5">Received</p>
                        <p class="text-content-secondary text-sm">{{ $message->created_at->format('d M Y \a\t H:i') }}</p>
                    </div>
                    @if($message->read_at)
                    <div>
                        <p class="text-xs text-content-muted mb-0.5">Read At</p>
                        <p class="text-content-secondary text-sm">{{ $message->read_at->format('d M Y \a\t H:i') }}</p>
                    </div>
                    @endif
                    @if($message->replied_at)
                    <div>
                        <p class="text-xs text-content-muted mb-0.5">Replied At</p>
                        <p class="text-content-secondary text-sm">{{ $message->replied_at->format('d M Y \a\t H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Message body card --}}
            <div class="bg-surface-card border border-border rounded-2xl p-6">
                <h2 class="text-xs font-semibold text-content-muted uppercase tracking-wider mb-1">Subject</h2>
                <p class="text-content-primary font-semibold text-lg mb-5">{{ $message->subject }}</p>

                <h2 class="text-xs font-semibold text-content-muted uppercase tracking-wider mb-3">Message</h2>
                <blockquote class="border-l-4 border-brand-green/50 pl-4 py-1">
                    <p class="text-content-secondary leading-relaxed whitespace-pre-wrap">{{ $message->message }}</p>
                </blockquote>
            </div>

        </div>

        {{-- Right: Action panel --}}
        <div class="space-y-4">

            {{-- Status badge --}}
            <div class="bg-surface-card border border-border rounded-2xl p-5">
                <h2 class="text-xs font-semibold text-content-muted uppercase tracking-wider mb-3">Status</h2>
                @if($message->is_read)
                <span class="inline-flex items-center gap-1.5 text-sm px-3 py-1.5 rounded-full border border-border text-content-muted font-semibold">
                    <span class="w-2 h-2 rounded-full bg-content-muted"></span>Read
                </span>
                @else
                <span class="inline-flex items-center gap-1.5 text-sm px-3 py-1.5 rounded-full border border-brand-green/40 bg-brand-green/10 text-brand-green font-semibold">
                    <span class="w-2 h-2 rounded-full bg-brand-green animate-pulse"></span>Unread
                </span>
                @endif
            </div>

            {{-- Actions --}}
            <div class="bg-surface-card border border-border rounded-2xl p-5 space-y-3">
                <h2 class="text-xs font-semibold text-content-muted uppercase tracking-wider mb-1">Actions</h2>

                {{-- Reply via Email --}}
                <a href="mailto:{{ $message->email }}?subject=Re: {{ rawurlencode($message->subject) }}"
                   class="flex items-center gap-2 w-full bg-brand-green text-surface-bg font-semibold px-4 py-2.5 rounded-xl hover:bg-brand-dark transition-colors text-sm text-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Reply via Email
                </a>

                {{-- Mark as Read (only if unread) --}}
                @if(!$message->is_read)
                <form method="POST" action="{{ route('admin.contact-messages.read', $message) }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-2 w-full border border-border text-content-secondary font-semibold px-4 py-2.5 rounded-xl hover:bg-surface-elevated transition-colors text-sm justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Mark as Read
                    </button>
                </form>
                @endif

                {{-- Delete --}}
                <form method="POST" action="{{ route('admin.contact-messages.destroy', $message) }}"
                      x-data
                      @submit.prevent="if(confirm('Delete this message permanently?')) $el.submit()">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="flex items-center gap-2 w-full border border-red-500/30 text-red-400 font-semibold px-4 py-2.5 rounded-xl hover:bg-red-500/10 transition-colors text-sm justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Message
                    </button>
                </form>

            </div>

        </div>
    </div>

</div>
@endsection
