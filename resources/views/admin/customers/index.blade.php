@extends('layouts.admin')

@section('page-title', 'Customers')

@section('content')
<div class="card p-4 mb-6">
    <form action="{{ route('admin.customers.index') }}" method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search by name, email, phone..." class="input py-2 text-sm flex-1 min-w-48">
        <button type="submit" class="btn-primary py-2 px-4 text-sm">Search</button>
        <a href="{{ route('admin.customers.index') }}" class="btn-ghost py-2 px-4 text-sm">Clear</a>
    </form>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-header text-left">Customer</th>
                    <th class="table-header text-left">Phone</th>
                    <th class="table-header text-left">Orders</th>
                    <th class="table-header text-left">Total Spent</th>
                    <th class="table-header text-left">Joined</th>
                    <th class="table-header text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr class="hover:bg-surface-elevated/50 transition-colors">
                    <td class="table-cell">
                        <div class="flex items-center gap-3">
                            <img src="{{ $customer->avatar_url }}" alt="{{ $customer->name }}"
                                 class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                            <div>
                                <p class="text-sm text-content-primary font-medium">{{ $customer->name }}</p>
                                <p class="text-xs text-content-muted">{{ $customer->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="table-cell"><span class="text-sm text-content-secondary">{{ $customer->phone ?? '—' }}</span></td>
                    <td class="table-cell"><span class="text-sm text-content-primary font-semibold">{{ $customer->orders_count ?? 0 }}</span></td>
                    <td class="table-cell"><span class="text-sm text-brand-green font-semibold">₦{{ number_format($customer->orders_sum_total ?? 0, 0) }}</span></td>
                    <td class="table-cell"><span class="text-xs text-content-muted">{{ $customer->created_at->format('d M Y') }}</span></td>
                    <td class="table-cell">
                        <a href="{{ route('admin.customers.show', $customer) }}" class="text-xs text-brand-green hover:underline">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-12 text-center text-content-muted text-sm">No customers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $customers->withQueryString()->links() }}</div>
@endsection
