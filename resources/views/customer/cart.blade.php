@extends('layouts.customer')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-12">

    <h1 class="text-3xl font-bold mb-8 text-center">Your Cart</h1>

    @if(session('success'))
        <div class="mb-6 text-green-600 text-center font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if(empty($cart))
        <p class="text-center text-gray-600">Your cart is empty.</p>
    @else
        <div class="space-y-6">
            @foreach($cart as $productId => $item)
                <div class="bg-white rounded-2xl shadow p-6 flex flex-col md:flex-row gap-6 items-center">

                    {{-- Image --}}
                    <img
                        src="{{ $item['image'] ?? 'https://via.placeholder.com/120' }}"
                        class="w-28 h-28 object-cover rounded-xl"
                    >

                    {{-- Product Info --}}
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold">{{ $item['name'] }}</h3>
                        <p class="text-gray-600">
                            ${{ number_format($item['price'], 2) }} × {{ $item['quantity'] }}
                        </p>
                        <p class="font-bold mt-1">
                            ${{ number_format($item['price'] * $item['quantity'], 2) }}
                        </p>
                    </div>

                    {{-- Quantity (AUTO UPDATE) --}}
                    <form
                        action="{{ route('cart.update', $productId) }}"
                        method="POST"
                    >
                        @csrf
                        <input
                            type="number"
                            name="quantity"
                            min="1"
                            value="{{ $item['quantity'] }}"
                            onchange="this.form.submit()"
                            class="w-20 border rounded-lg px-3 py-2 text-center"
                        >
                    </form>

                    {{-- Remove --}}
                    <form
                        action="{{ route('cart.remove', $productId) }}"
                        method="POST"
                    >
                        @csrf
                        <button
                            type="submit"
                            class="px-4 py-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition"
                        >
                            Remove
                        </button>
                    </form>

                </div>
            @endforeach
        </div>

        {{-- Cart Total --}}
        <div class="mt-10 text-right">
            @php
                $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
            @endphp

            <p class="text-xl font-bold mb-4">
                Total: ${{ number_format($total, 2) }}
            </p>

            <a
                href="{{ route('checkout') }}"
                class="inline-block bg-black text-white px-8 py-3 rounded-full hover:bg-gray-800 transition"
            >
                Proceed to Checkout
            </a>
        </div>
    @endif

</div>
@endsection
