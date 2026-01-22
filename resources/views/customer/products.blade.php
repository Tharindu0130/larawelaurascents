@extends('layouts.customer')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">

    <h1 class="text-3xl font-bold mb-8 text-center">
        Our Collection
    </h1>

    @if(session('success'))
        <div class="mb-6 text-green-600 text-center font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($products as $product)
            <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">

                {{-- Product Image --}}
                <img
                    src="{{ $product->image ?? 'https://via.placeholder.com/400x300' }}"
                    alt="{{ $product->name }}"
                    class="w-full h-60 object-cover"
                >

                {{-- Product Info --}}
                <div class="p-6">
                    <p class="text-xs uppercase text-indigo-600 mb-1">
                        Unisex
                    </p>

                    <h3 class="text-lg font-semibold mb-2">
                        {{ $product->name }}
                    </h3>

                    <p class="text-gray-700 font-bold mb-4">
                        ${{ number_format($product->price, 2) }}
                    </p>

                    {{-- ✅ ADD TO CART FORM (NEW) --}}
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button
                            type="submit"
                            class="w-full bg-indigo-600 text-white py-2 rounded-full text-sm hover:bg-indigo-700 transition">
                            Add to Cart
                        </button>
                    </form>
                    {{-- ✅ END ADD TO CART FORM --}}

                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
