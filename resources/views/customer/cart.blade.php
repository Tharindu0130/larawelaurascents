@extends('layouts.customer')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-12">

    <h1 class="text-3xl font-bold mb-8 text-center">Your Cart</h1>

    <div id="cart-container">
        <div id="loading" class="text-center py-8 hidden">
            <div class="inline-block animate-spin rounded-full h-10 w-10 border-b-2 border-amber-600"></div>
            <p class="mt-4 text-gray-600">Loading your cart...</p>
        </div>
        
        <div id="error-message" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6 hidden"></div>
        
        <div id="cart-content">
            <div id="cart-items" class="space-y-6">
                <!-- Cart items will be loaded here -->
            </div>

            {{-- Cart Total --}}
            <div id="cart-total" class="mt-10 text-right hidden">
                <p class="text-xl font-bold mb-4">
                    Total: Rs. <span id="total-amount">0.00</span>
                </p>

                <a
                    href="{{ route('checkout') }}"
                    id="checkout-button"
                    class="inline-block bg-black text-white px-8 py-3 rounded-full hover:bg-gray-800 transition disabled:opacity-50 disabled:cursor-not-allowed"
                    onclick="return validateCartBeforeCheckout()"
                >
                    Proceed to Checkout
                </a>
            </div>
            
            <div id="empty-cart" class="text-center text-gray-600 hidden">
                <p>Your cart is empty.</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadCart();
        
        // Refresh cart every 30 seconds to keep it updated
        setInterval(loadCart, 30000);
    });
    
    async function loadCart() {
        try {
            // Cart API requires Sanctum Bearer token (from global axios config in bootstrap.js)
            showLoading();
            
            console.log('Loading cart from API...');
            const response = await axios.get('/api/cart', {
                withCredentials: true // Send cookies for session storage
            });
            
            console.log('Cart API response:', response.data);
            const data = response.data;
            
            // Cart API returns: { cart: {...}, count: 5, total: 100.00 }
            if (data && (data.cart !== undefined || data.count !== undefined)) {
                displayCart(data);
            } else {
                showError('Failed to load cart: Invalid response format');
            }
        } catch (error) {
            console.error('Error loading cart:', error);
            console.error('Error response:', error.response);
            
            if (error.response) {
                if (error.response.status === 401) {
                    showError('Authentication required. Please log in again.');
                } else {
                    showError('Error loading cart: ' + (error.response.data?.message || error.message));
                }
            } else {
                showError('Error loading cart: ' + error.message);
            }
        }
    }
    
    function displayCart(data) {
        hideLoading();
        
        const cartItems = data.cart;
        const cartItemsContainer = document.getElementById('cart-items');
        
        if (Object.keys(cartItems).length === 0) {
            // Show empty cart
            document.getElementById('cart-items').classList.add('hidden');
            document.getElementById('cart-total').classList.add('hidden');
            document.getElementById('empty-cart').classList.remove('hidden');
            return;
        }
        
        // Show cart items
        document.getElementById('empty-cart').classList.add('hidden');
        document.getElementById('cart-items').classList.remove('hidden');
        
        let cartHtml = '';
        let total = 0;
        
        for (const productId in cartItems) {
            const item = cartItems[productId];
            const itemTotal = item.price * item.quantity;
            total += itemTotal;
            
            cartHtml += `
                <div class="bg-white rounded-2xl shadow p-6 flex flex-col md:flex-row gap-6 items-center" data-product-id="${productId}">
                    
                    <!-- Image -->
                    <img
                        src="${item.image || 'https://via.placeholder.com/120'}"
                        alt="${item.name || 'Product'}"
                        class="w-28 h-28 object-cover rounded-xl"
                        onerror="this.src='https://via.placeholder.com/120'"
                    >

                    <!-- Product Info -->
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold">${item.name || 'Product'}</h3>
                        <p class="text-gray-600">
                            Rs. ${parseFloat(item.price).toFixed(2)} × ${item.quantity}
                        </p>
                        <p class="font-bold mt-1">
                            Rs. ${itemTotal.toFixed(2)}
                        </p>
                    </div>

                    <!-- Quantity (AUTO UPDATE) -->
                    <input
                        type="number"
                        value="${item.quantity}"
                        min="1"
                        onchange="updateQuantity(${productId}, this.value)"
                        class="w-20 border rounded-lg px-3 py-2 text-center"
                    >

                    <!-- Remove -->
                    <button
                        onclick="removeItem(${productId})"
                        class="px-4 py-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition"
                    >
                        Remove
                    </button>

                </div>
            `;
        }
        
        cartItemsContainer.innerHTML = cartHtml;
        
        // Update total
        document.getElementById('total-amount').textContent = total.toFixed(2);
        document.getElementById('cart-total').classList.remove('hidden');
        
        // Update cart count in navbar
        updateCartCount(Object.keys(cartItems).length);
    }
    
    async function updateQuantity(productId, newQuantity) {
        try {
            // Cart API requires Sanctum Bearer token (from global axios config)
            const response = await axios.put(`/api/cart/update/${productId}`, {
                quantity: parseInt(newQuantity)
            }, {
                withCredentials: true // Send cookies for session storage
            });
            
            console.log('Update cart response:', response.data);
            
            if (response.data.success) {
                loadCart(); // Reload cart to update totals
            } else {
                showError(response.data.message || 'Failed to update quantity');
            }
        } catch (error) {
            console.error('Error updating quantity:', error);
            if (error.response) {
                if (error.response.status === 401) {
                    showError('Authentication required. Please log in again.');
                } else {
                    showError('Error updating quantity: ' + (error.response.data?.message || error.message));
                }
            } else {
                showError('Error updating quantity: ' + error.message);
            }
        }
    }
    
    async function removeItem(productId) {
        const result = await Swal.fire({
            title: 'Remove Item?',
            text: 'Are you sure you want to remove this item from your cart?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d97706',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, remove it',
            cancelButtonText: 'Cancel'
        });
        
        if (!result.isConfirmed) {
            return;
        }
        
        try {
            // Cart API requires Sanctum Bearer token (from global axios config)
            const response = await axios.delete(`/api/cart/remove/${productId}`, {
                withCredentials: true // Send cookies for session storage
            });
            
            console.log('Remove cart response:', response.data);
            
            if (response.data.success) {
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Removed!',
                    text: 'Item has been removed from your cart.',
                    confirmButtonColor: '#d97706',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                loadCart(); // Reload cart after removal
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.data.message || 'Failed to remove item',
                    confirmButtonColor: '#d97706'
                });
            }
        } catch (error) {
            console.error('Error removing item:', error);
            if (error.response) {
                if (error.response.status === 401) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Authentication Required',
                        text: 'Please log in again.',
                        confirmButtonColor: '#d97706'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error removing item: ' + (error.response.data?.message || error.message),
                        confirmButtonColor: '#d97706'
                    });
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error removing item: ' + error.message,
                    confirmButtonColor: '#d97706'
                });
            }
        }
    }
    
    function validateCartBeforeCheckout() {
        const checkoutBtn = document.getElementById('checkout-button');
        
        // Disable button temporarily to prevent double clicks
        checkoutBtn.disabled = true;
        
        // Re-enable after 2 seconds
        setTimeout(() => {
            checkoutBtn.disabled = false;
        }, 2000);
        
        // Return true to allow navigation
        return true;
    }
    
    function showLoading() {
        document.getElementById('loading').classList.remove('hidden');
        document.getElementById('cart-content').classList.add('hidden');
        hideError();
    }
    
    function hideLoading() {
        document.getElementById('loading').classList.add('hidden');
        document.getElementById('cart-content').classList.remove('hidden');
    }
    
    // Token is now handled globally in bootstrap.js via axios defaults
    // No need for getSanctumToken() function here
    
    function showError(message) {
        hideLoading();
        const errorDiv = document.getElementById('error-message');
        errorDiv.textContent = message;
        errorDiv.classList.remove('hidden');
    }
    
    function hideError() {
        document.getElementById('error-message').classList.add('hidden');
    }
    
    function updateCartCount(count) {
        // Update cart count in the navbar if it exists
        const cartBadge = document.querySelector('.relative.text-gray-800 span');
        if (cartBadge) {
            cartBadge.textContent = count;
            cartBadge.style.display = count > 0 ? 'flex' : 'none';
        }
    }
</script>

@endsection
