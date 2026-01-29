<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\User;
use Livewire\Component;

class AdminDashboard extends Component
{
    public function render()
    {
        return view('livewire.admin-dashboard', [
            'totalProducts' => Product::count(),
            'totalUsers' => User::where('user_type', 'customer')->count(),
            'totalAdmins' => User::where('user_type', 'admin')->count(),
        ]);
    }
}
