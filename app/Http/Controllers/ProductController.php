<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    /**Fetch products from API. Public read.*/
    public function index()
    {
        $response = Http::acceptJson()->get(url('/api/products'));

        if (!$response->successful()) {
            $products = [];
        } else {
            $products = $response->json('data') ?? $response->json() ?? [];
        }

        return view('customer.products', compact('products'));
    }
}
