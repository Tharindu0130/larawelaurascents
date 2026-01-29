/**
 * API Client for Web Application
 * 
 * This client uses the same API endpoints as the mobile app
 * Ensures both web and mobile use unified API routes
 */

class ApiClient {
    constructor() {
        this.baseUrl = '/api';
        this.token = this.getToken();
    }

    /**
     * Get auth token from localStorage
     */
    getToken() {
        return localStorage.getItem('auth_token');
    }

    /**
     * Save auth token to localStorage
     */
    setToken(token) {
        this.token = token;
        localStorage.setItem('auth_token', token);
    }

    /**
     * Clear auth token
     */
    clearToken() {
        this.token = null;
        localStorage.removeItem('auth_token');
    }

    /**
     * Get headers for requests
     */
    getHeaders(authenticated = false) {
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest', // Laravel CSRF
        };

        if (authenticated && this.token) {
            headers['Authorization'] = `Bearer ${this.token}`;
        }

        return headers;
    }

    /**
     * Handle API response
     */
    async handleResponse(response) {
        const data = await response.json();
        
        if (!response.ok) {
            console.error('❌ API Error:', data);
            throw new Error(data.message || 'API request failed');
        }
        
        return data;
    }

    // ============================================
    // AUTH METHODS
    // ============================================

    /**
     * Register new user
     */
    async register(name, email, password) {
        console.log('📝 API: Registering user', email);
        
        try {
            const response = await fetch(`${this.baseUrl}/register`, {
                method: 'POST',
                headers: this.getHeaders(),
                body: JSON.stringify({ name, email, password })
            });

            const data = await this.handleResponse(response);
            
            if (data.success && data.data.token) {
                this.setToken(data.data.token);
                console.log('✅ Registration successful');
            }
            
            return data;
        } catch (error) {
            console.error('❌ Registration failed:', error);
            throw error;
        }
    }

    /**
     * Login user
     */
    async login(email, password) {
        console.log('🔐 API: Logging in', email);
        
        try {
            const response = await fetch(`${this.baseUrl}/login`, {
                method: 'POST',
                headers: this.getHeaders(),
                body: JSON.stringify({ 
                    email, 
                    password,
                    device_name: 'Web Browser'
                })
            });

            const data = await this.handleResponse(response);
            
            if (data.success && data.data.token) {
                this.setToken(data.data.token);
                console.log('✅ Login successful');
            }
            
            return data;
        } catch (error) {
            console.error('❌ Login failed:', error);
            throw error;
        }
    }

    /**
     * Logout user
     */
    async logout() {
        console.log('🚪 API: Logging out');
        
        try {
            const response = await fetch(`${this.baseUrl}/logout`, {
                method: 'POST',
                headers: this.getHeaders(true)
            });

            const data = await this.handleResponse(response);
            this.clearToken();
            console.log('✅ Logout successful');
            
            return data;
        } catch (error) {
            // Clear token even if request fails
            this.clearToken();
            console.error('❌ Logout failed:', error);
            throw error;
        }
    }

    /**
     * Get current user
     */
    async getCurrentUser() {
        console.log('👤 API: Fetching current user');
        
        try {
            const response = await fetch(`${this.baseUrl}/user`, {
                method: 'GET',
                headers: this.getHeaders(true)
            });

            const data = await this.handleResponse(response);
            console.log('✅ User fetched');
            
            return data;
        } catch (error) {
            console.error('❌ Failed to fetch user:', error);
            throw error;
        }
    }

    // ============================================
    // PRODUCT METHODS
    // ============================================

    /**
     * Get all products
     */
    async getProducts(limit = 50, page = 1) {
        console.log('🛍️ API: Fetching products');
        
        try {
            const response = await fetch(`${this.baseUrl}/products?limit=${limit}&page=${page}`, {
                method: 'GET',
                headers: this.getHeaders()
            });

            const data = await this.handleResponse(response);
            console.log('✅ Products fetched:', data.data?.length || 0);
            
            return data;
        } catch (error) {
            console.error('❌ Failed to fetch products:', error);
            throw error;
        }
    }

    /**
     * Search products
     */
    async searchProducts(query) {
        console.log('🔎 API: Searching products', query);
        
        try {
            const response = await fetch(`${this.baseUrl}/products/search?query=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: this.getHeaders()
            });

            const data = await this.handleResponse(response);
            console.log('✅ Search completed:', data.data?.length || 0);
            
            return data;
        } catch (error) {
            console.error('❌ Search failed:', error);
            throw error;
        }
    }

    /**
     * Get single product
     */
    async getProduct(id) {
        console.log('🔍 API: Fetching product', id);
        
        try {
            const response = await fetch(`${this.baseUrl}/products/${id}`, {
                method: 'GET',
                headers: this.getHeaders()
            });

            const data = await this.handleResponse(response);
            console.log('✅ Product fetched');
            
            return data;
        } catch (error) {
            console.error('❌ Failed to fetch product:', error);
            throw error;
        }
    }

    // ============================================
    // ORDER METHODS
    // ============================================

    /**
     * Get user orders
     */
    async getOrders() {
        console.log('📦 API: Fetching orders');
        
        try {
            const response = await fetch(`${this.baseUrl}/orders`, {
                method: 'GET',
                headers: this.getHeaders(true)
            });

            const data = await this.handleResponse(response);
            console.log('✅ Orders fetched');
            
            return data;
        } catch (error) {
            console.error('❌ Failed to fetch orders:', error);
            throw error;
        }
    }

    /**
     * Create order
     */
    async createOrder(orderData) {
        console.log('🛒 API: Creating order');
        
        try {
            const response = await fetch(`${this.baseUrl}/orders`, {
                method: 'POST',
                headers: this.getHeaders(true),
                body: JSON.stringify(orderData)
            });

            const data = await this.handleResponse(response);
            console.log('✅ Order created');
            
            return data;
        } catch (error) {
            console.error('❌ Failed to create order:', error);
            throw error;
        }
    }

    /**
     * Get single order
     */
    async getOrder(id) {
        console.log('📋 API: Fetching order', id);
        
        try {
            const response = await fetch(`${this.baseUrl}/orders/${id}`, {
                method: 'GET',
                headers: this.getHeaders(true)
            });

            const data = await this.handleResponse(response);
            console.log('✅ Order fetched');
            
            return data;
        } catch (error) {
            console.error('❌ Failed to fetch order:', error);
            throw error;
        }
    }

    // ============================================
    // CART METHODS
    // ============================================

    /**
     * Get cart
     */
    async getCart() {
        console.log('🛒 API: Fetching cart');
        
        try {
            const response = await fetch(`${this.baseUrl}/cart`, {
                method: 'GET',
                headers: this.getHeaders(true)
            });

            const data = await this.handleResponse(response);
            console.log('✅ Cart fetched');
            
            return data;
        } catch (error) {
            console.error('❌ Failed to fetch cart:', error);
            throw error;
        }
    }

    /**
     * Add to cart
     */
    async addToCart(product, quantity = 1) {
        console.log('➕ API: Adding to cart', product.id);
        
        try {
            const response = await fetch(`${this.baseUrl}/cart/add`, {
                method: 'POST',
                headers: this.getHeaders(true),
                body: JSON.stringify({
                    product_id: product.id,
                    name: product.name,
                    price: product.price,
                    image: product.image,
                    quantity: quantity
                })
            });

            const data = await this.handleResponse(response);
            console.log('✅ Added to cart');
            
            return data;
        } catch (error) {
            console.error('❌ Failed to add to cart:', error);
            throw error;
        }
    }

    // ============================================
    // UTILITY METHODS
    // ============================================

    /**
     * Check if user is authenticated
     */
    isAuthenticated() {
        return !!this.token;
    }

    /**
     * Ping backend to check connectivity
     */
    async ping() {
        console.log('📡 API: Pinging backend');
        
        try {
            const response = await fetch(`${this.baseUrl}/ping`, {
                method: 'GET',
                headers: this.getHeaders()
            });

            const data = await this.handleResponse(response);
            console.log('✅ Ping successful');
            
            return data;
        } catch (error) {
            console.error('❌ Ping failed:', error);
            throw error;
        }
    }
}

// Create and export singleton instance
const apiClient = new ApiClient();

// Make it available globally
window.apiClient = apiClient;
