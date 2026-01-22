@extends('layouts.customer')

@section('content')

<!-- HERO SECTION -->
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

        <!-- TEXT -->
        <div>
            <p class="text-sm tracking-widest text-gray-500 mb-4 uppercase">
                Luxury Fragrances
            </p>

            <h1 class="text-5xl font-serif font-bold text-gray-900 leading-tight mb-6">
                Discover Your <br> Signature Scent
            </h1>

            <p class="text-gray-600 text-lg mb-8 max-w-md">
                Experience timeless elegance with our curated collection of
                premium perfumes crafted for every personality.
            </p>

            <div class="flex gap-4">
                <a href="{{ route('products') }}"
                   class="bg-black text-white px-8 py-3 rounded-full text-sm hover:bg-gray-800 transition">
                    Shop Now
                </a>

                <a href="{{ route('about') }}"
                   class="border border-black px-8 py-3 rounded-full text-sm hover:bg-black hover:text-white transition">
                    Learn More
                </a>
            </div>
        </div>

        <!-- IMAGE -->
        <div class="flex justify-center">
            <img
                src="https://images.unsplash.com/photo-1592945403244-b3fbafd7f539"
                alt="Luxury Perfume"
                class="rounded-2xl shadow-xl max-h-[420px] object-cover"
            >
        </div>

    </div>
</section>

<!-- FEATURED COLLECTION -->
<section class="max-w-7xl mx-auto px-6 py-16">

    <h2 class="text-3xl font-serif font-bold text-center mb-12">
        Featured Collection
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">

        <!-- CARD -->
        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition overflow-hidden">
            <img
                src="https://images.unsplash.com/photo-1585386959984-a41552231691"
                class="w-full h-64 object-cover"
            >
            <div class="p-6">
                <h3 class="font-semibold text-lg mb-1">Chanel No.5</h3>
                <p class="text-sm text-gray-500 mb-3">Floral · Elegant</p>

                <div class="flex justify-between items-center">
                    <span class="font-bold text-lg">$120</span>
                    <button class="bg-black text-white px-4 py-2 rounded-full text-sm">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>

        <!-- CARD -->
        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition overflow-hidden">
            <img
                src="https://images.unsplash.com/photo-1610465299993-e6675c9f9efa"
                class="w-full h-64 object-cover"
            >
            <div class="p-6">
                <h3 class="font-semibold text-lg mb-1">Dior Sauvage</h3>
                <p class="text-sm text-gray-500 mb-3">Fresh · Bold</p>

                <div class="flex justify-between items-center">
                    <span class="font-bold text-lg">$98</span>
                    <button class="bg-black text-white px-4 py-2 rounded-full text-sm">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>

        <!-- CARD -->
        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition overflow-hidden">
            <img
                src="https://images.unsplash.com/photo-1619995745882-f4128ac82ad6"
                class="w-full h-64 object-cover"
            >
            <div class="p-6">
                <h3 class="font-semibold text-lg mb-1">YSL Libre</h3>
                <p class="text-sm text-gray-500 mb-3">Warm · Sensual</p>

                <div class="flex justify-between items-center">
                    <span class="font-bold text-lg">$110</span>
                    <button class="bg-black text-white px-4 py-2 rounded-full text-sm">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection
