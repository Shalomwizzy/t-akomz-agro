@extends('layouts.admin')

@section('page-title', 'Reviews')

@section('content')

{{-- Pending Reviews --}}
<div class="mb-8">
    <div class="flex items-center gap-3 mb-4">
        <h2 class="font-semibold text-content-primary">Pending Approval</h2>
        @if($pending->count())
        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-yellow-500/20 text-yellow-400">{{ $pending->count() }}</span>
        @endif
    </div>

    @if($pending->count())
    <div class="space-y-4">
        @foreach($pending as $review)
        <div class="card p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3 flex-1 min-w-0">
                    <img src="{{ $review->user->avatar_url }}" alt="{{ $review->user->name }}"
                         class="w-10 h-10 rounded-full object-cover flex-shrink-0 mt-0.5">
                    <div class="min-w-0">
                        <div class="flex items-center flex-wrap gap-2 mb-0.5">
                            <span class="text-sm font-medium text-content-primary">{{ $review->user->name }}</span>
                            <span class="text-xs text-content-muted">reviewed</span>
                            <a href="{{ route('admin.products.edit', $review->product) }}"
                               class="text-xs text-brand-green hover:underline font-medium truncate max-w-48">
                                {{ $review->product->name }}
                            </a>
                        </div>
                        <div class="flex items-center gap-1.5 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-surface-elevated' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                            <span class="text-xs text-content-muted ml-1">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        @if($review->title)
                        <p class="text-sm font-semibold text-content-primary mb-1">{{ $review->title }}</p>
                        @endif
                        <p class="text-sm text-content-secondary leading-relaxed">{{ $review->body }}</p>
                    </div>
                </div>

                <div class="flex flex-col gap-2 flex-shrink-0">
                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary py-1.5 px-4 text-xs w-full">Approve</button>
                    </form>
                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                          onsubmit="return confirm('Delete this review?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger py-1.5 px-4 text-xs w-full">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="card p-8 text-center">
        <div class="text-3xl mb-2">✅</div>
        <p class="text-content-muted text-sm">No pending reviews. All caught up!</p>
    </div>
    @endif
</div>

{{-- Approved Reviews --}}
<div>
    <h2 class="font-semibold text-content-primary mb-4">Approved Reviews</h2>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="table-header text-left">Customer</th>
                        <th class="table-header text-left">Product</th>
                        <th class="table-header text-left">Rating</th>
                        <th class="table-header text-left">Review</th>
                        <th class="table-header text-left">Date</th>
                        <th class="table-header text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($approved as $review)
                    <tr class="hover:bg-surface-elevated/50 transition-colors">
                        <td class="table-cell">
                            <div class="flex items-center gap-2">
                                <img src="{{ $review->user->avatar_url }}" alt="{{ $review->user->name }}"
                                     class="w-7 h-7 rounded-full object-cover">
                                <span class="text-sm text-content-primary">{{ $review->user->name }}</span>
                            </div>
                        </td>
                        <td class="table-cell">
                            <span class="text-xs text-content-secondary line-clamp-1 max-w-36">{{ $review->product->name }}</span>
                        </td>
                        <td class="table-cell">
                            <div class="flex gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-3 h-3 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-surface-elevated' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                        </td>
                        <td class="table-cell">
                            <p class="text-xs text-content-muted line-clamp-2 max-w-48">
                                @if($review->title)<strong>{{ $review->title }}:</strong> @endif{{ $review->body }}
                            </p>
                        </td>
                        <td class="table-cell">
                            <span class="text-xs text-content-muted">{{ $review->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="table-cell">
                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                  onsubmit="return confirm('Delete this review?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-content-muted text-sm">No approved reviews yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $approved->links() }}</div>
</div>
@endsection
