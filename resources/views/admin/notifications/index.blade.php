@extends('layouts.admin')
@section('page-title', 'Push Notifications')

@section('content')
<div class="p-6 lg:p-8 space-y-8">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-display font-bold text-content-primary">Push Notifications</h1>
            <p class="text-content-secondary text-sm mt-1">Send instant push notifications to your subscribed users via OneSignal.</p>
        </div>
        <div class="flex-shrink-0 flex items-center gap-2 text-xs text-content-muted bg-surface-elevated border border-border rounded-xl px-3 py-2">
            <span class="w-2 h-2 rounded-full bg-brand-green animate-pulse flex-shrink-0"></span>
            OneSignal Connected
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
    <div class="bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl px-4 py-3 flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl px-4 py-3 flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        {{-- Compose Panel --}}
        <div class="xl:col-span-1">
            <div class="bg-surface-card border border-border rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-border">
                    <h2 class="font-semibold text-content-primary flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        Compose Notification
                    </h2>
                </div>
                <form method="POST" action="{{ route('admin.notifications.send') }}" class="p-6 space-y-5">
                    @csrf

                    {{-- Title --}}
                    <div>
                        <label class="block text-xs font-medium text-content-secondary mb-1.5">Notification Title <span class="text-red-400">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" maxlength="100" required
                               placeholder="e.g. New Arrival — Fresh Broilers!"
                               class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary placeholder-content-muted focus:outline-none focus:border-brand-green transition-colors">
                        @error('title')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                    </div>

                    {{-- Message --}}
                    <div>
                        <label class="block text-xs font-medium text-content-secondary mb-1.5">Message Body <span class="text-red-400">*</span></label>
                        <textarea name="message" rows="4" maxlength="300" required
                                  placeholder="Write a short, compelling message. Keep it under 300 characters."
                                  class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary placeholder-content-muted focus:outline-none focus:border-brand-green transition-colors resize-none">{{ old('message') }}</textarea>
                        @error('message')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                    </div>

                    {{-- URL --}}
                    <div>
                        <label class="block text-xs font-medium text-content-secondary mb-1.5">Link URL <span class="text-content-muted font-normal">(optional)</span></label>
                        <input type="url" name="url" value="{{ old('url') }}"
                               placeholder="https://takomzagro.com/shop"
                               class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary placeholder-content-muted focus:outline-none focus:border-brand-green transition-colors">
                        @error('url')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                    </div>

                    {{-- Audience --}}
                    <div>
                        <label class="block text-xs font-medium text-content-secondary mb-2">Send To</label>
                        <div class="space-y-2">
                            @foreach([
                                ['all', 'All Subscribers', 'Everyone who has opted in to push notifications'],
                                ['subscribers', 'Opted-In Users', 'Users who explicitly subscribed to notifications'],
                                ['customers', 'All Devices', 'All registered devices (widest reach)'],
                            ] as [$val, $label, $desc])
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <div class="mt-0.5 flex-shrink-0">
                                    <input type="radio" name="segment" value="{{ $val }}" {{ old('segment', 'all') === $val ? 'checked' : '' }}
                                           class="w-4 h-4 accent-[#B8F397] cursor-pointer">
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-content-primary">{{ $label }}</span>
                                    <p class="text-xs text-content-muted">{{ $desc }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('segment')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                    </div>

                    {{-- Preview --}}
                    <div class="bg-surface-elevated border border-border rounded-xl p-4 space-y-1" x-data="{}" id="preview">
                        <p class="text-xs text-content-muted font-medium uppercase tracking-wider mb-2">Preview</p>
                        <div class="flex items-start gap-3">
                            <img src="{{ asset('images/logo-mark.svg') }}" alt="" class="w-8 h-8 rounded-lg flex-shrink-0 logo-img">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-content-primary truncate" id="preview-title">Your notification title</p>
                                <p class="text-xs text-content-secondary line-clamp-2 mt-0.5" id="preview-body">Your message will appear here…</p>
                                <p class="text-xs text-content-muted mt-1">takomzagro.com</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-brand-green text-surface-bg font-semibold py-3 rounded-xl hover:bg-brand-dark transition-colors text-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Send Push Notification
                    </button>
                </form>
            </div>

            {{-- Tips --}}
            <div class="mt-4 bg-surface-card border border-border rounded-2xl p-5 space-y-3">
                <h3 class="text-xs font-semibold text-content-secondary uppercase tracking-wider">Tips for Better Push Notifications</h3>
                <ul class="space-y-2 text-xs text-content-secondary">
                    <li class="flex items-start gap-2"><span class="text-brand-green mt-0.5 flex-shrink-0">✓</span> Keep titles under 50 characters</li>
                    <li class="flex items-start gap-2"><span class="text-brand-green mt-0.5 flex-shrink-0">✓</span> Add urgency — "Limited stock", "Today only"</li>
                    <li class="flex items-start gap-2"><span class="text-brand-green mt-0.5 flex-shrink-0">✓</span> Always include a relevant landing URL</li>
                    <li class="flex items-start gap-2"><span class="text-brand-green mt-0.5 flex-shrink-0">✓</span> Don't send more than 2-3 pushes per week</li>
                    <li class="flex items-start gap-2"><span class="text-brand-green mt-0.5 flex-shrink-0">✓</span> Best times: 8–10am and 6–8pm</li>
                </ul>
            </div>
        </div>

        {{-- History --}}
        <div class="xl:col-span-2">
            <div class="bg-surface-card border border-border rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                    <h2 class="font-semibold text-content-primary flex items-center gap-2">
                        <svg class="w-4 h-4 text-content-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Notification History
                    </h2>
                    <span class="text-xs text-content-muted">Last 30 sent</span>
                </div>

                @if($logs->isEmpty())
                <div class="py-16 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-surface-elevated border border-border flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-content-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <p class="text-content-secondary font-medium">No notifications sent yet</p>
                    <p class="text-content-muted text-sm mt-1">Compose your first push above</p>
                </div>
                @else
                <div class="divide-y divide-border">
                    @foreach($logs as $log)
                    <div class="px-6 py-4 flex items-start gap-4 hover:bg-surface-elevated/50 transition-colors">
                        {{-- Status icon --}}
                        <div class="flex-shrink-0 mt-0.5">
                            @if($log->success)
                            <div class="w-8 h-8 rounded-xl bg-green-500/10 border border-green-500/20 flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            @else
                            <div class="w-8 h-8 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                            @endif
                        </div>
                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <p class="font-semibold text-content-primary text-sm truncate">{{ $log->title }}</p>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="text-xs px-2 py-0.5 rounded-full border
                                        {{ $log->segment === 'all' ? 'bg-blue-500/10 border-blue-500/20 text-blue-400' :
                                           ($log->segment === 'subscribers' ? 'bg-purple-500/10 border-purple-500/20 text-purple-400' :
                                           'bg-yellow-500/10 border-yellow-500/20 text-yellow-400') }}">
                                        {{ ucfirst($log->segment) }}
                                    </span>
                                    <span class="text-xs text-content-muted">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <p class="text-content-secondary text-xs mt-1 line-clamp-2">{{ $log->message }}</p>
                            <div class="flex items-center gap-3 mt-1.5 text-xs text-content-muted">
                                @if($log->url)
                                <a href="{{ $log->url }}" target="_blank" rel="noopener noreferrer"  class="hover:text-brand-green transition-colors flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    Link
                                </a>
                                @endif
                                @if($log->sender)
                                <span>by {{ $log->sender->name }}</span>
                                @endif
                                @if(!$log->success)
                                <span class="text-red-400">Failed — check ONESIGNAL_REST_API_KEY</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-border">
                    {{ $logs->links() }}
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Live preview update
    document.querySelector('[name="title"]')?.addEventListener('input', function() {
        document.getElementById('preview-title').textContent = this.value || 'Your notification title';
    });
    document.querySelector('[name="message"]')?.addEventListener('input', function() {
        document.getElementById('preview-body').textContent = this.value || 'Your message will appear here…';
    });
</script>
@endsection
