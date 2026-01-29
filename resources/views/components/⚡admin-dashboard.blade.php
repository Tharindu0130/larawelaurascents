<?php

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Http;

new class extends Component
{
    public $name = '';
    public $description = '';
    public $price = '';
    public $stock = '';
    public $image = '';
    public $category_id = '';
    public $categories = [];
    public $message = '';

    public function mount()
    {
        // Load categories for the dropdown
        $this->categories = Category::all();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'required|string|max:500',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Get the authenticated user's token from session
        $token = session('api_token');

        if (!$token) {
            $this->message = 'You must be logged in to add products.';
            return;
        }

        // Make API call to create product
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->post(url('/api/products'), [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'image' => $this->image,
            'category_id' => $this->category_id,
        ]);

        if ($response->successful()) {
            $this->message = 'Product added successfully!';
            // Reset form
            $this->reset(['name', 'description', 'price', 'stock', 'image', 'category_id']);
        } else {
            $this->message = 'Error: ' . ($response->json()['message'] ?? 'Failed to add product');
        }
    }

    public function render()
    {
        return view('livewire.admin-dashboard');
    }
};
?>

<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Admin Dashboard - Add Product</h1>

    @if($message)
        <div class="mb-4 p-4 bg-{{ str_contains($message, 'Error') ? 'red' : 'green' }}-100 border border-{{ str_contains($message, 'Error') ? 'red' : 'green' }}-400 text-{{ str_contains($message, 'Error') ? 'red' : 'green' }}-700 rounded">
            {{ $message }}
        </div>
    @endif

    <form wire:submit="save" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                Product Name
            </label>
            <input wire:model="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                Description
            </label>
            <textarea wire:model="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" rows="4"></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                Price
            </label>
            <input wire:model="price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="price" type="number" step="0.01" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="stock">
                Stock
            </label>
            <input wire:model="stock" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="stock" type="number" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="image">
                Image URL
            </label>
            <input wire:model="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="image" type="text" placeholder="https://example.com/image.jpg" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="category_id">
                Category
            </label>
            <select wire:model="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="category_id" required>
                <option value="">Select a category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Add Product
            </button>
        </div>
    </form>
</div>
