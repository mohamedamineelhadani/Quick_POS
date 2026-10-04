@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Product Details</h1>
        <div>
            <a href="{{ route('products.edit', $product) }}" class="btn btn-primary me-2">Edit</a>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Back to List</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card">
                @if ($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top"
                         alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
                @else
                    <div class="card-body text-center text-muted">
                        No image available
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ $product->name }}</h5>
                    <dl class="row mb-0">
                        <dt class="col-sm-3">SKU</dt>
                        <dd class="col-sm-9">{{ $product->sku }}</dd>

                        <dt class="col-sm-3">Category</dt>
                        <dd class="col-sm-9">{{ $product->category->name ?? '-' }}</dd>

                        <dt class="col-sm-3">Price</dt>
                        <dd class="col-sm-9">${{ number_format($product->price, 2) }}</dd>

                        <dt class="col-sm-3">Stock</dt>
                        <dd class="col-sm-9">{{ $product->stock_quantity }}</dd>

                        <dt class="col-sm-3">Description</dt>
                        <dd class="col-sm-9">
                            {{ $product->description ?: 'No description.' }}
                        </dd>

                        <dt class="col-sm-3">Created At</dt>
                        <dd class="col-sm-9">{{ $product->created_at?->format('Y-m-d H:i') }}</dd>

                        <dt class="col-sm-3">Updated At</dt>
                        <dd class="col-sm-9">{{ $product->updated_at?->format('Y-m-d H:i') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection

