@extends('layouts.customer')

@section('content')

<section class="max-w-4xl mx-auto px-6 py-12">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-serif font-bold mb-2">Order Confirmed!</h1>
        <p class="text-gray-600">Thank you for your order. We'll send you a confirmation email shortly.</p>
    </div>

    <div class="bg-white p-8 rounded-xl shadow mb-6">
        <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
        
        <div class="space-y-4 mb-6">
            @foreach($orders as $order)
                <div class="flex justify-between items-center border-b pb-3">
                    <div>
                        <p class="font-medium">{{ $order->product->name }}</p>
                        <p class="text-sm text-gray-600">Quantity: {{ $order->quantity }}</p>
                        <p class="text-xs text-gray-500">Order #{{ $order->id }}</p>
                    </div>
                    <span class="font-semibold">Rs. {{ number_format($order->total_price, 2) }}</span>
                </div>
            @endforeach
        </div>

        <div class="flex justify-between items-center font-bold text-lg pt-3 border-t">
            <span>Total Amount:</span>
            <span class="text-green-600">Rs. {{ number_format($totalAmount, 2) }}</span>
        </div>
    </div>

    <div class="bg-gray-50 p-6 rounded-xl mb-6">
        <h3 class="font-semibold mb-3">Delivery Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
            <div>
                <span class="text-gray-600">Name:</span>
                <span class="font-medium ml-2">{{ $customerInfo['first_name'] }} {{ $customerInfo['last_name'] }}</span>
            </div>
            <div>
                <span class="text-gray-600">Email:</span>
                <span class="font-medium ml-2">{{ $customerInfo['email'] }}</span>
            </div>
            <div>
                <span class="text-gray-600">Phone:</span>
                <span class="font-medium ml-2">{{ $customerInfo['phone'] }}</span>
            </div>
            <div>
                <span class="text-gray-600">Address:</span>
                <span class="font-medium ml-2">{{ $customerInfo['address'] }}, {{ $customerInfo['city'] }}, {{ $customerInfo['state'] }} {{ $customerInfo['zip'] }}</span>
            </div>
        </div>
    </div>

    <div class="flex gap-4">
        <a href="{{ route('orders.index') }}" class="flex-1 bg-black text-white text-center py-3 rounded-full hover:bg-gray-800">
            View My Orders
        </a>
        <a href="{{ route('products') }}" class="flex-1 bg-gray-200 text-gray-800 text-center py-3 rounded-full hover:bg-gray-300">
            Continue Shopping
        </a>
    </div>
</section>

@endsection
