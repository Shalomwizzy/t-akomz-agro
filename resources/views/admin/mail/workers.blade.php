@extends('layouts.admin')

@section('title', 'Mail Workers & Staff')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background: rgba(184,243,151,0.12); border: 1px solid rgba(184,243,151,0.2);">
            <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-xl font-bold text-content-primary">Mail Workers & Staff</h1>
            <p class="text-sm text-content-muted">Send email to farm workers or admin staff</p>
        </div>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium"
         style="background: rgba(184,243,151,0.12); border: 1px solid rgba(184,243,151,0.25); color: #B8F397;">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="flex items-start gap-3 px-4 py-3 rounded-xl text-sm" style="background: rgba(229,57,53,0.1); border: 1px solid rgba(229,57,53,0.3); color: #EF9A9A;">
        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.mail.workers.send') }}"
          x-data="{ type: '{{ old('recipient_type', 'all_workers') }}' }">
        @csrf

        <div class="rounded-2xl overflow-hidden space-y-0" style="background: rgba(17,17,17,0.8); border: 1px solid rgba(255,255,255,0.06);">

            {{-- Recipient Type --}}
            <div class="px-6 py-5" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                <h2 class="text-sm font-bold text-content-primary mb-4">Recipients</h2>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    @foreach([
                        ['all_workers',   '👷',  'All Workers',    'Active farm workers with email'],
                        ['all_staff',     '👔',  'All Staff',      'All admin panel staff'],
                        ['specific_worker','🎯', 'A Worker',       'Pick one worker'],
                        ['specific_staff', '🎯', 'A Staff Member', 'Pick one staff'],
                    ] as [$val, $icon, $label, $desc])
                    <label class="cursor-pointer rounded-xl p-3 transition-all"
                           :class="type === '{{ $val }}' ? 'ring-2 ring-brand-green' : ''"
                           style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                        <input type="radio" name="recipient_type" value="{{ $val }}"
                               x-model="type" class="sr-only"
                               {{ old('recipient_type', 'all_workers') === $val ? 'checked' : '' }}>
                        <div class="text-xl mb-1">{{ $icon }}</div>
                        <div class="text-xs font-bold text-content-primary">{{ $label }}</div>
                        <div class="text-xs text-content-muted mt-0.5">{{ $desc }}</div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Specific Worker picker --}}
            <div x-show="type === 'specific_worker'" x-cloak
                 class="px-6 py-4" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                <label class="block text-xs font-semibold text-content-muted mb-2">Select Worker</label>
                <select name="worker_id"
                        class="w-full rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:ring-2 focus:ring-brand-green"
                        style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                    <option value="">— Choose a worker —</option>
                    @foreach($workers as $w)
                    <option value="{{ $w->id }}" {{ old('worker_id') == $w->id ? 'selected' : '' }}>
                        {{ $w->full_name }} ({{ $w->role }}){{ $w->email ? '' : ' — no email' }}
                    </option>
                    @endforeach
                </select>
                @if($workers->where('email', null)->count() > 0)
                <p class="text-xs text-content-muted mt-1.5">Workers marked "no email" cannot receive mail.</p>
                @endif
            </div>

            {{-- Specific Staff picker --}}
            <div x-show="type === 'specific_staff'" x-cloak
                 class="px-6 py-4" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                <label class="block text-xs font-semibold text-content-muted mb-2">Select Staff Member</label>
                <select name="staff_id"
                        class="w-full rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:ring-2 focus:ring-brand-green"
                        style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                    <option value="">— Choose a staff member —</option>
                    @foreach($staff as $s)
                    <option value="{{ $s->id }}" {{ old('staff_id') == $s->id ? 'selected' : '' }}>
                        {{ $s->name }} ({{ $s->roles->first()?->name ?? 'Staff' }}) — {{ $s->email }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Subject & Body --}}
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-content-muted mb-2">Subject Line</label>
                    <input type="text" name="subject" value="{{ old('subject') }}"
                           placeholder="e.g. Important Farm Update — Please Read"
                           class="w-full rounded-xl px-4 py-2.5 text-sm text-content-primary placeholder-content-muted focus:outline-none focus:ring-2 focus:ring-brand-green"
                           style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-content-muted mb-2">Message Body</label>
                    <textarea name="body" rows="10" placeholder="Write your message here. Plain text, line breaks are preserved in the email."
                              class="w-full rounded-xl px-4 py-3 text-sm text-content-primary placeholder-content-muted focus:outline-none focus:ring-2 focus:ring-brand-green resize-y"
                              style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">{{ old('body') }}</textarea>
                    <p class="text-xs text-content-muted mt-1.5">Plain text only. Line breaks are preserved in the email.</p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 flex items-center justify-between gap-4" style="border-top: 1px solid rgba(255,255,255,0.05);">
                <div class="text-xs text-content-muted">
                    <template x-if="type === 'all_workers'">
                        <span>{{ $workers->whereNotNull('email')->count() }} active worker(s) with email address</span>
                    </template>
                    <template x-if="type === 'all_staff'">
                        <span>{{ $staff->count() }} staff member(s)</span>
                    </template>
                    <template x-if="type === 'specific_worker' || type === 'specific_staff'">
                        <span>1 recipient</span>
                    </template>
                </div>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-dark-bg transition-all hover:scale-105 active:scale-95"
                        style="background: #B8F397;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Send Email
                </button>
            </div>

        </div>
    </form>

    {{-- Info note --}}
    <div class="rounded-xl px-4 py-3 text-xs text-content-muted" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
        <strong class="text-content-secondary">Note:</strong>
        Emails are sent immediately and cannot be recalled. Workers without an email address on file will not receive messages.
        To add an email to a worker, edit their profile from the
        <a href="{{ route('admin.workers.index') }}" class="text-brand-green hover:underline">Workers page</a>.
    </div>

</div>
@endsection
