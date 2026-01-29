@extends('layouts.customer')

@section('content')

<section class="max-w-4xl mx-auto px-6 py-12">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-serif font-bold mb-2">Order Confirmed!</h1>
        <p class="text-gray-600">Thank you for your order. We'll send you a confirmation email shortly.</p>
    </div>

    <div id="order-confirmation-container">
        <div id="loading" class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-10 w-10 border-b-2 border-amber-600"></div>
            <p class="mt-4 text-gray-600">Loading your order details...</p>
        </div>
        
        <div id="error-message" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6 hidden"></div>
        
        <div id="order-content" class="hidden">
            <div class="bg-white p-8 rounded-xl shadow mb-6">
                <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                
                <div id="order-items" class="space-y-4 mb-6">
                    <!-- Order items will be loaded here -->
                </div>

                <div class="flex justify-between items-center font-bold text-lg pt-3 border-t">
                    <span>Total Amount:</span>
                    <span id="total-amount" class="text-green-600">Rs. 0.00</span>
                </div>
            </div>

            <div class="bg-gray-50 p-6 rounded-xl mb-6">
                <h3 class="font-semibold mb-3">Delivery Information</h3>
                <div id="delivery-info" class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <!-- Delivery info will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <div id="actions" class="flex gap-4">
        @auth
            <a href="{{ route('orders.index') }}" class="flex-1 bg-black text-white text-center py-3 rounded-full hover:bg-gray-800">
                View My Orders
            </a>
        @else
            <a href="{{ route('login') }}" class="flex-1 bg-black text-white text-center py-3 rounded-full hover:bg-gray-800">
                Login to View Orders
            </a>
        @endauth
        <a href="{{ route('products') }}" class="flex-1 bg-gray-200 text-gray-800 text-center py-3 rounded-full hover:bg-gray-300">
            Continue Shopping
        </a>
    </div>
</section>

{{-- Axios loaded globally via bootstrap.js --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadLatestOrder();
    });
    
    async function loadLatestOrder() {
        try {
            // Get the user's latest order using global axios (already configured with Bearer token)
            const response = await axios.get('/api/orders', {
                params: {
                    per_page: 1  // Just get the latest order
                },
                withCredentials: true
            });
            
            const data = response.data;
            
            if (data.success && data.data && data.data.data && data.data.data.length > 0) {
                const latestOrder = data.data.data[0]; // Get the first (most recent) order
                
                // Display order information
                displayOrderInfo(latestOrder);
            } else {
                showError('No recent orders found.');
            }
        } catch (error) {
            console.error('Error loading order:', error);
            if (error.response && error.response.status === 401) {
                showError('Authentication required. Please log in again.');
            } else {
                showError('Error loading order details: ' + error.message);
            }
        }
    }
    
    function displayOrderInfo(order) {
        // Hide loading, show content
        document.getElementById('loading').classList.add('hidden');
        document.getElementById('order-content').classList.remove('hidden');
        
        // Display order items
        let orderItemsHtml = '';
        if (Array.isArray(order)) {
            // If we have multiple orders in the response
            order.forEach(ord => {
                orderItemsHtml += `
                    <div class="flex justify-between items-center border-b pb-3">
                        <div>
                            <p class="font-medium">${ord.product ? ord.product.name : 'N/A'}</p>
                            <p class="text-sm text-gray-600">Quantity: ${ord.quantity}</p>
                            <p class="text-xs text-gray-500">Order #${ord.id}</p>
                        </div>
                        <span class="font-semibold">Rs. ${parseFloat(ord.total_price).toFixed(2)}</span>
                    </div>
                `;
            });
        } else {
            // Single order object
            orderItemsHtml = `
                <div class="flex justify-between items-center border-b pb-3">
                    <div>
                        <p class="font-medium">${order.product ? order.product.name : 'N/A'}</p>
                        <p class="text-sm text-gray-600">Quantity: ${order.quantity}</p>
                        <p class="text-xs text-gray-500">Order #${order.id}</p>
                    </div>
                    <span class="font-semibold">Rs. ${parseFloat(order.total_price).toFixed(2)}</span>
                </div>
            `;
        }
        
        document.getElementById('order-items').innerHTML = orderItemsHtml;
        
        // Set total amount
        const total = Array.isArray(order) ? order.reduce((sum, ord) => sum + parseFloat(ord.total_price), 0) : parseFloat(order.total_price);
        document.getElementById('total-amount').textContent = 'Rs. ' + total.toFixed(2);
        
        // Display delivery info
        // Since this is from the API, we don't have the original customer info
        // So we'll just show a general message
        const deliveryInfoHtml = `
            <div>
                <span class="text-gray-600">Order Date:</span>
                <span class="font-medium ml-2">${new Date(order.created_at).toLocaleDateString()}</span>
            </div>
            <div>
                <span class="text-gray-600">Status:</span>
                <span class="font-medium ml-2 capitalize">${order.status}</span>
            </div>
            <div class="md:col-span-2">
                <span class="text-gray-600">Order ID:</span>
                <span class="font-medium ml-2">${order.id}</span>
            </div>
        `;
        
        document.getElementById('delivery-info').innerHTML = deliveryInfoHtml;
    }
    
    function showError(message) {
        document.getElementById('loading').classList.add('hidden');
        const errorDiv = document.getElementById('error-message');
        errorDiv.textContent = message;
        errorDiv.classList.remove('hidden');
    }
</script>

@endsection
