<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * ASSIGNMENT CRITERIA: Admin Customer Management via API
 * 
 * This component demonstrates:
 * - Using API calls instead of direct database access
 * - Http facade for server-side API consumption
 * - Proper separation of concerns (frontend calls API, not DB)
 */
class AdminCustomers extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedUser = null;
    public $isOrderModalOpen = false;
    public $isDeleteModalOpen = false;
    public $userToDeleteId = null;

    /**
     * ASSIGNMENT: API-based helper method
     * All CRUD operations use API endpoints
     */
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

    /**
     * ASSIGNMENT: Fetch customers via API (not direct DB access)
     */
    private function fetchCustomers(): array
    {
        $url = url('/api/users');
        if ($this->search) {
            $url .= '?search=' . urlencode($this->search);
        }
        
        // Debug: Check if token exists
        $token = session('api_token');
        if (!$token) {
            \Log::error('AdminCustomers: No API token in session');
            session()->flash('error', 'No API token found. Please logout and login again.');
            return [];
        }
        
        $r = $this->api('GET', $url);
        
        if (!$r->successful()) {
            \Log::error('AdminCustomers API failed', [
                'status' => $r->status(),
                'response' => $r->body(),
                'url' => $url
            ]);
            session()->flash('error', 'Failed to load customers: ' . $r->status() . ' - ' . ($r->json('message') ?? 'Unknown error'));
            return [];
        }
        
        $data = $r->json('data') ?? $r->json() ?? [];
        \Log::info('AdminCustomers: Fetched ' . count($data) . ' customers');
        return is_array($data) ? $data : [];
    }

    public function render()
    {
        // ASSIGNMENT: Get customers from API, not database
        $allUsers = collect($this->fetchCustomers());
        $allUsers = $allUsers->sortByDesc('created_at')->values();
        
        // Manual pagination
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $slice = $allUsers->slice(($page - 1) * $perPage, $perPage)->values();
        
        $users = new \Illuminate\Pagination\LengthAwarePaginator(
            $slice,
            $allUsers->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        return view('livewire.admin-customers', [
            'users' => $users
        ])->layout('layouts.admin');
    }

    /**
     * ASSIGNMENT: Toggle status via API
     */
    public function toggleStatus($userId)
    {
        // Get current user data
        $r = $this->api('GET', url('/api/users/' . $userId));
        if (!$r->successful()) {
            session()->flash('error', 'Failed to load user data.');
            return;
        }
        
        $user = $r->json('data') ?? [];
        $currentStatus = $user['is_active'] ?? true;
        
        // Update via API
        $r = $this->api('PUT', url('/api/users/' . $userId), [
            'is_active' => !$currentStatus
        ]);
        
        if ($r->successful()) {
            session()->flash('message', 'Customer status updated successfully.');
        } else {
            session()->flash('error', 'Failed to update customer status.');
        }
    }

    /**
     * ASSIGNMENT: View orders via API
     */
    public function viewOrders($userId)
    {
        $r = $this->api('GET', url('/api/users/' . $userId));
        if ($r->successful()) {
            $this->selectedUser = $r->json('data');
            $this->isOrderModalOpen = true;
        } else {
            session()->flash('error', 'Failed to load user orders.');
        }
    }

    public function closeOrderModal()
    {
        $this->isOrderModalOpen = false;
        $this->selectedUser = null;
    }

    public function confirmDelete($userId)
    {
        $this->userToDeleteId = $userId;
        $this->isDeleteModalOpen = true;
    }

    public function cancelDelete()
    {
        $this->isDeleteModalOpen = false;
        $this->userToDeleteId = null;
    }

    /**
     * ASSIGNMENT: Delete user via API
     */
    public function deleteUser()
    {
        if ($this->userToDeleteId) {
            $r = $this->api('DELETE', url('/api/users/' . $this->userToDeleteId));
            
            if ($r->successful()) {
                session()->flash('message', 'Customer deleted successfully.');
            } else {
                session()->flash('error', 'Failed to delete customer: ' . ($r->json('message') ?? $r->body()));
            }
        }
        $this->cancelDelete();
    }
}
