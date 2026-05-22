@extends('layouts.admin')
@section('page-title', 'Our Team')
@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="font-display font-semibold text-xl text-content-primary">Our Team</h2>
        <p class="text-content-muted text-sm mt-0.5">{{ $members->count() }} members · shown on the homepage</p>
    </div>
</div>

@if(session('success'))
<div class="mb-5 px-4 py-3 rounded-xl text-sm text-brand-green bg-brand-green/10 border border-brand-green/20">{{ session('success') }}</div>
@endif

{{-- Add member --}}
<div class="card p-6 mb-6">
    <h3 class="font-semibold text-content-primary mb-4">Add Team Member</h3>
    <form action="{{ route('admin.team.store') }}" method="POST" enctype="multipart/form-data"
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
        @csrf
        <div>
            <label class="label">Full Name</label>
            <input type="text" name="name" class="input text-sm" required placeholder="e.g. Temitope Akomo">
        </div>
        <div>
            <label class="label">Role / Title</label>
            <input type="text" name="role" class="input text-sm" required placeholder="e.g. Farm Director">
        </div>
        <div>
            <label class="label">Photo (JPG/PNG)</label>
            <input type="file" name="image" accept="image/*"
                   class="input text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-brand-green/20 file:text-brand-green file:font-medium cursor-pointer">
        </div>
        <div>
            <label class="label">Short Bio <span class="text-content-muted font-normal">(optional)</span></label>
            <input type="text" name="bio" class="input text-sm" placeholder="One-liner about this person">
        </div>
        <div class="sm:col-span-2 lg:col-span-4 flex gap-3">
            <input type="number" name="sort_order" class="input text-sm w-24" placeholder="Order" min="0">
            <button type="submit" class="btn-primary py-2.5 px-6">Add Member</button>
        </div>
    </form>
</div>

{{-- Member cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
    @forelse($members as $member)
    <div class="card overflow-hidden {{ $member->is_active ? '' : 'opacity-50' }}">
        <div class="aspect-square bg-surface-elevated overflow-hidden">
            <img src="{{ $member->image_url }}" alt="{{ $member->name }}"
                 class="w-full h-full object-cover object-top">
        </div>
        <div class="p-4">
            <p class="font-semibold text-content-primary">{{ $member->name }}</p>
            <p class="text-xs text-brand-green font-medium mt-0.5">{{ $member->role }}</p>
            @if($member->bio)
            <p class="text-xs text-content-muted mt-1.5 line-clamp-2">{{ $member->bio }}</p>
            @endif

            <form action="{{ route('admin.team.update', $member) }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-2">
                @csrf @method('PATCH')
                <input type="text" name="name" value="{{ $member->name }}" class="input text-xs py-1.5" required>
                <input type="text" name="role" value="{{ $member->role }}" class="input text-xs py-1.5" required>
                <input type="text" name="bio" value="{{ $member->bio }}" class="input text-xs py-1.5" placeholder="Bio">
                <div class="flex items-center gap-2">
                    <input type="file" name="image" accept="image/*"
                           class="input text-xs py-1 file:mr-2 file:py-0.5 file:px-2 file:rounded file:border-0 file:bg-brand-green/20 file:text-brand-green file:text-xs cursor-pointer">
                </div>
                <div class="flex items-center gap-2">
                    <input type="number" name="sort_order" value="{{ $member->sort_order }}" class="input text-xs py-1.5 w-20" min="0">
                    <label class="flex items-center gap-1.5 text-xs text-content-secondary cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ $member->is_active ? 'checked' : '' }} class="accent-green-500">
                        Visible
                    </label>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary py-1.5 px-4 text-xs flex-1">Save</button>
                    <button type="button"
                            onclick="if(confirm('Remove {{ addslashes($member->name) }}?')) this.closest('div').nextElementSibling.querySelector('form').submit()"
                            class="btn-danger py-1.5 px-3 text-xs">✕</button>
                </div>
            </form>
            <div class="hidden">
                <form action="{{ route('admin.team.destroy', $member) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit"></button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="sm:col-span-2 lg:col-span-4 card p-16 text-center">
        <p class="text-content-muted">No team members yet. Add some above.</p>
    </div>
    @endforelse
</div>
@endsection
