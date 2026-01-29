<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Aura Scents</title>

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- API Token --}}
    @if(session('api_token'))
        <meta name="api-token" content="{{ session('api_token') }}">
    @endif

    {{-- App CSS / JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- SweetAlert2 for beautiful popups --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Alpine.js for interactive components --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    {{-- Custom styles section --}}
    @stack('styles')
</head>

<body class="bg-gray-50">

@php
    // Calculate cart count safely from session
    $cartCount = collect(session('cart', []))->sum('quantity');
@endphp

{{-- NAVBAR --}}
<nav class="bg-white border-b border-gray-100 h-20 lg:h-16" x-data="{ mobileMenuOpen: false }">
    <div class="w-full px-6 h-full flex justify-between items-center">
        
        {{-- Mobile: Hamburger Icon (Left) --}}
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-gray-800 hover:text-amber-600 focus:outline-none">
            <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg x-show="mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- Logo (Desktop: Left, Mobile: Center) --}}
        <a href="{{ route('home') }}" class="flex items-center lg:flex-none absolute left-1/2 transform -translate-x-1/2 lg:relative lg:left-auto lg:transform-none">
            <img src="{{ asset('images/logo-navbar.png') }}" alt="Aura Scents" class="h-14 lg:h-10 w-auto object-contain brightness-110">
        </a>

        {{-- Desktop Menu (Hidden on Mobile) --}}
        <div class="hidden lg:flex space-x-8 items-center">
            <a href="{{ route('home') }}" class="text-gray-800 font-medium hover:text-gray-600 transition-colors duration-200 {{ request()->routeIs('home') ? 'border-b-2 border-amber-600 pb-1' : '' }}">Home</a>
            <a href="{{ route('products') }}" class="text-gray-800 font-medium hover:text-gray-600 transition-colors duration-200 {{ request()->routeIs('products') ? 'border-b-2 border-amber-600 pb-1' : '' }}">Products</a>
            <a href="{{ route('about') }}" class="text-gray-800 font-medium hover:text-gray-600 transition-colors duration-200 {{ request()->routeIs('about') ? 'border-b-2 border-amber-600 pb-1' : '' }}">About</a>
            <a href="{{ route('contact') }}" class="text-gray-800 font-medium hover:text-gray-600 transition-colors duration-200 {{ request()->routeIs('contact') ? 'border-b-2 border-amber-600 pb-1' : '' }}">Contact</a>

            {{-- CART ICON --}}
            <a href="{{ route('cart.show') }}" class="relative text-gray-800 hover:text-amber-600 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-6 w-6"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.5"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4
                             M7 13L5.4 5
                             M7 13l-2.293 2.293
                             c-.63.63-.184 1.707.707 1.707H17
                             m0 0a2 2 0 100 4
                             2 2 0 000-4
                             zm-8 2a2 2 0 11-4 0
                             2 2 0 014 0z" />
                </svg>

                @if($cartCount > 0)
                    <span
                        class="absolute -top-2 -right-2 bg-amber-600 text-white text-xs
                               rounded-full h-5 w-5 flex items-center justify-center">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>

            {{-- AUTH LINKS --}}
            @auth
                {{-- Profile Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 text-gray-800 hover:text-amber-600 focus:outline-none transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                         style="display: none;">
                        
                        <div class="p-4 border-b border-gray-200">
                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <div class="py-1">
                            {{-- Jetstream Profile Page --}}
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Profile
                            </a>
                            
                            {{-- Dashboard (if customer) --}}
                            @if(Auth::user()->user_type === 'customer')
                                <a href="{{ route('customer.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Dashboard
                                </a>
                            @endif

                            {{-- Admin Dashboard (if admin) --}}
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Admin Dashboard
                                </a>
                            @endif
                            
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-gray-800 font-medium hover:text-amber-600 transition-colors duration-200">
                    Login
                </a>
            @endauth
        </div>

        {{-- Mobile: Cart Icon (Right) --}}
        <a href="{{ route('cart.show') }}" class="lg:hidden relative text-gray-800 hover:text-amber-600 transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-8 w-8"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.5"
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4
                         M7 13L5.4 5
                         M7 13l-2.293 2.293
                         c-.63.63-.184 1.707.707 1.707H17
                         m0 0a2 2 0 100 4
                         2 2 0 000-4
                         zm-8 2a2 2 0 11-4 0
                         2 2 0 014 0z" />
            </svg>

            @if($cartCount > 0)
                <span
                    class="absolute -top-2 -right-2 bg-amber-600 text-white text-xs
                           rounded-full h-6 w-6 flex items-center justify-center font-semibold">
                    {{ $cartCount }}
                </span>
            @endif
        </a>
    </div>

    {{-- Mobile Menu (Full-screen Overlay from Top) --}}
    <div x-show="mobileMenuOpen" 
         @click.away="mobileMenuOpen = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-full"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-full"
         class="lg:hidden fixed inset-0 z-50 bg-gradient-to-b from-amber-50 to-white overflow-y-auto"
         style="display: none;">
        
        {{-- Close Button --}}
        <div class="flex justify-end p-8">
            <button @click="mobileMenuOpen = false" class="text-gray-800 hover:text-amber-600 bg-white rounded-full p-3 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Menu Content --}}
        <div class="px-10 py-8 space-y-8">
            <a href="{{ route('home') }}" class="block text-gray-800 text-3xl font-bold hover:text-amber-600 py-5 border-b-2 border-gray-300 transition-colors {{ request()->routeIs('home') ? 'text-amber-600' : '' }}">
                Home
            </a>
            <a href="{{ route('products') }}" class="block text-gray-800 text-3xl font-bold hover:text-amber-600 py-5 border-b-2 border-gray-300 transition-colors {{ request()->routeIs('products') ? 'text-amber-600' : '' }}">
                Products
            </a>
            <a href="{{ route('about') }}" class="block text-gray-800 text-3xl font-bold hover:text-amber-600 py-5 border-b-2 border-gray-300 transition-colors {{ request()->routeIs('about') ? 'text-amber-600' : '' }}">
                About
            </a>
            <a href="{{ route('contact') }}" class="block text-gray-800 text-3xl font-bold hover:text-amber-600 py-5 border-b-2 border-gray-300 transition-colors {{ request()->routeIs('contact') ? 'text-amber-600' : '' }}">
                Contact
            </a>
            
            {{-- User Section --}}
            <div class="pt-8 border-t-4 border-amber-300">
                @auth
                    <div class="space-y-6">
                        <div class="bg-white rounded-xl p-6 shadow-lg">
                            <p class="text-xl font-bold text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-base text-gray-500 mt-1">{{ Auth::user()->email }}</p>
                        </div>
                        
                        <a href="{{ route('profile.show') }}" class="block text-gray-800 text-2xl font-semibold hover:text-amber-600 py-4 transition-colors">
                            👤 Profile
                        </a>
                        
                        @if(Auth::user()->user_type === 'customer')
                            <a href="{{ route('customer.dashboard') }}" class="block text-gray-800 text-2xl font-semibold hover:text-amber-600 py-4 transition-colors">
                                📊 Dashboard
                            </a>
                        @endif

                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block text-gray-800 text-2xl font-semibold hover:text-amber-600 py-4 transition-colors">
                                🔧 Admin Dashboard
                            </a>
                        @endif
                        
                        <form action="{{ route('logout') }}" method="POST" class="pt-4">
                            @csrf
                            <button type="submit" class="w-full text-left text-red-600 hover:text-red-700 text-2xl font-bold py-4 transition-colors">
                                🚪 Logout
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="block bg-amber-600 text-white text-center text-2xl font-bold py-6 rounded-xl hover:bg-amber-700 transition-colors shadow-xl">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- PAGE CONTENT --}}
<main class="w-full px-0 py-0">
    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="bg-gray-800 text-white mt-20">
    <div class="w-full px-6 py-8 text-center">
        <p>© {{ date('Y') }} Aura Scents. All rights reserved.</p>
    </div>
</footer>

    
    <script>
        // Scroll reveal animations
        document.addEventListener('DOMContentLoaded', function() {
            // Function to check if element is in viewport
            function isInViewport(element) {
                const rect = element.getBoundingClientRect();
                return (
                    rect.top >= 0 &&
                    rect.left >= 0 &&
                    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                );
            }
            
            // Function to handle scroll event
            function handleScroll() {
                const elements = document.querySelectorAll('.animate-on-scroll');
                elements.forEach(element => {
                    if (isInViewport(element)) {
                        element.classList.add('opacity-100', 'translate-y-0');
                        element.classList.remove('opacity-0', '-translate-y-10');
                    }
                });
            }
            
            // Initial check
            handleScroll();
            
            // Add scroll event listener
            window.addEventListener('scroll', handleScroll);
            
            // Add animation classes to hero text after page load
            setTimeout(() => {
                const heroElements = document.querySelectorAll('.animate-fade-in-up');
                heroElements.forEach(el => {
                    el.classList.remove('opacity-0');
                    el.classList.add('opacity-100');
                });
                
                const heroDownElements = document.querySelectorAll('.animate-fade-in-down');
                heroDownElements.forEach(el => {
                    el.classList.remove('opacity-0');
                    el.classList.add('opacity-100');
                });
            }, 100);
        });
    </script>
</body>
</html>
