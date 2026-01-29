<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unified API Demo - Web & Mobile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h1 class="text-3xl font-bold mb-4">🔄 Unified API Demo</h1>
            <p class="text-gray-600 mb-4">
                This page demonstrates how the web app uses the same API endpoints as the mobile app.
                Both platforms now consume <code class="bg-gray-200 px-2 py-1 rounded">/api/*</code> routes.
            </p>
            
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                <h3 class="font-bold text-blue-900">✅ What's Changed:</h3>
                <ul class="list-disc ml-6 text-blue-800 mt-2">
                    <li>API responses now have consistent format: <code>{ success, message, data }</code></li>
                    <li>Comprehensive logging added to all API endpoints</li>
                    <li>ProductResource includes both 'user' and 'author' keys</li>
                    <li>Web app and mobile app use identical API routes</li>
                </ul>
            </div>
        </div>

        <!-- Auth Section -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">🔐 Authentication</h2>
            
            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <h3 class="font-semibold mb-2">Login</h3>
                    <input type="email" id="loginEmail" placeholder="Email" class="w-full px-4 py-2 border rounded mb-2">
                    <input type="password" id="loginPassword" placeholder="Password" class="w-full px-4 py-2 border rounded mb-2">
                    <button onclick="testLogin()" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Login (API)
                    </button>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-2">User Info</h3>
                    <div id="userInfo" class="bg-gray-50 p-4 rounded min-h-[120px]">
                        <p class="text-gray-500">Not logged in</p>
                    </div>
                    <button onclick="testLogout()" class="w-full bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 mt-2">
                        Logout (API)
                    </button>
                </div>
            </div>
        </div>

        <!-- Products Section -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">🛍️ Products (GET /api/products)</h2>
            
            <div class="mb-4">
                <button onclick="testGetProducts()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 mr-2">
                    Fetch Products
                </button>
                <button onclick="testSearchProducts()" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">
                    Search "perfume"
                </button>
            </div>
            
            <div id="productsResult" class="bg-gray-50 p-4 rounded overflow-auto max-h-96">
                <p class="text-gray-500">Click "Fetch Products" to load data...</p>
            </div>
        </div>

        <!-- Orders Section -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">📦 Orders (GET /api/orders)</h2>
            
            <button onclick="testGetOrders()" class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600 mb-4">
                Fetch My Orders
            </button>
            
            <div id="ordersResult" class="bg-gray-50 p-4 rounded overflow-auto max-h-64">
                <p class="text-gray-500">Login and click "Fetch My Orders" to load data...</p>
            </div>
        </div>

        <!-- API Logs Section -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-4">📝 API Call Logs</h2>
            <div id="apiLogs" class="bg-gray-900 text-green-400 p-4 rounded font-mono text-sm overflow-auto max-h-64">
                <p>// Open browser console (F12) to see detailed logs</p>
                <p>// Laravel logs: storage/logs/laravel.log</p>
            </div>
        </div>
    </div>

    <!-- Include API Client -->
    <script src="/js/api-client.js"></script>

    <script>
        // Test Login
        async function testLogin() {
            const email = document.getElementById('loginEmail').value || 'test@example.com';
            const password = document.getElementById('loginPassword').value || 'password';
            
            try {
                const result = await apiClient.login(email, password);
                
                if (result.success) {
                    document.getElementById('userInfo').innerHTML = `
                        <p class="font-semibold text-green-600">✅ Logged in</p>
                        <p><strong>Name:</strong> ${result.data.user.name}</p>
                        <p><strong>Email:</strong> ${result.data.user.email}</p>
                        <p><strong>Type:</strong> ${result.data.user.user_type}</p>
                    `;
                }
            } catch (error) {
                alert('Login failed: ' + error.message);
            }
        }

        // Test Logout
        async function testLogout() {
            try {
                await apiClient.logout();
                document.getElementById('userInfo').innerHTML = '<p class="text-gray-500">Not logged in</p>';
                alert('Logged out successfully');
            } catch (error) {
                alert('Logout failed: ' + error.message);
            }
        }

        // Test Get Products
        async function testGetProducts() {
            try {
                const result = await apiClient.getProducts();
                
                if (result.success && result.data) {
                    const productsHtml = result.data.map(product => `
                        <div class="border-b pb-2 mb-2">
                            <p class="font-semibold">${product.name}</p>
                            <p class="text-sm text-gray-600">Price: $${product.price} | Stock: ${product.stock}</p>
                            <p class="text-xs text-gray-500">Seller: ${product.user?.name || 'N/A'}</p>
                        </div>
                    `).join('');
                    
                    document.getElementById('productsResult').innerHTML = `
                        <p class="font-semibold mb-2">✅ Found ${result.data.length} products</p>
                        ${productsHtml}
                    `;
                }
            } catch (error) {
                document.getElementById('productsResult').innerHTML = `<p class="text-red-500">❌ ${error.message}</p>`;
            }
        }

        // Test Search Products
        async function testSearchProducts() {
            try {
                const result = await apiClient.searchProducts('perfume');
                
                if (result.success && result.data) {
                    const productsHtml = result.data.map(product => `
                        <div class="border-b pb-2 mb-2">
                            <p class="font-semibold">${product.name}</p>
                            <p class="text-sm text-gray-600">Price: $${product.price}</p>
                        </div>
                    `).join('');
                    
                    document.getElementById('productsResult').innerHTML = `
                        <p class="font-semibold mb-2">🔍 Search results: ${result.data.length} products</p>
                        ${productsHtml || '<p class="text-gray-500">No results found</p>'}
                    `;
                }
            } catch (error) {
                document.getElementById('productsResult').innerHTML = `<p class="text-red-500">❌ ${error.message}</p>`;
            }
        }

        // Test Get Orders
        async function testGetOrders() {
            try {
                const result = await apiClient.getOrders();
                
                if (result.success && result.data) {
                    const ordersHtml = result.data.data ? result.data.data.map(order => `
                        <div class="border-b pb-2 mb-2">
                            <p class="font-semibold">Order #${order.id}</p>
                            <p class="text-sm">Product: ${order.product?.name || 'N/A'}</p>
                            <p class="text-sm">Quantity: ${order.quantity} | Total: $${order.total_price}</p>
                            <p class="text-xs text-gray-500">Status: ${order.status}</p>
                        </div>
                    `).join('') : '<p class="text-gray-500">No orders found</p>';
                    
                    document.getElementById('ordersResult').innerHTML = `
                        <p class="font-semibold mb-2">📦 Your orders</p>
                        ${ordersHtml}
                    `;
                }
            } catch (error) {
                document.getElementById('ordersResult').innerHTML = `<p class="text-red-500">❌ ${error.message}</p>`;
            }
        }

        // Auto-load products on page load
        window.addEventListener('load', () => {
            // Set default values
            document.getElementById('loginEmail').value = 'test@example.com';
            document.getElementById('loginPassword').value = 'password';
            
            // Check if user is logged in
            if (apiClient.isAuthenticated()) {
                apiClient.getCurrentUser().then(user => {
                    document.getElementById('userInfo').innerHTML = `
                        <p class="font-semibold text-green-600">✅ Logged in</p>
                        <p><strong>Name:</strong> ${user.name}</p>
                        <p><strong>Email:</strong> ${user.email}</p>
                    `;
                }).catch(() => {
                    // Token expired
                    apiClient.clearToken();
                });
            }
        });
    </script>
</body>
</html>
