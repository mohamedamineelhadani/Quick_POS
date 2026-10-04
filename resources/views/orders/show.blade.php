@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Order Details #{{ $order->id }}</h1>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Back to History</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Items</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>{{ $item->product->name ?? 'Product Deleted' }}</td>
                                    <td class="text-center">${{ number_format($item->price, 2) }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Order Date:</span>
                        <strong>{{ $order->created_at->format('Y-m-d H:i') }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Gross Total</span>
                        <strong>${{ number_format($order->gross_amount, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Taxes (5%)</span>
                        <strong>${{ number_format($order->tax_amount, 2) }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-0">
                        <span class="h5">Net Total</span>
                        <span class="h5 fw-bold text-success">${{ number_format($order->net_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection