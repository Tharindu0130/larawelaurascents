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
    public $isDeleteModalOpen = false;
    public $userToDeleteId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Query customers directly from database (no HTTP calls)
        $query = User::where('user_type', 'customer');
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin-customers', [
            'users' => $users
        ])->layout('layouts.admin');
    }

    /**
     * Toggle status directly in database
     */
    public function toggleStatus($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            session()->flash('error', 'Customer not found.');
            return;
        }
        
        $user->is_active = !($user->is_active ?? true);
        $user->save();
        
        session()->flash('message', 'Customer status updated successfully.');
    }

    /**
     *  View orders directly from database
     */
    public function viewOrders($userId)
    {
        $user = User::with(['orders.product'])->find($userId);
        
        if ($user) {
            $this->selectedUser = $user->toArray();
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
     *  Delete user directly from database
     */
    public function deleteUser()
    {
        if ($this->userToDeleteId) {
            $user = User::find($this->userToDeleteId);
            
            if ($user) {
                // Delete related orders first
                $user->orders()->delete();
                
                // Delete user
                $user->delete();
                
                session()->flash('message', 'Customer deleted successfully.');
            } else {
                session()->flash('error', 'Customer not found.');
            }
        }
        $this->cancelDelete();
    }
}
