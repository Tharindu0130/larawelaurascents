<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class AdminCustomers extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedUser = null;
    public $isOrderModalOpen = false;

    // Reset pagination when searching
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::where('user_type', 'customer')
            ->where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin-customers', [
            'users' => $users
        ])->layout('layouts.admin');
    }

    public function toggleStatus($userId)
    {
        $user = User::find($userId);
        if ($user) {
            // Toggle is_active. Default to true if column shouldn't be null but might be initially
            // We assume column exists now or we handle it gracefully.
            // Using a migration to add it, but fallback logic:
            $currentStatus = $user->is_active ?? true; 
            $user->is_active = !$currentStatus;
            $user->save();
            
            session()->flash('message', 'Customer status updated successfully.');
        }
    }

    public function viewOrders($userId)
    {
        $this->selectedUser = User::with('orders.product')->find($userId);
        $this->isOrderModalOpen = true;
    }

    public function closeOrderModal()
    {
        $this->isOrderModalOpen = false;
        $this->selectedUser = null;
    }

    public $isDeleteModalOpen = false;
    public $userToDeleteId = null;

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

    public function deleteUser()
    {
        if ($this->userToDeleteId) {
            try {
                \Illuminate\Support\Facades\DB::transaction(function () {
                    $userId = $this->userToDeleteId;
                    
                    // 1. Delete Orders
                    \App\Models\Order::where('user_id', $userId)->delete();
                    
                    // 2. Delete Cart Items (if you have a cart table, assuming session for now but just in case)
                    // \App\Models\Cart::where('user_id', $userId)->delete(); 

                    // 3. Delete from Users table directly to bypass model events
                    \Illuminate\Support\Facades\DB::table('users')->where('id', $userId)->delete();
                });

                session()->flash('message', 'Customer FORCE DELETED successfully.');
            } catch (\Exception $e) {
                session()->flash('error', 'CRITICAL ERROR: ' . $e->getMessage());
            }
        }
        $this->cancelDelete();
    }
}
