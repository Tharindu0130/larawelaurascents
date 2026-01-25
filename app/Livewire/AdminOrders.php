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

    // Reset pagination when searching or filtering
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
        $query = Order::with(['user', 'product'])
            ->orderBy('created_at', 'desc');

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })->orWhere('id', 'like', '%' . $this->search . '%');
        }

        $orders = $query->paginate(10);

        return view('livewire.admin-orders', [
            'orders' => $orders
        ])->layout('layouts.admin');
    }

    public function updateStatus($orderId, $newStatus)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->status = $newStatus;
            $order->save();
            session()->flash('message', "Order #{$order->id} status updated to " . ucfirst($newStatus));
        }
    }

    public function viewDetails($orderId)
    {
        $this->selectedOrder = Order::with(['user', 'product'])->find($orderId);
        $this->isDetailModalOpen = true;
    }

    public function closeDetailModal()
    {
        $this->isDetailModalOpen = false;
        $this->selectedOrder = null;
    }
}
