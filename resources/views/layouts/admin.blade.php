<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Portal - Aura Scents</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 font-sans antialiased">
    <nav class="bg-white shadow-md mb-6 relative z-10">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-gray-800 hover:text-indigo-600 transition">
                        Aura Scents Admin
                    </a>
                    
                    <div class="hidden md:flex space-x-6">
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 font-semibold' : 'text-gray-600 hover:text-indigo-600' }}">Dashboard</a>
                        <a href="{{ route('admin.products') }}" class="{{ request()->routeIs('admin.products') ? 'text-indigo-600 font-semibold' : 'text-gray-600 hover:text-indigo-600' }}">Products</a>
                        <a href="{{ route('admin.customers') }}" class="{{ request()->routeIs('admin.customers') ? 'text-indigo-600 font-semibold' : 'text-gray-600 hover:text-indigo-600' }}">Customers</a>
                        <a href="{{ route('admin.orders') }}" class="{{ request()->routeIs('admin.orders') ? 'text-indigo-600 font-semibold' : 'text-gray-600 hover:text-indigo-600' }}">Orders</a>
                    </div>
                </div>

                <div class="flex items-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-2 px-4 rounded shadow-sm transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-6 pb-12">
        {{ $slot }}
    </main>
    
    @livewireScripts
</body>
</html>