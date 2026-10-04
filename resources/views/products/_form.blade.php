@csrf

<div class="row mb-3">
    <div class="col-md-6">
        <label for="name" class="form-label">Name</label>
        <input type="text" name="name" id="name"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $product->name ?? '') }}" required>
        @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="sku" class="form-label">SKU</label>
        <input type="text" name="sku" id="sku"
               class="form-control @error('sku') is-invalid @enderror"
               value="{{ old('sku', $product->sku ?? '') }}" required>
        @error('sku')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="price" class="form-label">Price</label>
        <input type="number" name="price" id="price" step="0.01" min="0"
               class="form-control @error('price') is-invalid @enderror"
               value="{{ old('price', isset($product) ? $product->price : '') }}" required>
        @error('price')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="stock_quantity" class="form-label">Stock Quantity</label>
        <input type="number" name="stock_quantity" id="stock_quantity" min="0"
               class="form-control @error('stock_quantity') is-invalid @enderror"
               value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" required>
        @error('stock_quantity')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="category_id" class="form-label">Category</label>
        <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
            <option value="">Select Category</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-6">
        <label for="image" class="form-label">Image</label>
        <input type="file" name="image" id="image"
               class="form-control @error('image') is-invalid @enderror" accept="image/*">
        @error('image')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @if (!empty($product?->image_url))
            <div class="mt-2">
                <span class="d-block mb-1">Current image:</span>
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                     class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
            </div>
        @endif
    </div>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea name="description" id="description" rows="4"
              class="form-control @error('description') is-invalid @enderror"
              placeholder="Optional description">{{ old('description', $product->description ?? '') }}</textarea>
    @error('description')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="d-flex justify-content-between">
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">
        {{ isset($product) ? 'Update Product' : 'Create Product' }}
    </button>
</div>