@extends('layouts.customer')

@section('content')

<section class="max-w-4xl mx-auto px-6 py-12">
    <h1 class="text-3xl font-serif font-bold mb-8">Checkout</h1>
    
    <div class="bg-white p-8 rounded-xl shadow">
        <h2 class="text-xl font-semibold mb-6">Order Summary</h2>
        
        <div id="order-summary">
            <div class="space-y-4 mb-8">
                <div id="cart-items">Loading cart...</div>
                <div id="total-section" class="flex justify-between items-center font-bold text-lg pt-3">
                    <span>Total:</span>
                    <span id="total-amount">Rs. 0.00</span>
                </div>
            </div>
        </div>
        
        <div id="error-message" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6 hidden"></div>
        
        <form id="checkout-form">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 mb-2">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="w-full border rounded-lg px-4 py-2" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-2">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="w-full border rounded-lg px-4 py-2" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email" class="w-full border rounded-lg px-4 py-2" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-2">Phone</label>
                    <input type="tel" name="phone" id="phone" class="w-full border rounded-lg px-4 py-2" required>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2">Address</label>
                    <input type="text" name="address" id="address" class="w-full border rounded-lg px-4 py-2" required>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2">City</label>
                    <input type="text" name="city" id="city" class="w-full border rounded-lg px-4 py-2" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-2">State</label>
                    <input type="text" name="state" id="state" class="w-full border rounded-lg px-4 py-2" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-2">ZIP Code</label>
                    <input type="text" name="zip" id="zip" class="w-full border rounded-lg px-4 py-2" required>
                </div>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Payment Method</label>
                <select name="payment_method" id="payment_method" class="w-full border rounded-lg px-4 py-2" required>
                    <option value="">Select Payment Method</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                    <option value="bank_transfer">Bank Transfer</option>
                </select>
            </div>
            
            <button type="submit" id="place-order-btn" class="w-full bg-black text-white py-3 rounded-full hover:bg-gray-800">
                Place Order
            </button>
        </form>
        
        <div id="loading" class="hidden text-center py-4">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-amber-600"></div>
            <p class="mt-2 text-gray-600">Processing your order...</p>
        </div>
        
        @if(!session()->has('cart') || empty(session()->get('cart')))
            <p>Your cart is empty. <a href="{{ route('products') }}" class="text-amber-600 hover:text-amber-700 transition-colors">Continue shopping</a></p>
        @endif
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load cart data and populate the checkout form
        loadCartData();
        
        // Set up event listener for the checkout form
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            e.preventDefault();
            placeOrder();
        });
        
        // Pre-fill user data if logged in
        const user = @json(auth()->user() ? [
            'email' => auth()->user()->email,
            'name' => auth()->user()->name
        ] : null);
        
        if (user) {
            const [firstName, lastName] = user.name.split(' ');
            document.getElementById('first_name').value = firstName || '';
            document.getElementById('last_name').value = lastName || firstName || '';
            document.getElementById('email').value = user.email;
        }
    });
    
    async function loadCartData() {
        try {
            console.log('Loading cart data for checkout...');
            // Cart API requires Sanctum Bearer token (from global axios config)
            // Also needs withCredentials for session cookies
            const response = await axios.get('/api/cart', {
                withCredentials: true // Send cookies for session storage
            });
            
            const data = response.data;
            
            if (data.success) {
                if (Object.keys(data.cart).length === 0) {
                    document.getElementById('cart-items').innerHTML = '<p class="text-red-500">Your cart is empty.</p>';
                    document.getElementById('place-order-btn').disabled = true;
                    return;
                }
                
                let cartHtml = '';
                let total = 0;
                
                for (const productId in data.cart) {
                    const item = data.cart[productId];
                    const itemTotal = item.price * item.quantity;
                    total += itemTotal;
                    
                    cartHtml += `
                        <div class="flex justify-between items-center border-b pb-3">
                            <span>${item.name || 'Product'} x ${item.quantity}</span>
                            <span>Rs. ${itemTotal.toFixed(2)}</span>
                        </div>
                    `;
                }
                
                document.getElementById('cart-items').innerHTML = cartHtml;
                document.getElementById('total-amount').textContent = 'Rs. ' + total.toFixed(2);
            } else {
                showError('Failed to load cart data: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error loading cart:', error);
            if (error.response && error.response.status === 401) {
                showError('Authentication required. Please log in again.');
            } else {
                showError('Error loading cart: ' + error.message);
            }
        }
    }
    
    async function placeOrder() {
        const formData = new FormData(document.getElementById('checkout-form'));
        
        // Get cart items for the order
        try {
            console.log('Getting cart for order...');
            // Cart API requires Sanctum Bearer token (from global axios config)
            const cartResponse = await axios.get('/api/cart', {
                withCredentials: true // Send cookies for session storage
            });
            
            const cartData = cartResponse.data;
            
            if (!cartData.success || Object.keys(cartData.cart).length === 0) {
                showError('Your cart is empty. Cannot place order.');
                return;
            }
            
            // Prepare order data
            const orderData = {
                first_name: formData.get('first_name'),
                last_name: formData.get('last_name'),
                email: formData.get('email'),
                phone: formData.get('phone'),
                address: formData.get('address'),
                city: formData.get('city'),
                state: formData.get('state'),
                zip: formData.get('zip'),
                payment_method: formData.get('payment_method'),
                cart_items: []
            };
            
            // Format cart items
            for (const productId in cartData.cart) {
                orderData.cart_items.push({
                    product_id: parseInt(productId),
                    quantity: cartData.cart[productId].quantity
                });
            }
            
            // Show loading state
            document.getElementById('loading').classList.remove('hidden');
            document.getElementById('place-order-btn').disabled = true;
            hideError();
            
            // Send order to API (requires Sanctum Bearer token from global axios config)
            console.log('Sending order to API...');
            const response = await axios.post('/api/orders', orderData, {
                withCredentials: true
            });
            
            const result = response.data;
            
            if (result.success || response.status === 201) {
                // Clear cart after successful order (requires Sanctum Bearer token)
                await axios.delete('/api/cart/clear', {
                    withCredentials: true
                });
                
                // Redirect to order confirmation page
                window.location.href = '/order-confirmation';
            } else {
                document.getElementById('place-order-btn').disabled = false;
                showError(result.message || 'Failed to place order');
            }
        } catch (error) {
            console.error('Error placing order:', error);
            document.getElementById('place-order-btn').disabled = false;
            if (error.response && error.response.status === 401) {
                showError('Authentication required. Please log in again.');
            } else if (error.response && error.response.data && error.response.data.message) {
                showError(error.response.data.message);
            } else {
                showError('Error placing order: ' + error.message);
            }
        } finally {
            document.getElementById('loading').classList.add('hidden');
        }
    }
    
    // Sanctum Bearer token is handled globally via bootstrap.js axios defaults
    // All API calls automatically include: Authorization: Bearer <token>
    
    function showError(message) {
        const errorDiv = document.getElementById('error-message');
        errorDiv.textContent = message;
        errorDiv.classList.remove('hidden');
    }
    
    function hideError() {
        document.getElementById('error-message').classList.add('hidden');
    }
</script>

@endsection