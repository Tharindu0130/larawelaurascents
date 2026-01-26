@extends('layouts.customer')

@section('content')

<section class="max-w-7xl mx-auto px-6 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-serif font-bold mb-2">Customer Dashboard</h1>
        <p class="text-gray-600">Welcome back, {{ Auth::user()->name }}!</p>
    </div>

    {{-- Dashboard Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Total Orders --}}
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Orders</p>
                    <p class="text-3xl font-bold">{{ Auth::user()->orders()->count() }}</p>
                </div>
                <div class="bg-amber-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Pending Orders --}}
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Pending Orders</p>
                    <p class="text-3xl font-bold">{{ Auth::user()->orders()->where('status', 'pending')->count() }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Completed Orders --}}
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Completed Orders</p>
                    <p class="text-3xl font-bold">{{ Auth::user()->orders()->where('status', 'completed')->count() }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-xl shadow p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('products') }}" class="flex items-center gap-3 p-4 border rounded-lg hover:bg-gray-50 transition">
                <div class="bg-amber-100 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <span class="font-medium">Browse Products</span>
            </a>

            <a href="{{ route('cart.show') }}" class="flex items-center gap-3 p-4 border rounded-lg hover:bg-gray-50 transition">
                <div class="bg-blue-100 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="font-medium">View Cart</span>
            </a>

            <a href="{{ route('orders.index') }}" class="flex items-center gap-3 p-4 border rounded-lg hover:bg-gray-50 transition">
                <div class="bg-green-100 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <span class="font-medium">My Orders</span>
            </a>

            <a href="{{ route('profile.show') }}" class="flex items-center gap-3 p-4 border rounded-lg hover:bg-gray-50 transition">
                <div class="bg-purple-100 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <span class="font-medium">My Profile</span>
            </a>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Recent Orders</h2>
            <a href="{{ route('orders.index') }}" class="text-amber-600 hover:text-amber-800 text-sm font-medium">
                View All →
            </a>
        </div>

        @php
            $recentOrders = Auth::user()->orders()->with('product')->latest()->take(5)->get();
        @endphp

        @if($recentOrders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b">
                        <tr>
                            <th class="text-left py-3 text-sm font-semibold text-gray-600">Order ID</th>
                            <th class="text-left py-3 text-sm font-semibold text-gray-600">Product</th>
                            <th class="text-left py-3 text-sm font-semibold text-gray-600">Date</th>
                            <th class="text-left py-3 text-sm font-semibold text-gray-600">Status</th>
                            <th class="text-left py-3 text-sm font-semibold text-gray-600">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3">
                                    <span class="font-mono text-sm">#{{ $order->id }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="font-medium">{{ $order->product->name }}</span>
                                </td>
                                <td class="py-3 text-sm text-gray-600">
                                    {{ $order->created_at->format('M d, Y') }}
                                </td>
                                <td class="py-3">
                                    @if($order->status === 'completed')
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Completed</span>
                                    @elseif($order->status === 'processing')
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Processing</span>
                                    @elseif($order->status === 'cancelled')
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Cancelled</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif
                                </td>
                                <td class="py-3 font-semibold">
                                    Rs. {{ number_format($order->total_price, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <p class="text-gray-600 mb-4">You haven't placed any orders yet</p>
                <a href="{{ route('products') }}" class="inline-block bg-black text-white px-6 py-3 rounded-full hover:bg-gray-800">
                    Start Shopping
                </a>
            </div>
        @endif
    </div>
</section>

@endsection
