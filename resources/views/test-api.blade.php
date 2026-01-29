<!DOCTYPE html>
<html>
<head>
    <title>API Test Page</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(session('api_token'))
        <meta name="api-token" content="{{ session('api_token') }}">
    @endif
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <h1>API Test Page</h1>
    
    <div>
        <h2>Token Information</h2>
        <p>CSRF Token: <span id="csrf-token">{{ csrf_token() }}</span></p>
        <p>API Token: <span id="api-token">{{ session('api_token') ?? 'Not available' }}</span></p>
    </div>
    
    <div>
        <h2>API Tests</h2>
        <button onclick="testProducts()">Test Products API</button>
        <button onclick="testCart()">Test Cart API</button>
        <button onclick="testToken()">Test Token Retrieval</button>
    </div>
    
    <div>
        <h2>Results</h2>
        <pre id="results"></pre>
    </div>

    <script>
        function log(message) {
            const results = document.getElementById('results');
            results.textContent += message + '\n';
            console.log(message);
        }
        
        function getSanctumToken() {
            // Get token from API token meta tag first
            const apiTokenElement = document.querySelector('meta[name="api-token"]');
            if (apiTokenElement) {
                const token = apiTokenElement.getAttribute('content');
                log('Found API token: ' + (token ? 'YES' : 'NO'));
                return token;
            }
            
            // Fallback to CSRF token
            const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
            if (csrfTokenElement) {
                const token = csrfTokenElement.getAttribute('content');
                log('Using CSRF token as fallback: ' + (token ? 'YES' : 'NO'));
                return token;
            }
            
            log('No token found');
            return null;
        }
        
        async function testProducts() {
            log('Testing Products API...');
            try {
                const response = await axios.get('/api/products');
                log('Products API Response:');
                log(JSON.stringify(response.data, null, 2));
            } catch (error) {
                log('Products API Error:');
                log(error.message);
                if (error.response) {
                    log('Response data: ' + JSON.stringify(error.response.data));
                    log('Response status: ' + error.response.status);
                }
            }
        }
        
        async function testCart() {
            log('Testing Cart API...');
            try {
                const token = getSanctumToken();
                log('Token for cart test: ' + (token ? 'FOUND' : 'MISSING'));
                
                const response = await axios.post('/api/cart/add', {
                    product_id: 3,
                    name: 'Test Product',
                    price: 100,
                    image: 'test.jpg',
                    quantity: 1
                });
                
                log('Cart API Response:');
                log(JSON.stringify(response.data, null, 2));
            } catch (error) {
                log('Cart API Error:');
                log(error.message);
                if (error.response) {
                    log('Response data: ' + JSON.stringify(error.response.data));
                    log('Response status: ' + error.response.status);
                }
            }
        }
        
        function testToken() {
            log('Testing Token Retrieval...');
            const token = getSanctumToken();
            log('Token value: ' + (token || 'NULL'));
        }
        
        // Run initial tests
        document.addEventListener('DOMContentLoaded', function() {
            log('Page loaded. Ready to test APIs.');
            testToken();
        });
    </script>
</body>
</html>