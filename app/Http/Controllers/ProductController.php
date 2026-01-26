<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display products page (products loaded via API in frontend JS).
     * NO DIRECT DB ACCESS - follows assignment requirement.
     */
    public function index()
    {
        return view('customer.products');
    }
}
