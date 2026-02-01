<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    //Show customer's order history.

    public function myOrders()
    {
        return view('customer.my-orders');
    }

    //Show details of a specific order.
    public function show($id)
    {
        return view('customer.order-details', ['orderId' => $id]);
    }
}
