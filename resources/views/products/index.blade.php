@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Products</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
    </div>

    <form method="GET" action="{{ route('products.index') }}" class="row g-3 mb-3">
        <div class="col-md-4">
            <label for="search" class="form-label">Search</label>
            <input type="text" name="search" id="search" class="form-control"
                   value="{{ $search ?? request('search') }}"
                   placeholder="Search by name or SKU">
        </div>
        <div class="col-md-4">
            <label for="category_id" class="form-label">Category</label>
            <select name="category_id" id="category_id" class="form-select">
                <option value="">All Categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ (isset($category_id) ? $category_id : request('category_id')) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-secondary me-2">Filter</button>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>SKU</th>
                <th>Category</th>
                <th class="text-end">Price</th>
                <th class="text-end">Stock</th>
                <th>Image</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td class="text-end">${{ number_format($product->price, 2) }}</td>
                    <td class="text-end">{{ $product->stock_quantity }}</td>
                    <td>
                        @if ($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                 class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <span class="text-muted">No image</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info">View</a>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Edit</a>

                        <!-- Delete button triggers modal -->
                        <button type="button" class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal"
                                data-product-id="{{ $product->id }}"
                                data-product-name="{{ $product->name }}">
                            Delete
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">No products found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <strong id="deleteProductName"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteProductForm" method="POST" action="#">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                const productId = button.getAttribute('data-product-id');
                const productName = button.getAttribute('data-product-name');

                const nameSpan = deleteModal.querySelector('#deleteProductName');
                const form = deleteModal.querySelector('#deleteProductForm');

                nameSpan.textContent = productName;
                form.action = "{{ url('products') }}/" + productId;
            });
        }
    </script>
@endpush