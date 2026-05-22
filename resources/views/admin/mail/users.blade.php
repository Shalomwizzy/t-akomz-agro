@extends('layouts.admin')

@section('title', 'Mail Customers')

@section('content')
<div class="max-w-4xl mx-auto space-y-6"
     x-data="{
         type: '{{ old('recipient_type', 'all_customers') }}',
         selected: {{ json_encode(array_map('intval', (array) old('user_ids', []))) }},
         search: '',
         dropdownOpen: false,
         allUsers: {{ $users->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'email' => $u->email])->values()->toJson() }},
         get filtered() {
             if (!this.search) return this.allUsers;
             const q = this.search.toLowerCase();
             return this.allUsers.filter(u => u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q));
         },
         toggle(id) {
             const idx = this.selected.indexOf(id);
             if (idx === -1) this.selected.push(id);
             else this.selected.splice(idx, 1);
         },
         isSelected(id) { return this.selected.includes(id); },
         get selectedNames() {
             return this.allUsers.filter(u => this.selected.includes(u.id)).map(u => u.name);
         },
         selectAll() { this.selected = this.allUsers.map(u => u.id); },
         clearAll() { this.selected = []; },
     }">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background: rgba(184,243,151,0.12); border: 1px solid rgba(184,243,151,0.2);">
            <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-xl font-bold text-content-primary">Mail Customers</h1>
            <p class="text-sm text-content-muted">Send email to all customers or a selected group ({{ $users->count() }} total)</p>
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

    <form method="POST" action="{{ route('admin.mail.users.send') }}">
        @csrf

        <div class="rounded-2xl overflow-visible" style="background: rgba(17,17,17,0.8); border: 1px solid rgba(255,255,255,0.06);">

            {{-- Recipient Type --}}
            <div class="px-6 py-5" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                <h2 class="text-sm font-bold text-content-primary mb-4">Recipients</h2>
                <div class="flex flex-col sm:flex-row gap-3">
                    <label class="flex-1 cursor-pointer rounded-xl p-4 transition-all"
                           :class="type === 'all_customers' ? 'ring-2 ring-brand-green' : ''"
                           style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                        <input type="radio" name="recipient_type" value="all_customers"
                               x-model="type" class="sr-only">
                        <div class="text-2xl mb-2">📢</div>
                        <div class="text-sm font-bold text-content-primary">All Customers</div>
                        <div class="text-xs text-content-muted mt-1">Broadcast to all {{ $users->count() }} registered customer(s)</div>
                    </label>

                    <label class="flex-1 cursor-pointer rounded-xl p-4 transition-all"
                           :class="type === 'selected' ? 'ring-2 ring-brand-green' : ''"
                           style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                        <input type="radio" name="recipient_type" value="selected"
                               x-model="type" class="sr-only">
                        <div class="text-2xl mb-2">🎯</div>
                        <div class="text-sm font-bold text-content-primary">Selected Customers</div>
                        <div class="text-xs text-content-muted mt-1">Hand-pick specific customer(s) from the dropdown</div>
                    </label>
                </div>
            </div>

            {{-- Customer dropdown picker (shown when 'selected') --}}
            <div x-show="type === 'selected'" x-cloak
                 class="px-6 py-5" style="border-bottom: 1px solid rgba(255,255,255,0.05); position: relative;">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-semibold text-content-muted">Select Customers</label>
                    <div class="flex items-center gap-3 text-xs">
                        <button type="button" @click="selectAll()" class="text-brand-green hover:underline">Select all</button>
                        <button type="button" @click="clearAll()" class="text-content-muted hover:text-content-secondary">Clear</button>
                        <span class="text-content-muted" x-text="selected.length + ' selected'"></span>
                    </div>
                </div>

                {{-- Dropdown trigger --}}
                <div class="relative" @click.outside="dropdownOpen = false">
                    <button type="button"
                            @click="dropdownOpen = !dropdownOpen"
                            class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-xl text-sm text-left focus:outline-none focus:ring-2 focus:ring-brand-green transition-all"
                            style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                        <span class="truncate text-content-primary" x-text="selected.length === 0 ? 'Choose customers…' : selected.length + ' customer(s) selected'"></span>
                        <svg class="w-4 h-4 text-content-muted flex-shrink-0 transition-transform" :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- Dropdown panel --}}
                    <div x-show="dropdownOpen" x-cloak
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute left-0 right-0 mt-1 rounded-xl shadow-2xl z-50 overflow-hidden"
                         style="background: #1A1A1A; border: 1px solid rgba(255,255,255,0.12);">

                        {{-- Search inside dropdown --}}
                        <div class="p-2" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-content-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text" x-model="search" placeholder="Search by name or email…"
                                       @click.stop
                                       class="w-full pl-9 pr-3 py-2 rounded-lg text-sm text-content-primary placeholder-content-muted focus:outline-none focus:ring-1 focus:ring-brand-green"
                                       style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08);">
                            </div>
                        </div>

                        {{-- Options list --}}
                        <div class="max-h-56 overflow-y-auto">
                            <template x-for="user in filtered" :key="user.id">
                                <div @click="toggle(user.id)"
                                     class="flex items-center gap-3 px-4 py-2.5 cursor-pointer transition-colors hover:bg-white/5"
                                     :class="isSelected(user.id) ? 'bg-brand-green/8' : ''">
                                    <div class="w-4 h-4 rounded flex items-center justify-center flex-shrink-0 transition-all"
                                         :class="isSelected(user.id) ? 'bg-brand-green' : ''"
                                         style="border: 1.5px solid rgba(255,255,255,0.25);">
                                        <svg x-show="isSelected(user.id)" class="w-2.5 h-2.5 text-dark-bg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-medium text-content-primary truncate" x-text="user.name"></div>
                                        <div class="text-xs text-content-muted truncate" x-text="user.email"></div>
                                    </div>
                                </div>
                            </template>
                            <div x-show="filtered.length === 0" class="text-center py-6 text-sm text-content-muted">
                                No customers match your search.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hidden inputs for selected IDs --}}
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="user_ids[]" :value="id">
                </template>

                {{-- Selected tags --}}
                <div x-show="selected.length > 0" class="flex flex-wrap gap-2 mt-3">
                    <template x-for="name in selectedNames" :key="name">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium"
                              style="background: rgba(184,243,151,0.12); border: 1px solid rgba(184,243,151,0.2); color: #B8F397;">
                            <span x-text="name"></span>
                        </span>
                    </template>
                </div>
            </div>

            {{-- Subject & Body --}}
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-content-muted mb-2">Subject Line</label>
                    <input type="text" name="subject" value="{{ old('subject') }}"
                           placeholder="e.g. New Harvest Available — Fresh Eggs in Stock!"
                           class="w-full rounded-xl px-4 py-2.5 text-sm text-content-primary placeholder-content-muted focus:outline-none focus:ring-2 focus:ring-brand-green"
                           style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-content-muted mb-2">Message Body</label>
                    <textarea name="body" rows="10"
                              placeholder="Write your message here. Plain text — line breaks are preserved."
                              class="w-full rounded-xl px-4 py-3 text-sm text-content-primary placeholder-content-muted focus:outline-none focus:ring-2 focus:ring-brand-green resize-y"
                              style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">{{ old('body') }}</textarea>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 flex items-center justify-between gap-4" style="border-top: 1px solid rgba(255,255,255,0.05);">
                <div class="text-xs text-content-muted">
                    <template x-if="type === 'all_customers'">
                        <span>{{ $users->count() }} customer(s) will receive this email</span>
                    </template>
                    <template x-if="type === 'selected'">
                        <span x-text="selected.length + ' customer(s) selected'"></span>
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

    <div class="rounded-xl px-4 py-3 text-xs text-content-muted" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
        <strong class="text-content-secondary">Note:</strong>
        Emails are sent immediately and cannot be recalled.
        For large lists, consider sending during off-peak hours to avoid SMTP rate limits.
    </div>

</div>
@endsection
