@extends('layouts.customer')

@section('content')

<section class="max-w-6xl mx-auto px-6 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-serif font-bold mb-2">My Orders</h1>
        <p class="text-gray-600">View and track your order history</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($orders->count() > 0)
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Order ID</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Product</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Quantity</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Total</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Date</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm">#{{ $order->id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($order->product->image)
                                            <img src="{{ asset('storage/' . $order->product->image) }}" 
                                                 alt="{{ $order->product->name }}" 
                                                 class="w-12 h-12 object-cover rounded">
                                        @endif
                                        <span class="font-medium">{{ $order->product->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-gray-600">{{ $order->quantity }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold">Rs. {{ number_format($order->total_price, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
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
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600">{{ $order->created_at->format('M d, Y') }}</span>
                                    <br>
                                    <span class="text-xs text-gray-500">{{ $order->created_at->format('h:i A') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('orders.show', $order->id) }}" 
                                       class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No orders yet</h3>
            <p class="text-gray-600 mb-6">Start shopping to see your orders here!</p>
            <a href="{{ route('products') }}" class="inline-block bg-black text-white px-6 py-3 rounded-full hover:bg-gray-800">
                Browse Products
            </a>
        </div>
    @endif
</section>

@endsection
