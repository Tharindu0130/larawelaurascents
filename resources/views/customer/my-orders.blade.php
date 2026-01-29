@extends('layouts.customer')

@section('content')

<section class="max-w-6xl mx-auto px-6 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-serif font-bold mb-2">My Orders</h1>
        <p class="text-gray-600">View and track your order history</p>
    </div>

    <div id="orders-container">
        <div id="loading" class="text-center py-8 hidden">
            <div class="inline-block animate-spin rounded-full h-10 w-10 border-b-2 border-amber-600"></div>
            <p class="mt-4 text-gray-600">Loading your orders...</p>
        </div>
        
        <div id="error-message" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6 hidden"></div>
        
        <div id="orders-content">
            <div id="orders-list" class="bg-white rounded-xl shadow overflow-hidden hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Order ID</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Product</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Quantity</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Total</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Date</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Action</th>
                            </tr>
                        </thead>
                        <tbody id="orders-table-body" class="divide-y">
                            <!-- Orders will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="pagination" class="mt-6">
                <!-- Pagination will be loaded here -->
            </div>
            
            <div id="empty-orders" class="bg-white rounded-xl shadow p-12 text-center hidden">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No orders yet</h3>
                <p class="text-gray-600 mb-6">Start shopping to see your orders here!</p>
                <a href="{{ route('products') }}" class="inline-block bg-black text-white px-6 py-3 rounded-full hover:bg-gray-800">
                    Browse Products
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Axios loaded globally via bootstrap.js --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadOrders();
    });
    
    async function loadOrders(page = 1) {
        try {
            showLoading();
            
            // Get the API token from the meta tag
            const tokenMeta = document.querySelector('meta[name="api-token"]');
            const token = tokenMeta ? tokenMeta.getAttribute('content') : null;
            
            if (!token) {
                throw new Error('No API token found. Please log in again.');
            }
            
            // Set the Authorization header with the Bearer token
            const response = await axios.get('/api/orders', {
                params: {
                    page: page
                },
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            
            const data = response.data;
            
            if (data.success && data.data) {
                displayOrders(data.data);
            } else {
                showError('Failed to load orders: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error loading orders:', error);
            console.error('Error response:', error.response);
            
            if (error.response && error.response.status === 401) {
                showError('Authentication required. Please log in again.');
                // Redirect to login after a delay
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            } else {
                showError('Error loading orders: ' + error.message);
            }
        }
    }
    
    function displayOrders(paginatedData) {
        hideLoading();
        
        const orders = paginatedData.data || [];
        const tbody = document.getElementById('orders-table-body');
        
        if (orders.length > 0) {
            // Show orders list
            document.getElementById('orders-list').classList.remove('hidden');
            document.getElementById('empty-orders').classList.add('hidden');
            
            let ordersHtml = '';
            
            orders.forEach(order => {
                const product = order.product || {};
                const user = order.user || {};
                
                ordersHtml += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm">#${order.id}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                ${product.image ? `<img src="${product.image}" 
                                     alt="${product.name || 'N/A'}" 
                                     class="w-12 h-12 object-cover rounded">` : ''}
                                <span class="font-medium">${product.name || 'N/A'}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-600">${order.quantity}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold">Rs. ${parseFloat(order.total_price).toFixed(2)}</span>
                        </td>
                        <td class="px-6 py-4">
                            ${getStatusBadge(order.status)}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">${new Date(order.created_at).toLocaleDateString()}</span>
                            <br>
                            <span class="text-xs text-gray-500">${new Date(order.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="/orders/${order.id}" 
                               class="text-amber-600 hover:text-amber-800 text-sm font-medium">
                                View Details
                            </a>
                        </td>
                    </tr>
                `;
            });
            
            tbody.innerHTML = ordersHtml;
            
            // Handle pagination
            renderPagination(paginatedData);
        } else {
            // Show empty state
            document.getElementById('orders-list').classList.add('hidden');
            document.getElementById('empty-orders').classList.remove('hidden');
            tbody.innerHTML = '';
        }
    }
    
    function getStatusBadge(status) {
        switch(status) {
            case 'completed':
                return '<span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Completed</span>';
            case 'processing':
                return '<span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Processing</span>';
            case 'cancelled':
                return '<span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Cancelled</span>';
            default:
                return '<span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>';
        }
    }
    
    function renderPagination(data) {
        const paginationContainer = document.getElementById('pagination');
        
        if (data.last_page <= 1) {
            paginationContainer.innerHTML = '';
            return;
        }
        
        let paginationHtml = `
            <div class="flex justify-center space-x-2">
        `;
        
        // Previous button
        if (data.current_page > 1) {
            paginationHtml += `
                <button onclick="loadOrders(${data.current_page - 1})" 
                        class="px-4 py-2 border rounded">
                    Previous
                </button>
            `;
        }
        
        // Page numbers
        for (let i = Math.max(1, data.current_page - 2); i <= Math.min(data.last_page, data.current_page + 2); i++) {
            paginationHtml += `
                <button onclick="loadOrders(${i})" 
                        class="px-4 py-2 border rounded ${i === data.current_page ? 'bg-amber-600 text-white' : 'hover:bg-gray-100'}">
                    ${i}
                </button>
            `;
        }
        
        // Next button
        if (data.current_page < data.last_page) {
            paginationHtml += `
                <button onclick="loadOrders(${data.current_page + 1})" 
                        class="px-4 py-2 border rounded">
                    Next
                </button>
            `;
        }
        
        paginationHtml += '</div>';
        paginationContainer.innerHTML = paginationHtml;
    }
    
    function showLoading() {
        document.getElementById('loading').classList.remove('hidden');
        document.getElementById('orders-list').classList.add('hidden');
        document.getElementById('empty-orders').classList.add('hidden');
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
