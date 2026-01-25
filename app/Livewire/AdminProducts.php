<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class AdminProducts extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    
    // Product Fields
    public $product_id;
    public $name;
    public $description;
    public $price;
    public $image; // This is the image URL
    public $stock = 100; // Default stock
    public $status = 'active'; // Default status if we had one, essentially existence

    protected $rules = [
        'name' => 'required|string|min:3',
        'description' => 'required|string|min:10',
        'price' => 'required|numeric|min:0',
        'image' => 'required|url', // STRICT RULE: web link ONLY
    ];

    // Reset pagination when searching
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.admin-products', [
            'products' => $products
        ])->layout('layouts.admin');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->product_id = null;
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->image = '';
        $this->stock = 100;
    }

    public function store()
    {
        $this->validate();

        Product::updateOrCreate(['id' => $this->product_id], [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'image' => $this->image,
            'stock' => $this->stock,
            'user_id' => auth()->id() ?? 1, // Fallback to admin ID 1 if needed
        ]);

        session()->flash('message', $this->product_id ? 'Product updated successfully.' : 'Product created successfully.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->product_id = $id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->image = $product->image;
        $this->stock = $product->stock;
    
        $this->openModal();
    }

    public function confirmDelete($id)
    {
        $this->product_id = $id;
        $this->isDeleteModalOpen = true;
    }

    public function cancelDelete()
    {
        $this->isDeleteModalOpen = false;
        $this->product_id = null;
    }

    public function delete()
    {
        Product::find($this->product_id)->delete();
        session()->flash('message', 'Product deleted successfully.');
        $this->cancelDelete();
    }
}
