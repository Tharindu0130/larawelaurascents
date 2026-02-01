<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithPagination;


class AdminOrders extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = ''; // '' = All, 'pending', 'completed', 'cancelled'
    public $selectedOrder = null;
    public $isDetailModalOpen = false;

    
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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    /**
     *  Fetch orders via API (not direct DB access)
     */
    private function fetchOrders(): array
    {
        // Debug: Check if token exists
        $token = session('api_token');
        if (!$token) {
            \Log::error('AdminOrders: No API token in session');
            session()->flash('error', 'No API token found. Please logout and login again.');
            return [];
        }
        
        $r = $this->api('GET', url('/api/orders'));
        
        if (!$r->successful()) {
            \Log::error('AdminOrders API failed', [
                'status' => $r->status(),
                'response' => $r->body()
            ]);
            session()->flash('error', 'Failed to load orders: ' . $r->status() . ' - ' . ($r->json('message') ?? 'Unknown error'));
            return [];
        }
        
        $data = $r->json('data') ?? $r->json() ?? [];
        
        // Extract orders from paginated response
        if (isset($data['data']) && is_array($data['data'])) {
            \Log::info('AdminOrders: Fetched ' . count($data['data']) . ' orders (paginated)');
            return $data['data'];
        }
        
        \Log::info('AdminOrders: Fetched ' . count($data) . ' orders');
        return is_array($data) ? $data : [];
    }

    public function render()
    {
        // ASSIGNMENT: Get orders from API, not database
        $allOrders = collect($this->fetchOrders());
        
        // Apply filters (client-side since API returns all)
        if ($this->statusFilter) {
            $allOrders = $allOrders->filter(function($order) {
                return ($order['status'] ?? '') === $this->statusFilter;
            });
        }
        
        if ($this->search) {
            $allOrders = $allOrders->filter(function($order) {
                $searchLower = strtolower($this->search);
                $userName = strtolower($order['user']['name'] ?? '');
                $userEmail = strtolower($order['user']['email'] ?? '');
                $orderId = strtolower($order['id'] ?? '');
                
                return str_contains($userName, $searchLower) ||
                       str_contains($userEmail, $searchLower) ||
                       str_contains($orderId, $searchLower);
            });
        }
        
        $allOrders = $allOrders->sortByDesc('created_at')->values();
        
        // Manual pagination
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $slice = $allOrders->slice(($page - 1) * $perPage, $perPage)->values();
        
        $orders = new \Illuminate\Pagination\LengthAwarePaginator(
            $slice,
            $allOrders->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        return view('livewire.admin-orders', [
            'orders' => $orders
        ])->layout('layouts.admin');
    }

    // Update order status via API
     
    public function updateStatus($orderId, $newStatus)
    {
        $r = $this->api('PUT', url('/api/orders/' . $orderId), [
            'status' => $newStatus
        ]);
        
        if ($r->successful()) {
            session()->flash('message', "Order #{$orderId} status updated to " . ucfirst($newStatus));
        } else {
            session()->flash('error', 'Failed to update order status.');
        }
    }

    // ASSIGNMENT: View order details via API
    public function viewDetails($orderId)
    {
        $r = $this->api('GET', url('/api/orders/' . $orderId));
        
        if ($r->successful()) {
            $this->selectedOrder = $r->json('data');
            $this->isDetailModalOpen = true;
        } else {
            session()->flash('error', 'Failed to load order details.');
        }
    }

    public function closeDetailModal()
    {
        $this->isDetailModalOpen = false;
        $this->selectedOrder = null;
    }
}
