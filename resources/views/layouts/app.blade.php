<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Aura Scents</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Tailwind CDN (simple for now) --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800">

    {{-- NAVBAR --}}
    <header class="border-b">
        <div class="max-w-7xl mx-auto flex justify-between items-center p-4">
            <h1 class="text-xl font-bold">Aura Scents</h1>

            <nav class="space-x-6 text-sm">
                <a href="{{ route('home') }}" class="hover:text-indigo-600">Home</a>
                <a href="{{ route('products') }}" class="hover:text-indigo-600">Products</a>
                <a href="{{ route('about') }}" class="hover:text-indigo-600">About</a>
                <a href="{{ route('contact') }}" class="hover:text-indigo-600">Contact</a>
                <a href="{{ route('cart.show') }}" class="hover:text-indigo-600">
    Cart
</a>
                <a href="{{ route('login') }}" class="hover:text-indigo-600">Login</a>
            </nav>
        </div>
    </header>

    {{-- PAGE CONTENT --}}
    <main class="max-w-7xl mx-auto py-10 px-4">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="border-t mt-20 py-6 text-center text-sm text-gray-500">
        © {{ date('Y') }} Aura Scents. All rights reserved.
    </footer>

</body>
</html>
