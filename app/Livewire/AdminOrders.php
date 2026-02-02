<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;


class AdminOrders extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = ''; // '' = All, 'pending', 'completed', 'cancelled'
    public $selectedOrder = null;
    public $isDetailModalOpen = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Query orders from database
        $query = Order::with(['user', 'product']);
        // Apply status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }
        // Apply search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function($userQuery) {
                      $userQuery->where('name', 'like', '%' . $this->search . '%')
                               ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin-orders', [
            'orders' => $orders
        ])->layout('layouts.admin');
    }

    // Update order status in database
    public function updateStatus($orderId, $newStatus)
    {
        $order = Order::find($orderId);
        
        if ($order) {
            $order->status = $newStatus;
            $order->save();
            session()->flash('message', "Order #{$orderId} status updated to " . ucfirst($newStatus));
        } else {
            session()->flash('error', 'Order not found.');
        }
    }

    // View order details from database
    public function viewDetails($orderId)
    {
        $order = Order::with(['user', 'product'])->find($orderId);
        
        if ($order) {
            $this->selectedOrder = $order->toArray();
            $this->isDetailModalOpen = true;
        } else {
            session()->flash('error', 'Order not found.');
        }
    }

    public function closeDetailModal()
    {
        $this->isDetailModalOpen = false;
        $this->selectedOrder = null;
    }
}
