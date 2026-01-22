<?php

use Livewire\Component;
use Illuminate\Support\Facades\Http;

new class extends Component
{
    public $products = [];
    public $loading = true;
    public $error = '';

    public function mount()
    {
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $this->loading = true;
        $this->error = '';

        // Get the authenticated user's token from session
        $token = session('api_token');

        if (!$token) {
            $this->error = 'You must be logged in to view products.';
            $this->loading = false;
            return;
        }

        // Fetch products from API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->get(url('/api/products'));

        if ($response->successful()) {
            $this->products = $response->json()['data'] ?? $response->json();
        } else {
            $this->error = 'Failed to load products.';
        }

        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.customer-dashboard');
    }
};
?>

<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Customer Dashboard - Perfume Collection</h1>

    @if($error)
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ $error }}
        </div>
    @endif

    @if($loading)
        <div class="text-center py-8">
            <p class="text-gray-600">Loading products...</p>
        </div>
    @elseif(count($products) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    @if(isset($product['image']) && $product['image'])
                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] ?? 'Product' }}" class="w-full h-64 object-cover">
                    @else
                        <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">No Image</span>
                        </div>
                    @endif
                    <div class="p-4">
                        <h3 class="text-xl font-bold mb-2">{{ $product['name'] ?? 'Unnamed Product' }}</h3>
                        <p class="text-gray-600 mb-2">{{ Str::limit($product['description'] ?? '', 100) }}</p>
                        <p class="text-2xl font-bold text-blue-600">${{ number_format($product['price'] ?? 0, 2) }}</p>
                        <p class="text-sm text-gray-500 mt-2">Stock: {{ $product['stock'] ?? 0 }}</p>
                        @if(isset($product['category']) && is_array($product['category']))
                            <p class="text-sm text-gray-500">Category: {{ $product['category']['name'] ?? 'N/A' }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <p class="text-gray-600">No products available.</p>
        </div>
    @endif
</div>
