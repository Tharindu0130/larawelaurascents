<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    //Display products page  
    
    public function index()
    {
        return view('customer.products');
    }
}
