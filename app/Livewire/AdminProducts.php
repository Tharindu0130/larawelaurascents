<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class AdminProducts extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;

    public $product_id;
    public $name;
    public $description;
    public $price;
    public $image;
    public $stock = 100;

    // Default category for create (first category)
    public $defaultCategoryId = null;

    protected $rules = [
        'name' => 'required|string|min:3',
        'description' => 'required|string|min:10',
        'price' => 'required|numeric|min:0',
        'image' => 'required|url',
    ];

    public function mount(): void
    {
        // Get first category ID from database
        $firstCategory = Category::first();
        $this->defaultCategoryId = $firstCategory ? $firstCategory->id : null;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        // Query products from database
        $query = Product::with(['category', 'user']);
        
        if ($this->search !== '') {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        
        $products = $query->orderBy('id', 'desc')->paginate(10);

        return view('livewire.admin-products', [
            'products' => $products,
        ])->layout('layouts.admin');
    }

    public function create(): void
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal(): void
    {
        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields(): void
    {
        $this->product_id = null;
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->image = '';
        $this->stock = 100;
    }

    public function store(): void
    {
        $this->validate();

        if (!$this->defaultCategoryId) {
            session()->flash('message', 'No category found. Create a category first.');
            return;
        }

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'stock' => (int) $this->stock,
            'image' => $this->image,
            'category_id' => $this->defaultCategoryId,
            'user_id' => auth()->id(),
        ];

        if ($this->product_id) {
            // Update existing product
            $product = Product::find($this->product_id);
            if ($product) {
                $product->update($data);
                session()->flash('message', 'Product updated successfully.');
            } else {
                session()->flash('message', 'Product not found.');
            }
        } else {
            // Create new product
            Product::create($data);
            session()->flash('message', 'Product created successfully.');
        }

        $this->closeModal();
    }

    public function edit($id): void
    {
        $product = Product::find($id);
        
        if (!$product) {
            session()->flash('message', 'Product not found.');
            return;
        }

        $this->product_id = $product->id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->image = $product->image;
        $this->stock = $product->stock;

        $this->openModal();
    }

    public function confirmDelete($id): void
    {
        $this->product_id = $id;
        $this->isDeleteModalOpen = true;
    }

    public function cancelDelete(): void
    {
        $this->isDeleteModalOpen = false;
        $this->product_id = null;
    }

    public function delete(): void
    {
        $product = Product::find($this->product_id);
        
        if ($product) {
            $product->delete();
            session()->flash('message', 'Product deleted successfully.');
        } else {
            session()->flash('message', 'Product not found.');
        }
        
        $this->cancelDelete();
    }
}
