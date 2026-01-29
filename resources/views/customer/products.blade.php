@extends('layouts.customer')

@section('content')
<div class="w-full px-6 py-12">

    <h1 class="text-3xl font-bold mb-8 text-center">
        Our Collection
    </h1>

    <div id="products-container">
        <div id="loading" class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-10 w-10 border-b-2 border-amber-600"></div>
            <p class="mt-4 text-gray-600">Loading products...</p>
        </div>
        
        <div id="error-message" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6 hidden"></div>
        
        <div id="products-content" class="hidden">
            @if(session('success'))
                <div class="mb-6 text-green-600 text-center font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Products will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    console.log('🔵 Products page script loaded');
    
    // Ensure axios is available (from CDN or Vite)
    (function() {
        console.log('🔵 Initializing products loader...');
        
        if (typeof window.axios === 'undefined') {
            console.warn('⚠️ window.axios not found, waiting for CDN axios...');
            // Wait a bit for CDN axios to load
            setTimeout(() => {
                if (typeof axios !== 'undefined') {
                    window.axios = axios;
                    console.log('✅ CDN axios loaded');
                } else {
                    console.error('❌ Axios failed to load from CDN');
                    showError('Failed to load axios library. Please refresh the page.');
                    return;
                }
            }, 500);
        } else {
            console.log('✅ Using Vite axios instance');
        }
        
        // Configure axios with token if available
        function configureAxios() {
            const apiTokenElement = document.querySelector('meta[name="api-token"]');
            if (apiTokenElement) {
                const token = apiTokenElement.getAttribute('content');
                if (token) {
                    window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                    console.log('Axios configured with Bearer token');
                }
            }
        }
        
        // Wait for DOM and configure
        function startLoading() {
            console.log('🔵 startLoading() called');
            configureAxios();
            
            // Ensure axios is available before making request
            if (!window.axios && typeof axios !== 'undefined') {
                window.axios = axios;
            }
            
            if (!window.axios) {
                console.error('❌ Axios still not available');
                showError('Axios library not loaded. Please refresh the page.');
                return;
            }
            
            console.log('✅ Starting product loading...');
            
            // Small delay to ensure everything is ready
            setTimeout(() => {
                loadProducts();
            }, 200);
        }
        
        if (document.readyState === 'loading') {
            console.log('🔵 DOM still loading, waiting...');
            document.addEventListener('DOMContentLoaded', function() {
                console.log('✅ DOM loaded, starting...');
                startLoading();
            });
        } else {
            console.log('✅ DOM already loaded, starting...');
            startLoading();
        }
        
        // Fallback: if nothing happens after 15 seconds, show error
        setTimeout(() => {
            const loadingEl = document.getElementById('loading');
            if (loadingEl && !loadingEl.classList.contains('hidden')) {
                console.error('⏱️ Loading timeout after 15 seconds');
                showError('Products are taking too long to load. Please check the browser console (F12) for errors or refresh the page.');
            }
        }, 15000);
    })();
    
    async function loadProducts() {
        try {
            console.log('=== STARTING PRODUCT LOAD ===');
            showLoading();
            
            // Use window.axios (from CDN or Vite)
            const axiosInstance = window.axios;
            
            if (!axiosInstance) {
                console.error('Axios not found!');
                showError('Axios library not loaded. Please refresh the page.');
                return;
            }
            
            console.log('Axios found:', typeof axiosInstance);
            console.log('Making API request to /api/products');
            
            // Add timeout to prevent infinite loading
            const timeoutPromise = new Promise((_, reject) => 
                setTimeout(() => reject(new Error('Request timeout after 10 seconds')), 10000)
            );
            
            const apiPromise = axiosInstance.get('/api/products', {
                headers: {
                    'Accept': 'application/json'
                },
                timeout: 10000
            });
            
            const response = await Promise.race([apiPromise, timeoutPromise]);
                
            console.log('✅ API Response received!');
            console.log('Status:', response.status);
            console.log('Response data:', response.data);
            console.log('Response data type:', typeof response.data);
            console.log('Is array?', Array.isArray(response.data));
                
            const data = response.data;
                        
            // Laravel ResourceCollection wraps data in 'data' key
            let products = [];
            if (Array.isArray(data)) {
                // Direct array response
                products = data;
                console.log('✅ Products from direct array:', products.length);
            } else if (data && Array.isArray(data.data)) {
                // Wrapped in 'data' key (ResourceCollection format)
                products = data.data;
                console.log('✅ Products from data.data:', products.length);
            } else if (data && typeof data === 'object') {
                // Try to extract products from object
                console.error('❌ Unexpected response format:', data);
                console.error('Response keys:', Object.keys(data));
                
                // Try to find products in nested structure
                if (data.data && Array.isArray(data.data)) {
                    products = data.data;
                    console.log('✅ Found products in nested data.data');
                } else {
                    showError('Failed to load products: Unexpected response format. Response: ' + JSON.stringify(data).substring(0, 200));
                    return;
                }
            } else {
                console.error('❌ Invalid response format:', data);
                showError('Failed to load products: Invalid response format. Got: ' + typeof data);
                return;
            }
                
            console.log('✅ Products to display:', products.length, 'items');
            if (products.length > 0) {
                console.log('First product:', products[0]);
            }
            
            if (products.length === 0) {
                hideLoading();
                document.getElementById('products-grid').innerHTML = '<div class="col-span-full text-center py-12 text-gray-600"><p class="text-lg mb-2">No products available</p><p class="text-sm">Please add products via the admin panel.</p></div>';
                return;
            }
            
            displayProducts(products);
        } catch (error) {
            console.error('❌ ERROR LOADING PRODUCTS:', error);
            console.error('Error name:', error.name);
            console.error('Error message:', error.message);
            
            // Always hide loading on error
            hideLoading();
            
            if (error.response) {
                console.error('Error response status:', error.response.status);
                console.error('Error response data:', error.response.data);
                
                let errorMessage = 'Error loading products (Status: ' + error.response.status + ')';
                if (error.response.data) {
                    if (error.response.data.message) {
                        errorMessage += ': ' + error.response.data.message;
                    } else {
                        errorMessage += ': ' + JSON.stringify(error.response.data).substring(0, 200);
                    }
                }
                showError(errorMessage);
            } else if (error.request) {
                console.error('No response received. Request:', error.request);
                showError('Error: No response from server. The API endpoint may be down or unreachable.');
            } else if (error.message && error.message.includes('timeout')) {
                showError('Error: Request timed out. The server may be slow or unreachable.');
            } else {
                console.error('Error stack:', error.stack);
                showError('Error loading products: ' + (error.message || 'Unknown error'));
            }
        }
    }
    
    function displayProducts(products) {
        console.log('=== DISPLAYING PRODUCTS ===');
        console.log('Products count:', products.length);
        
        const productsGrid = document.getElementById('products-grid');
        
        if (!productsGrid) {
            console.error('Products grid element not found!');
            showError('Error: Products container not found on page.');
            return;
        }
        
        if (products.length === 0) {
            hideLoading();
            productsGrid.innerHTML = '<div class="col-span-full text-center py-12 text-gray-600"><p class="text-lg mb-2">No products available</p><p class="text-sm">Please add products via the admin panel.</p></div>';
            return;
        }
        
        let productsHtml = '';
        
        products.forEach((product, index) => {
            console.log(`Processing product ${index + 1}:`, product.id, product.name);
            
            // Escape single quotes and backslashes in product name for onclick handler
            const productNameEscaped = (product.name || 'Product')
                .replace(/\\/g, '\\\\')
                .replace(/'/g, "\\'")
                .replace(/"/g, '&quot;');
            
            const productImage = (product.image || 'https://via.placeholder.com/400x300')
                .replace(/'/g, "\\'");
            
            const productPrice = product.price ? parseFloat(product.price).toFixed(2) : '0.00';
            const productId = product.id || index;
            
            productsHtml += '<div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">' +
                '<img src="' + productImage + '" alt="' + productNameEscaped + '" class="w-full h-60 object-cover" onerror="this.src=\'https://via.placeholder.com/400x300\'">' +
                '<div class="p-6">' +
                    '<p class="text-xs uppercase text-amber-600 mb-1">Unisex</p>' +
                    '<h3 class="text-lg font-semibold mb-2">' + productNameEscaped + '</h3>' +
                    '<p class="text-gray-700 font-bold mb-4">Rs. ' + productPrice + '</p>' +
                    '<button onclick="addToCart(' + productId + ', \'' + productNameEscaped + '\', ' + (product.price || 0) + ', \'' + productImage + '\')" ' +
                        'class="w-full bg-amber-600 text-white py-2 rounded-full text-sm hover:bg-amber-700 transition">' +
                        'Add to Cart' +
                    '</button>' +
                '</div>' +
            '</div>';
        });
        
        console.log('Setting innerHTML with', products.length, 'products');
        productsGrid.innerHTML = productsHtml;
        
        // Hide loading and show content
        hideLoading();
        console.log('✅ Products displayed successfully!');
    }
    
    async function addToCart(productId, productName, productPrice, productImage) {
        try {
            console.log('🛒 Adding to cart:', productId, productName);
            
            // Use window.axios (from CDN or Vite)
            const axiosInstance = window.axios;
            
            if (!axiosInstance) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Axios is not available. Please refresh the page.',
                    confirmButtonColor: '#d97706'
                });
                return;
            }
            
            // Ensure CSRF token is set (required for web middleware)
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                axiosInstance.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
            }
            
            console.log('Making POST request to /api/cart/add');
            console.log('Request data:', {
                product_id: productId,
                name: productName,
                price: parseFloat(productPrice),
                image: productImage || '',
                quantity: 1
            });
            
            // Cart API requires Sanctum Bearer token (from global axios config)
            const response = await axiosInstance.post('/api/cart/add', {
                product_id: productId,
                name: productName,
                price: parseFloat(productPrice),
                image: productImage || '',
                quantity: 1
            }, {
                withCredentials: true // Send cookies for session storage
            });
            
            console.log('✅ Cart add response:', response.data);
            console.log('Cart items after add:', response.data.cart);
            console.log('Cart count:', response.data.count);
            
            if (response.data.success) {
                // Update cart count in navbar silently (no popup)
                if (response.data.count !== undefined) {
                    updateCartCount(response.data.count);
                }
                
                console.log('✅ Product added successfully. Cart now has', response.data.count, 'items');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: response.data.message || 'Failed to add product to cart',
                    confirmButtonColor: '#d97706'
                });
            }
        } catch (error) {
            console.error('❌ Error adding to cart:', error);
            console.error('Error response:', error.response);
            
            if (error.response) {
                if (error.response.status === 419) {
                    Swal.fire({
                        icon: 'error',
                        title: 'CSRF Error',
                        text: 'Please refresh the page and try again.',
                        confirmButtonColor: '#d97706'
                    });
                } else if (error.response.status === 401) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Authentication Required',
                        text: 'Please log in to add items to cart.',
                        confirmButtonColor: '#d97706'
                    });
                } else if (error.response.data && error.response.data.message) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.response.data.message,
                        confirmButtonColor: '#d97706'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to add product to cart. Please try again.',
                        confirmButtonColor: '#d97706'
                    });
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to add product to cart: ' + error.message,
                    confirmButtonColor: '#d97706'
                });
            }
        }
    }
    
    function showLoading() {
        const loadingEl = document.getElementById('loading');
        const contentEl = document.getElementById('products-content');
        if (loadingEl) loadingEl.classList.remove('hidden');
        if (contentEl) contentEl.classList.add('hidden');
        hideError();
    }
    
    function hideLoading() {
        const loadingEl = document.getElementById('loading');
        const contentEl = document.getElementById('products-content');
        if (loadingEl) loadingEl.classList.add('hidden');
        if (contentEl) contentEl.classList.remove('hidden');
        console.log('Loading hidden, content shown');
    }
    
    function getSanctumToken() {
        // Get token from API token meta tag first
        const apiTokenElement = document.querySelector('meta[name="api-token"]');
        if (apiTokenElement) {
            return apiTokenElement.getAttribute('content');
        }
        
        // Fallback to CSRF token
        const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
        return csrfTokenElement ? csrfTokenElement.getAttribute('content') : null;
    }
    
    function updateCartCount(count) {
        console.log('Updating cart count to:', count);
        // Update cart count in the navbar - find the cart badge
        const cartLink = document.querySelector('a[href*="cart"]');
        if (cartLink) {
            let cartBadge = cartLink.querySelector('span');
            if (!cartBadge && count > 0) {
                // Create badge if it doesn't exist
                cartBadge = document.createElement('span');
                cartBadge.className = 'absolute -top-2 -right-2 bg-amber-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center';
                cartLink.appendChild(cartBadge);
            }
            if (cartBadge) {
                cartBadge.textContent = count;
                cartBadge.style.display = count > 0 ? 'flex' : 'none';
                console.log('✅ Cart count updated in navbar');
            }
        } else {
            console.warn('Cart link not found in navbar');
        }
    }
    
    function showError(message) {
        console.error('SHOWING ERROR:', message);
        hideLoading();
        const errorDiv = document.getElementById('error-message');
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.classList.remove('hidden');
            errorDiv.style.display = 'block';
            console.log('Error message displayed');
        } else {
            console.error('Error div not found!');
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
                confirmButtonColor: '#d97706'
            });
        }
    }
    
    function hideError() {
        const errorDiv = document.getElementById('error-message');
        if (errorDiv) {
            errorDiv.classList.add('hidden');
            errorDiv.style.display = 'none';
        }
    }
</script>

@endsection
