@extends('layouts.app')

@section('content')
<div id="pos-app" class="container-fluid">
    <div class="row">
        
        <div class="col-md-8">
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Item</h5>
                </div>
                <div class="card-body">
                    
                    <div class="mb-3">
                        <button class="btn btn-sm me-1 mb-1" 
                                :class="selectedCategory === null ? 'btn-primary' : 'btn-outline-primary'"
                                @click="selectedCategory = null">
                            All
                        </button>
                        <button v-for="category in categories" :key="category.id"
                                class="btn btn-sm me-1 mb-1"
                                :class="selectedCategory === category.id ? 'btn-primary' : 'btn-outline-primary'"
                                @click="selectedCategory = category.id">
                            @{{ category.name }}
                        </button>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-3 col-sm-4 col-6" v-for="product in filteredProducts" :key="product.id">
                            <div class="card h-100 shadow-sm product-card" @click="addToCart(product)" style="cursor: pointer;">
                                <img :src="product.image_url ? product.image_url : 'https://via.placeholder.com/150'" 
                                     class="card-img-top" alt="Product" style="height: 120px; object-fit: cover;">
                                
                                <div class="card-body p-2 text-center">
                                    <h6 class="card-title text-truncate mb-1" style="font-size: 0.9rem;">@{{ product.name }}</h6>
                                    <span class="badge bg-success mb-1">available (@{{ product.stock_quantity }})</span>
                                    <div class="fw-bold">$@{{ parseFloat(product.price).toFixed(2) }}</div>
                                </div>
                            </div>
                        </div>
                        <div v-if="filteredProducts.length === 0" class="col-12 text-center text-muted py-4">
                            No products found in this category.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Order</h5>
                    <div>
                        <a href="{{ route('orders.index') }}" class="btn btn-success btn-sm me-1">Show All Orders</a>
                        <button class="btn btn-danger btn-sm" @click="clearCart">Clear</button>
                    </div>
                </div>
                
                <div class="card-body p-0" style="overflow-y: auto; max-height: 500px;">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Price</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="cart.length === 0">
                                <td colspan="4" class="text-center text-muted py-4">No items in order</td>
                            </tr>
                            <tr v-for="(item, index) in cart" :key="item.id">
                                <td>@{{ item.name }}</td>
                                <td class="text-center">@{{ item.quantity }}</td>
                                <td class="text-end">$@{{ (item.price * item.quantity).toFixed(2) }}</td>
                                <td class="text-end">
                                    <button class="btn btn-danger btn-sm py-0 px-1" @click="removeFromCart(index)">x</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Gross Total</span>
                        <strong>$@{{ grossTotal.toFixed(2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Taxes (5%)</span>
                        <strong>$@{{ taxAmount.toFixed(2) }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="h5">Net Total</span>
                        <span class="h5 fw-bold">$@{{ netTotal.toFixed(2) }}</span>
                    </div>
                    
                    <button class="btn btn-success w-100 py-2" @click="pay" :disabled="cart.length === 0">
                        Pay
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.js"></script>
<script>
    const { createApp, ref, computed } = Vue;

    createApp({
        setup() {
            const products = ref(@json($products));
            const categories = ref(@json($categories));
            
            const cart = ref([]);
            const selectedCategory = ref(null);

            const filteredProducts = computed(() => {
                if (selectedCategory.value === null) {
                    return products.value;
                }
                return products.value.filter(p => p.category_id === selectedCategory.value);
            });

            const grossTotal = computed(() => {
                return cart.value.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            });

            const taxAmount = computed(() => {
                return grossTotal.value * 0.05;
            });

            const netTotal = computed(() => {
                return grossTotal.value + taxAmount.value;
            });

            const addToCart = (product) => {
                const existingItem = cart.value.find(item => item.id === product.id);
                if (existingItem) {
                    if(existingItem.quantity < product.stock_quantity) {
                        existingItem.quantity++;
                    } else {
                        alert('Not enough stock available');
                    }
                } else {
                    cart.value.push({
                        id: product.id,
                        name: product.name,
                        price: parseFloat(product.price),
                        quantity: 1
                    });
                }
            };

            const removeFromCart = (index) => {
                cart.value.splice(index, 1);
            };

            const clearCart = () => {
                if(confirm('Are you sure you want to clear the order?')) {
                    cart.value = [];
                }
            };

            const pay = async () => {
                if (cart.value.length === 0) return;

                try {
                    const response = await fetch("{{ route('pos.checkout') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ cart: cart.value })
                    });

                    const data = await response.json();

                    if (data.success) {
                        alert(data.message + ' Order ID: ' + data.order_id);
                        cart.value = [];
                        window.location.reload(); 
                    } else {
                        alert('Error: ' + data.message);
                    }
                } catch (error) {
                    console.error(error);
                    alert('An error occurred during checkout.');
                }
            };

            return {
                products,
                categories,
                cart,
                selectedCategory,
                filteredProducts,
                grossTotal,
                taxAmount,
                netTotal,
                addToCart,
                removeFromCart,
                clearCart,
                pay
            };
        }
    }).mount('#pos-app');
</script>
@endpush