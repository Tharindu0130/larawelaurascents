<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Aura Scents</title>

    {{-- CSRF Token for AJAX requests --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Tailwind / App CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Add script for dynamic cart count -->
    <script>
        function updateCartCount() {
            fetch('{{ route("cart.count") }}')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('cart-count-badge');
                    if (badge) {
                        badge.textContent = data.count;
                        badge.style.display = data.count > 0 ? 'flex' : 'none';
                    }
                })
                .catch(error => console.error('Error fetching cart count:', error));
        }
        
        // Update cart count when page loads
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });
    </script>
</head>
<body class="bg-gray-50">

    {{-- NAVBAR --}}
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-indigo-600">
                Aura Scents
            </a>

            <div class="space-x-6">
                <a href="/" class="hover:text-indigo-600">Home</a>
                <a href="/products" class="hover:text-indigo-600">Products</a>
                <a href="/about" class="hover:text-indigo-600">About</a>
                <a href="/contact" class="hover:text-indigo-600">Contact</a>

                <!-- Cart Icon with Badge -->
                <div class="relative inline-block">
                    <a href="{{ route('cart.show') }}" class="hover:text-indigo-600 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span id="cart-count-badge" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" style="{{ count(session()->get('cart', [])) > 0 ? 'display:flex' : 'display:none' }}">
                            {{ count(session()->get('cart', [])) }}
                        </span>
                    </a>
                </div>

                @auth
                    <a href="/customer/dashboard" class="text-indigo-600">Dashboard</a>
                @else
                    <a href="/login" class="text-indigo-600">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- PAGE CONTENT --}}
    <main class="max-w-7xl mx-auto px-6 py-10">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-gray-800 text-white mt-20">
        <div class="max-w-7xl mx-auto px-6 py-8 text-center">
            <p>© {{ date('Y') }} Aura Scents. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>