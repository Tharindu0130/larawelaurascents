<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Aura Scents</title>

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- App CSS / JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine.js for interactive components --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50">

@php
    // Calculate cart count safely from session
    $cartCount = collect(session('cart', []))->sum('quantity');
@endphp

{{-- NAVBAR --}}
<nav class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600">
            Aura Scents
        </a>

        <div class="space-x-6 flex items-center">
            <a href="{{ route('home') }}" class="hover:text-indigo-600">Home</a>
            <a href="{{ route('products') }}" class="hover:text-indigo-600">Products</a>
            <a href="{{ route('about') }}" class="hover:text-indigo-600">About</a>
            <a href="{{ route('contact') }}" class="hover:text-indigo-600">Contact</a>

            {{-- CART ICON --}}
            <a href="{{ route('cart.show') }}" class="relative hover:text-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-6 w-6"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
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
                        class="absolute -top-2 -right-2 bg-red-500 text-white text-xs
                               rounded-full h-5 w-5 flex items-center justify-center">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>

            {{-- AUTH LINKS --}}
            @auth
                {{-- Profile Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 hover:text-indigo-600 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
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
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Profile
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Orders
                            </a>
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
                <a href="{{ route('login') }}" class="text-indigo-600">
                    Login
                </a>
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
