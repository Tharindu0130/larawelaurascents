@extends('layouts.customer')

@section('content')

<section class="max-w-4xl mx-auto px-6 py-12">
    <div class="mb-6">
        <a href="{{ route('orders.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to My Orders
        </a>
        <h1 class="text-3xl font-serif font-bold">Order Details</h1>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden mb-6">
        <div class="p-6 border-b bg-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Order ID</p>
                    <p class="font-mono font-semibold">#{{ $order->id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Order Date</p>
                    <p class="font-semibold">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Status</p>
                    @if($order->status === 'completed')
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Completed
                        </span>
                    @elseif($order->status === 'processing')
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            Processing
                        </span>
                    @elseif($order->status === 'cancelled')
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            Cancelled
                        </span>
                    @else
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-6">
            <h2 class="text-lg font-semibold mb-4">Product Details</h2>
            
            <div class="flex gap-6 pb-6 border-b">
                @if($order->product->image)
                    <img src="{{ asset('storage/' . $order->product->image) }}" 
                         alt="{{ $order->product->name }}" 
                         class="w-32 h-32 object-cover rounded-lg">
                @else
                    <div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif

                <div class="flex-1">
                    <h3 class="text-xl font-semibold mb-2">{{ $order->product->name }}</h3>
                    
                    @if($order->product->description)
                        <p class="text-gray-600 text-sm mb-3">{{ Str::limit($order->product->description, 150) }}</p>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Unit Price</p>
                            <p class="font-semibold">Rs. {{ number_format($order->product->price, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Quantity</p>
                            <p class="font-semibold">{{ $order->quantity }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Subtotal</span>
                    <span>Rs. {{ number_format($order->total_price, 2) }}</span>
                </div>
                <div class="flex justify-between items-center font-bold text-lg pt-3 border-t">
                    <span>Total Amount</span>
                    <span class="text-green-600">Rs. {{ number_format($order->total_price, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
        <h3 class="font-semibold text-blue-900 mb-2">Order Status Information</h3>
        <p class="text-sm text-blue-800">
            @if($order->status === 'pending')
                Your order has been received and is awaiting processing. We'll notify you once it's being prepared.
            @elseif($order->status === 'processing')
                Your order is currently being processed and will be shipped soon.
            @elseif($order->status === 'completed')
                Your order has been completed and delivered. Thank you for your purchase!
            @elseif($order->status === 'cancelled')
                This order has been cancelled. If you have questions, please contact our support team.
            @endif
        </p>
    </div>
</section>

@endsection
