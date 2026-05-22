@extends('layouts.admin')

@section('page-title', 'Staff Management')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="flex-1">
            <p class="text-content-muted text-sm">Manage admin, managers, and sales team members.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-primary py-2.5 px-5 text-sm flex-shrink-0 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Staff Member
        </a>
    </div>

    {{-- Role legend --}}
    <div class="flex flex-wrap gap-2 text-xs">
        @foreach([
            ['SUPER_ADMIN', 'bg-purple-500/15 text-purple-400', 'Full system access'],
            ['ADMIN',       'bg-blue-500/15 text-blue-400',   'Admin panel access'],
            ['MANAGER',     'bg-indigo-500/15 text-indigo-400','Finance + inventory'],
            ['SALES',       'bg-cyan-500/15 text-cyan-400',   'Products + orders'],
        ] as [$r, $cls, $desc])
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $cls }}">
            <span class="font-semibold">{{ $r }}</span> &middot; {{ $desc }}
        </span>
        @endforeach
    </div>

    {{-- Filters --}}
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search name or email…" class="input sm:w-72 text-sm py-2">
        <select name="role" class="input sm:w-44 text-sm py-2">
            <option value="">All Roles</option>
            @foreach($staffRolesForFilter as $role)
            <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary py-2 px-4 text-sm">Filter</button>
        @if(request()->hasAny(['search','role']))
        <a href="{{ route('admin.users.index') }}" class="btn-ghost py-2 px-4 text-sm">Clear</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-surface-border">
                        <th class="table-header text-left">Member</th>
                        <th class="table-header text-left">Role</th>
                        <th class="table-header text-left hidden md:table-cell">Joined</th>
                        <th class="table-header text-left hidden lg:table-cell">Last Login</th>
                        <th class="table-header text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-border">
                    @forelse($staff as $user)
                    @php $role = $user->roles->first(); @endphp
                    <tr class="hover:bg-surface-elevated/50 transition-colors">
                        <td class="table-cell">
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->avatar_url }}" class="w-9 h-9 rounded-full object-cover flex-shrink-0" alt="">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-content-primary truncate">{{ $user->name }}
                                        @if($user->id === auth()->id())
                                        <span class="text-xs text-brand-green">(you)</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-content-muted truncate">{{ $user->email }}</p>
                                    @if($user->phone)
                                    <p class="text-xs text-content-muted">{{ $user->phone }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="table-cell">
                            @if($role)
                            @php
                                $roleColors = [
                                    'SUPER_ADMIN' => 'bg-purple-500/15 text-purple-400',
                                    'ADMIN'       => 'bg-blue-500/15 text-blue-400',
                                    'MANAGER'     => 'bg-indigo-500/15 text-indigo-400',
                                    'SALES'       => 'bg-cyan-500/15 text-cyan-400',
                                ];
                            @endphp
                            <span class="badge {{ $roleColors[$role->name] ?? 'badge-gray' }}">{{ $role->name }}</span>
                            @else
                            <span class="badge badge-gray">No Role</span>
                            @endif
                        </td>
                        <td class="hidden md:table-cell px-4 py-3.5 text-xs text-content-muted">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="hidden lg:table-cell px-4 py-3.5 text-xs text-content-muted">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                        </td>
                        <td class="table-cell text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($role?->name !== 'SUPER_ADMIN')
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="text-xs text-brand-green hover:underline">Edit</a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                      onsubmit="return confirm('Delete {{ $user->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-400 hover:underline">Delete</button>
                                </form>
                                @endif
                                @else
                                <span class="text-xs text-content-muted">Protected</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="table-cell text-center text-content-muted py-10">No staff members found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($staff->hasPages())
        <div class="p-4 border-t border-surface-border">{{ $staff->links() }}</div>
        @endif
    </div>

</div>
@endsection
