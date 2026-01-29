<?php

namespace App\Livewire;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
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

    /** @var int|null Default category for API create (first category). */
    public $defaultCategoryId = null;

    protected $rules = [
        'name' => 'required|string|min:3',
        'description' => 'required|string|min:10',
        'price' => 'required|numeric|min:0',
        'image' => 'required|url',
    ];

    public function mount(): void
    {
        $this->fetchDefaultCategoryId();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    private function api(string $method, string $url, array $data = []): \Illuminate\Http\Client\Response
    {
        $req = Http::acceptJson()->asJson()->withToken(session('api_token') ?? '');
        return match (strtoupper($method)) {
            'GET' => $req->get($url),
            'POST' => $req->post($url, $data),
            'PUT' => $req->put($url, $data),
            'PATCH' => $req->patch($url, $data),
            'DELETE' => $req->delete($url),
            default => $req->get($url),
        };
    }

    private function fetchDefaultCategoryId(): void
    {
        $r = $this->api('GET', url('/api/categories'));
        if ($r->successful()) {
            $data = $r->json('data') ?? $r->json();
            $items = is_array($data) ? $data : [];
            $first = !empty($items) ? reset($items) : [];
            $this->defaultCategoryId = is_array($first) ? ($first['id'] ?? null) : null;
        }
    }

    private function fetchProducts(): Collection
    {
        $r = Http::acceptJson()->get(url('/api/products'));
        if (!$r->successful()) {
            return collect();
        }
        $data = $r->json('data') ?? $r->json() ?? [];
        return collect($data);
    }

    public function getProductsProperty(): LengthAwarePaginator
    {
        $all = $this->fetchProducts();
        if ($this->search !== '') {
            $all = $all->filter(fn ($p) => stripos($p['name'] ?? '', $this->search) !== false);
        }
        $all = $all->sortByDesc('id')->values();
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $slice = $all->slice(($page - 1) * $perPage, $perPage)->values();
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $slice,
            $all->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
    }

    public function render()
    {
        return view('livewire.admin-products', [
            'products' => $this->getProductsProperty(),
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
            session()->flash('message', 'No category found. Create a category via API first.');
            return;
        }

        $payload = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'stock' => (int) $this->stock,
            'image' => $this->image,
            'category_id' => $this->defaultCategoryId,
        ];

        if ($this->product_id) {
            $r = $this->api('PUT', url('/api/products/' . $this->product_id), $payload);
        } else {
            $r = $this->api('POST', url('/api/products'), $payload);
        }

        if ($r->successful()) {
            session()->flash('message', $this->product_id ? 'Product updated successfully.' : 'Product created successfully.');
            $this->closeModal();
        } else {
            session()->flash('message', 'API error: ' . ($r->json('message') ?? $r->body()));
        }
    }

    public function edit($id): void
    {
        $r = Http::acceptJson()->withToken(session('api_token') ?? '')->get(url('/api/products/' . $id));
        if (!$r->successful()) {
            session()->flash('message', 'Failed to load product.');
            return;
        }
        $product = $r->json('data') ?? $r->json();
        $this->product_id = (int) $id;
        $this->name = $product['name'] ?? '';
        $this->description = $product['description'] ?? '';
        $this->price = $product['price'] ?? '';
        $this->image = $product['image'] ?? '';
        $this->stock = (int) ($product['stock'] ?? 100);
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
        $r = $this->api('DELETE', url('/api/products/' . $this->product_id));
        if ($r->successful()) {
            session()->flash('message', 'Product deleted successfully.');
        } else {
            session()->flash('message', 'Failed to delete product.');
        }
        $this->cancelDelete();
    }
}
