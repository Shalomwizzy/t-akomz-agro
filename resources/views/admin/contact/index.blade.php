@extends('layouts.admin')
@section('page-title', 'Contact Messages')

@section('content')
<div class="p-6 lg:p-8 space-y-8">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-display font-bold text-content-primary">Contact Messages</h1>
            <p class="text-content-secondary text-sm mt-1">Messages submitted through the public contact form.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl px-4 py-3 text-sm">{{ session('error') }}</div>
    @endif

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-surface-card border border-border rounded-2xl p-5">
            <p class="text-xs text-content-muted font-medium uppercase tracking-wider">Total Messages</p>
            <p class="text-3xl font-display font-bold text-content-primary mt-1">{{ $totalCount }}</p>
        </div>
        <div class="bg-surface-card border {{ $unreadCount > 0 ? 'border-brand-green/40' : 'border-border' }} rounded-2xl p-5">
            <p class="text-xs text-content-muted font-medium uppercase tracking-wider">Unread</p>
            <p class="text-3xl font-display font-bold {{ $unreadCount > 0 ? 'text-brand-green' : 'text-content-primary' }} mt-1">{{ $unreadCount }}</p>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" class="flex flex-wrap gap-3 items-center">
        <select name="status" class="bg-surface-card border border-border rounded-xl px-3 py-2 text-sm text-content-primary focus:outline-none focus:border-brand-green">
            <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>All Messages</option>
            <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread Only</option>
        </select>
        <button type="submit" class="bg-brand-green text-surface-bg px-4 py-2 rounded-xl text-sm font-semibold hover:bg-brand-dark transition-colors">Filter</button>
        @if(request('status') === 'unread')
        <a href="{{ route('admin.contact-messages.index') }}" class="px-4 py-2 rounded-xl text-sm border border-border text-content-secondary hover:bg-surface-elevated transition-colors">Clear</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="bg-surface-card border border-border rounded-2xl overflow-hidden">
        @if($messages->isEmpty())
        <div class="py-16 text-center">
            <svg class="w-12 h-12 mx-auto text-content-muted mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <p class="text-content-secondary font-medium">No contact messages yet.</p>
            <p class="text-content-muted text-sm mt-1">Messages submitted via the contact form will appear here.</p>
        </div>
        @else
        <table class="w-full text-sm">
            <thead class="border-b border-border bg-surface-elevated">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-content-muted uppercase tracking-wider">Sender</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-content-muted uppercase tracking-wider hidden md:table-cell">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-content-muted uppercase tracking-wider hidden lg:table-cell">Preview</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-content-muted uppercase tracking-wider hidden sm:table-cell">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-content-muted uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @foreach($messages as $msg)
                <tr class="hover:bg-surface-elevated/50 transition-colors {{ !$msg->is_read ? 'border-l-2 border-l-brand-green' : '' }}">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-content-primary {{ !$msg->is_read ? '' : 'font-medium' }}">{{ $msg->name }}</p>
                        <p class="text-xs text-content-muted mt-0.5">{{ $msg->email }}</p>
                    </td>
                    <td class="px-6 py-4 hidden md:table-cell">
                        <span class="{{ !$msg->is_read ? 'text-content-primary font-semibold' : 'text-content-secondary' }}">{{ $msg->subject }}</span>
                    </td>
                    <td class="px-6 py-4 text-content-muted hidden lg:table-cell">{{ Str::limit($msg->message, 80) }}</td>
                    <td class="px-6 py-4 text-xs text-content-muted hidden sm:table-cell whitespace-nowrap">{{ $msg->created_at->format('d M Y, H:i') }}</td>
                    <td class="px-6 py-4">
                        @if($msg->is_read)
                        <span class="inline-flex items-center text-xs px-2.5 py-1 rounded-full border border-border text-content-muted font-semibold">Read</span>
                        @else
                        <span class="inline-flex items-center text-xs px-2.5 py-1 rounded-full border border-brand-green/40 bg-brand-green/10 text-brand-green font-semibold">Unread</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.contact-messages.show', $msg) }}" class="text-brand-green hover:underline text-xs font-semibold">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($messages->hasPages())
        <div class="px-6 py-4 border-t border-border">{{ $messages->links() }}</div>
        @endif
        @endif
    </div>

</div>
@endsection
