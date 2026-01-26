@extends('layouts.customer')

@section('content')

<section class="max-w-4xl mx-auto px-6 py-12">
    <div class="mb-6">
        <a href="{{ route('orders.index') }}" class="inline-flex items-center text-amber-600 hover:text-amber-800 mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to My Orders
        </a>
        <h1 class="text-3xl font-serif font-bold">Order Details</h1>
    </div>

    <div id="order-details-container">
        <div id="loading" class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-10 w-10 border-b-2 border-amber-600"></div>
            <p class="mt-4 text-gray-600">Loading order details...</p>
        </div>
        
        <div id="error-message" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6 hidden"></div>
        
        <div id="order-content" class="hidden">
            <div class="bg-white rounded-xl shadow overflow-hidden mb-6">
                <div class="p-6 border-b bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Order ID</p>
                            <p id="order-id" class="font-mono font-semibold"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Order Date</p>
                            <p id="order-date" class="font-semibold"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Status</p>
                            <span id="order-status"></span>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <h2 class="text-lg font-semibold mb-4">Product Details</h2>
                    
                    <div id="product-details" class="flex gap-6 pb-6 border-b">
                        <!-- Product details will be loaded here -->
                    </div>

                    <div class="pt-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Subtotal</span>
                            <span id="subtotal">Rs. 0.00</span>
                        </div>
                        <div class="flex justify-between items-center font-bold text-lg pt-3 border-t">
                            <span>Total Amount</span>
                            <span id="total-amount" class="text-green-600">Rs. 0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <h3 class="font-semibold text-blue-900 mb-2">Order Status Information</h3>
                <p id="status-info" class="text-sm text-blue-800"></p>
            </div>
        </div>
    </div>
</section>

{{-- Axios loaded globally via bootstrap.js --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get order ID from the URL parameter
        const orderId = getOrderIdFromUrl();
        if (orderId) {
            loadOrderDetails(orderId);
        } else {
            showError('Order ID not found in URL');
        }
    });
    
    function getOrderIdFromUrl() {
        // Extract order ID from URL path (e.g., /orders/123 -> 123)
        const pathParts = window.location.pathname.split('/');
        const ordersIndex = pathParts.indexOf('orders');
        if (ordersIndex !== -1 && pathParts[ordersIndex + 1]) {
            return pathParts[ordersIndex + 1];
        }
        return null;
    }
    
    async function loadOrderDetails(orderId) {
        try {
            showLoading();
            
            // Use global axios from bootstrap.js (already configured with Bearer token)
            const response = await axios.get(`/api/orders/${orderId}`, {
                withCredentials: true
            });
            
            const data = response.data;
            
            if (data.success && data.data) {
                displayOrderDetails(data.data);
            } else {
                showError('Failed to load order details: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error loading order details:', error);
            if (error.response && error.response.status === 401) {
                showError('Authentication required. Please log in again.');
            } else if (error.response && error.response.status === 403) {
                showError('Unauthorized access to this order.');
            } else if (error.response && error.response.status === 404) {
                showError('Order not found.');
            } else {
                showError('Error loading order details: ' + error.message);
            }
        }
    }
    
    function displayOrderDetails(order) {
        hideLoading();
        
        // Display order information
        document.getElementById('order-id').textContent = '#' + order.id;
        document.getElementById('order-date').textContent = formatDate(order.created_at);
        document.getElementById('order-status').outerHTML = getStatusBadge(order.status);
        
        // Display product details
        const product = order.product || {};
        
        const productHtml = `
            <img src="${product.image || '/images/no-image.jpg'}" 
                 alt="${product.name || 'Product Image'}" 
                 class="w-32 h-32 object-cover rounded-lg">
            
            <div class="flex-1">
                <h3 class="text-xl font-semibold mb-2">${product.name || 'N/A'}</h3>
                
                ${product.description ? `<p class="text-gray-600 text-sm mb-3">${truncateText(product.description, 150)}</p>` : ''}

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Unit Price</p>
                        <p class="font-semibold">Rs. ${parseFloat(product.price || 0).toFixed(2)}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Quantity</p>
                        <p class="font-semibold">${order.quantity}</p>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('product-details').innerHTML = productHtml;
        
        // Display pricing information
        const totalPrice = parseFloat(order.total_price);
        document.getElementById('subtotal').textContent = `Rs. ${totalPrice.toFixed(2)}`;
        document.getElementById('total-amount').textContent = `Rs. ${totalPrice.toFixed(2)}`;
        
        // Display status information
        document.getElementById('status-info').textContent = getStatusInfo(order.status);
        
        // Show the content
        document.getElementById('order-content').classList.remove('hidden');
    }
    
    function getStatusBadge(status) {
        let className, text;
        switch(status) {
            case 'completed':
                className = 'inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800';
                text = 'Completed';
                break;
            case 'processing':
                className = 'inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800';
                text = 'Processing';
                break;
            case 'cancelled':
                className = 'inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800';
                text = 'Cancelled';
                break;
            default:
                className = 'inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800';
                text = 'Pending';
                break;
        }
        
        return `<span class="${className}">${text}</span>`;
    }
    
    function getStatusInfo(status) {
        switch(status) {
            case 'completed':
                return 'Your order has been completed and delivered. Thank you for your purchase!';
            case 'processing':
                return 'Your order is currently being processed and will be shipped soon.';
            case 'cancelled':
                return 'This order has been cancelled. If you have questions, please contact our support team.';
            default:
                return 'Your order has been received and is awaiting processing. We\'ll notify you once it\'s being prepared.';
        }
    }
    
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            hour12: true
        });
    }
    
    function truncateText(text, maxLength) {
        if (text.length <= maxLength) return text;
        return text.substr(0, maxLength) + '...';
    }
    
    function showLoading() {
        document.getElementById('loading').classList.remove('hidden');
        document.getElementById('order-content').classList.add('hidden');
        hideError();
    }
    
    function hideLoading() {
        document.getElementById('loading').classList.add('hidden');
    }
    
    function showError(message) {
        hideLoading();
        const errorDiv = document.getElementById('error-message');
        errorDiv.textContent = message;
        errorDiv.classList.remove('hidden');
    }
    
    function hideError() {
        document.getElementById('error-message').classList.add('hidden');
    }
</script>

@endsection
