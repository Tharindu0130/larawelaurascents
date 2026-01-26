@extends('layouts.customer')

@section('styles')
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        .animate-shimmer {
            background: linear-gradient(to right, #f0f0f0 0%, #f8f8f8 50%, #f0f0f0 100%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite linear;
        }
    </style>
@endsection

@section('content')

<!-- VIDEO HERO SECTION -->
<section class="relative w-full h-[80vh] overflow-hidden">
    <div class="absolute inset-0">
        <div class="w-full h-full overflow-hidden">
            <video autoplay muted loop playsinline class="w-full h-full min-w-full min-h-full object-cover object-center">
                <source src="{{ asset('videos/landing video.mp4') }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    </div>
    
    <div class="relative z-10 h-full flex items-center justify-center px-4">
        <div class="text-center max-w-4xl opacity-0 animate-fade-in-down">
            <p class="text-xl md:text-2xl text-white mb-6 tracking-widest uppercase opacity-0 transform transition-all duration-700 translate-y-10 delay-100 animate-fade-in-up">
                Luxury Fragrances
            </p>
            
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-serif font-bold text-white mb-8 leading-tight opacity-0 transform transition-all duration-700 translate-y-10 delay-200 animate-fade-in-up">
                Discover Your <br> Signature Scent
            </h1>

            <p class="text-lg md:text-xl text-white mb-10 max-w-2xl mx-auto opacity-0 transform transition-all duration-700 translate-y-10 delay-300 animate-fade-in-up">
                Experience timeless elegance with our curated collection of
                premium perfumes crafted for every personality.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center opacity-0 transform transition-all duration-700 translate-y-10 delay-400 animate-fade-in-up">
                <a href="{{ route('products') }}"
                   class="bg-white text-black px-8 py-4 rounded-full text-base font-medium hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 hover:shadow-xl shadow-lg">
                    Shop Now
                </a>

                <a href="{{ route('about') }}"
                   class="border-2 border-white text-white px-8 py-4 rounded-full text-base font-medium hover:bg-white hover:text-black transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                    Learn More
                </a>
            </div>
        </div>
    </div>
</section>

<!-- BRAND STORY SECTION -->
<section class="max-w-full mx-auto px-6 py-20 bg-gradient-to-r from-gray-50 to-gray-100 rounded-3xl my-16 animate-on-scroll opacity-0 -translate-y-10 transition-all duration-700">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div class="space-y-6">
            <h2 class="text-4xl font-serif font-bold text-gray-900">
                Our Story
            </h2>
            <p class="text-lg text-gray-700 leading-relaxed">
                Founded with a passion for luxury fragrances, Aura Scents brings you authentic, premium perfumes
                from the world's most prestigious brands. Each scent tells a unique story of craftsmanship,
                tradition, and innovation.
            </p>
            <div class="grid grid-cols-2 gap-8 pt-6">
                <div class="text-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="text-3xl font-bold text-amber-600">100+</div>
                    <div class="text-gray-600">Premium Brands</div>
                </div>
                <div class="text-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="text-3xl font-bold text-amber-600">500+</div>
                    <div class="text-gray-600">Unique Scents</div>
                </div>
            </div>
        </div>
        <div class="relative">
            <img src="{{ asset('images/brand-image.png') }}" 
                 alt="Aura Scents Brand" 
                 class="rounded-xl w-full h-96 object-cover shadow-2xl">
            <div class="absolute -top-6 -right-6 w-32 h-32 bg-amber-100 rounded-full animate-float"></div>
            <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-amber-100 rounded-full animate-float" style="animation-delay: -2s;"></div>
        </div>
    </div>
</section>

<!-- FEATURED COLLECTION -->
<section class="max-w-7xl mx-auto px-6 py-16">

    <h2 class="text-3xl font-serif font-bold text-center mb-12 animate-on-scroll opacity-0 -translate-y-10 transition-all duration-700">
        Featured Collection
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10 mb-12">

        <!-- CARD -->
        <div class="bg-white rounded-2xl shadow-md transition-all duration-500 overflow-hidden transform hover:-translate-y-2 hover:shadow-2xl animate-on-scroll opacity-0 -translate-y-10 transition-all duration-700">
            <div class="relative overflow-hidden">
                <img
                    src="https://scentminis.lk/wp-content/uploads/2024/10/channle-no-5.png"
                    class="w-full h-64 object-cover transition-transform duration-500 hover:scale-110"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                    <button class="bg-white text-black px-4 py-2 rounded-full text-sm font-medium opacity-0 hover:opacity-100 transition-all duration-300 transform translate-y-4 hover:translate-y-0">
                        Quick View
                    </button>
                </div>
            </div>
            <div class="p-6">
                <h3 class="font-semibold text-lg mb-1">Chanel No.5</h3>
                <p class="text-sm text-gray-500 mb-0">Floral · Elegant</p>
                <div class="mt-4 font-bold text-lg">Rs. 120</div>
            </div>
        </div>

        <!-- CARD -->
        <div class="bg-white rounded-2xl shadow-md transition-all duration-500 overflow-hidden transform hover:-translate-y-2 hover:shadow-2xl animate-on-scroll opacity-0 -translate-y-10 transition-all duration-700 delay-100">
            <div class="relative overflow-hidden">
                <img
                    src="https://theperfumestore.lk/wp-content/uploads/2020/10/Dior-Sauvage-eau-de-Parfum-for-him-for-men-edp-Branded-Original-authentic-fragrance-Perfume-store-in-Sri-Lanka.jpg"
                    class="w-full h-64 object-cover transition-transform duration-500 hover:scale-110"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                    <button class="bg-white text-black px-4 py-2 rounded-full text-sm font-medium opacity-0 hover:opacity-100 transition-all duration-300 transform translate-y-4 hover:translate-y-0">
                        Quick View
                    </button>
                </div>
            </div>
            <div class="p-6">
                <h3 class="font-semibold text-lg mb-1">Dior Sauvage</h3>
                <p class="text-sm text-gray-500 mb-0">Fresh · Bold</p>
                <div class="mt-4 font-bold text-lg">Rs. 98</div>
            </div>
        </div>

        <!-- CARD -->
        <div class="bg-white rounded-2xl shadow-md transition-all duration-500 overflow-hidden transform hover:-translate-y-2 hover:shadow-2xl animate-on-scroll opacity-0 -translate-y-10 transition-all duration-700 delay-200">
            <div class="relative overflow-hidden">
                <img
                    src="https://theperfumestore.lk/wp-content/uploads/2024/11/YSL-libre-intense-edp-eau-de-parfum-edp-for-her-for-women-branded-original-authentic-fragrance-perfume-stotre-in-sri-lanka-best-trusted-authentic-fragrance-store1.jpg"
                    class="w-full h-64 object-cover transition-transform duration-500 hover:scale-110"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                    <button class="bg-white text-black px-4 py-2 rounded-full text-sm font-medium opacity-0 hover:opacity-100 transition-all duration-300 transform translate-y-4 hover:translate-y-0">
                        Quick View
                    </button>
                </div>
            </div>
            <div class="p-6">
                <h3 class="font-semibold text-lg mb-1">YSL Libre</h3>
                <p class="text-sm text-gray-500 mb-0">Warm · Sensual</p>
                <div class="mt-4 font-bold text-lg">Rs. 110</div>
            </div>
        </div>

    </div>

    <div class="text-center animate-on-scroll opacity-0 -translate-y-10 transition-all duration-700 delay-300">
        <a href="{{ route('products') }}" class="inline-block bg-amber-600 text-white px-8 py-4 rounded-full font-medium hover:bg-amber-700 transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
            Explore More Collection
        </a>
    </div>
</section>

<!-- TESTIMONIALS SECTION -->
<section class="max-w-7xl mx-auto px-6 py-20">
    <h2 class="text-3xl font-serif font-bold text-center mb-16 animate-on-scroll opacity-0 -translate-y-10 transition-all duration-700">
        What Our Customers Say
    </h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 animate-on-scroll opacity-0 -translate-y-10 transition-all duration-700 delay-100">
            <div class="text-yellow-400 text-2xl mb-4">★★★★★</div>
            <p class="text-gray-700 mb-6 italic">"The quality of the perfumes exceeded my expectations. Authentic scents at great prices!"</p>
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gray-300 rounded-full mr-4"></div>
                <div>
                    <div class="font-semibold">Sarah Johnson</div>
                    <div class="text-sm text-gray-500">Verified Customer</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-8 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 animate-on-scroll opacity-0 -translate-y-10 transition-all duration-700 delay-200">
            <div class="text-yellow-400 text-2xl mb-4">★★★★★</div>
            <p class="text-gray-700 mb-6 italic">"Fast delivery and amazing customer service. Will definitely order again!"</p>
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gray-300 rounded-full mr-4"></div>
                <div>
                    <div class="font-semibold">Michael Chen</div>
                    <div class="text-sm text-gray-500">Verified Customer</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-8 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 animate-on-scroll opacity-0 -translate-y-10 transition-all duration-700 delay-300">
            <div class="text-yellow-400 text-2xl mb-4">★★★★★</div>
            <p class="text-gray-700 mb-6 italic">"The subscription service is perfect. Love discovering new scents every month!"</p>
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gray-300 rounded-full mr-4"></div>
                <div>
                    <div class="font-semibold">Emma Rodriguez</div>
                    <div class="text-sm text-gray-500">Verified Customer</div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
