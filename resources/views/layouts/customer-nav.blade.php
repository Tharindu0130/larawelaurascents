<nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between">
        <a href="{{ route('home') }}" class="font-bold">Aura Scents</a>

        <div class="flex gap-6">
            <a href="{{ route('products') }}">Products</a>
            <a href="{{ route('cart.show') }}">Cart</a>

            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
            @endauth
        </div>
    </div>
</nav>
