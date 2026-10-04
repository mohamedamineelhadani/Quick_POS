@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Order History</h1>
        <a href="{{ route('pos.index') }}" class="btn btn-primary">Back to POS</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th class="text-center">Items Count</th>
                <th class="text-end">Gross Amount</th>
                <th class="text-end">Tax (5%)</th>
                <th class="text-end">Net Amount</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                    <td class="text-center">
                        <span class="badge bg-info text-dark">{{ $order->items_count }}</span>
                    </td>
                    <td class="text-end">${{ number_format($order->gross_amount, 2) }}</td>
                    <td class="text-end">${{ number_format($order->tax_amount, 2) }}</td>
                    <td class="text-end fw-bold">${{ number_format($order->net_amount, 2) }}</td>
                    <td class="text-end">
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-info">View Details</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No orders found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $orders->links() }}
    </div>
@endsection