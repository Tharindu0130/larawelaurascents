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

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10 mb-12">

        <!-- CARD -->
        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition overflow-hidden">
            <img
                src="https://scentminis.lk/wp-content/uploads/2024/10/channle-no-5.png"
                class="w-full h-64 object-cover"
            >
            <div class="p-6">
                <h3 class="font-semibold text-lg mb-1">Chanel No.5</h3>
                <p class="text-sm text-gray-500 mb-0">Floral · Elegant</p>
                <div class="mt-4 font-bold text-lg">Rs. 120</div>
            </div>
        </div>

        <!-- CARD -->
        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition overflow-hidden">
            <img
                src="https://theperfumestore.lk/wp-content/uploads/2020/10/Dior-Sauvage-eau-de-Parfum-for-him-for-men-edp-Branded-Original-authentic-fragrance-Perfume-store-in-Sri-Lanka.jpg"
                class="w-full h-64 object-cover"
            >
            <div class="p-6">
                <h3 class="font-semibold text-lg mb-1">Dior Sauvage</h3>
                <p class="text-sm text-gray-500 mb-0">Fresh · Bold</p>
                <div class="mt-4 font-bold text-lg">Rs. 98</div>
            </div>
        </div>

        <!-- CARD -->
        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition overflow-hidden">
            <img
                src="https://theperfumestore.lk/wp-content/uploads/2024/11/YSL-libre-intense-edp-eau-de-parfum-edp-for-her-for-women-branded-original-authentic-fragrance-perfume-stotre-in-sri-lanka-best-trusted-authentic-fragrance-store1.jpg"
                class="w-full h-64 object-cover"
            >
            <div class="p-6">
                <h3 class="font-semibold text-lg mb-1">YSL Libre</h3>
                <p class="text-sm text-gray-500 mb-0">Warm · Sensual</p>
                <div class="mt-4 font-bold text-lg">Rs. 110</div>
            </div>
        </div>

    </div>

    <div class="text-center">
        <a href="{{ route('products') }}" class="inline-block bg-black text-white px-8 py-3 rounded-full font-medium hover:bg-gray-800 transition">
            Explore More
        </a>
    </div>
</section>

@endsection
