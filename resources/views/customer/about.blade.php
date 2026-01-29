@extends('layouts.customer')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <div class="relative bg-gray-900 text-white">
        <div class="absolute inset-0">
            <img class="w-full h-full object-cover opacity-30" src="https://www.essenza.ng/cdn/shop/articles/733068459365d1aab72388dbfef2ec01.jpg?v=1627477859" alt="Perfume background">
        </div>
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">About Aura Scents</h1>
            <p class="mt-6 text-xl max-w-3xl">Crafting memories through the art of fragrance. Discover the essence of elegance.</p>
        </div>
    </div>

    <!-- Our Story -->
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-24 lg:px-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-8 lg:items-center">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Our Story</h2>
                <p class="mt-3 max-w-3xl text-lg text-gray-500">
                    Founded in 2024, Aura Scents began with a simple mission: to bring luxurious, high-quality fragrances to everyone. We believe that a scent is more than just a smell—it's an identity, a memory, and a statement.
                </p>
                <p class="mt-3 max-w-3xl text-lg text-gray-500">
                    Our team of expert perfumers works tirelessly to source the finest ingredients from around the world, blending them into unique compositions that captivate the senses.
                </p>
                <div class="mt-8 sm:flex">
                    <div class="rounded-md shadow">
                        <a href="{{ route('products') }}" class="flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700">
                            Shop Collection
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-8 lg:mt-0">
                <div class="relative mx-auto w-full rounded-lg shadow-lg lg:max-w-md">
                   <img class="w-full rounded-lg" src="https://cdn.riah.ae/storage/upload/images/2025/01/20/678dfe4020362.jpg" alt="Perfume bottle">
                </div>
            </div>
        </div>
    </div>

    <!-- Values Section -->
    <div class="bg-gray-50">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-amber-600 font-semibold tracking-wide uppercase">Our Values</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl"> Why Choose Aura Scents?</p>
            </div>

            <div class="mt-10">
                <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-3 md:gap-x-8 md:gap-y-10">
                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-amber-500 text-white">
                                <!-- Heroicon name: outline/check -->
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Authentic Ingredients</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            We use only 100% authentic and ethically sourced ingredients to ensure the highest quality.
                        </dd>
                    </div>

                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-amber-500 text-white">
                                <!-- Heroicon name: outline/heart -->
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Cruelty Free</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            Our products are never tested on animals. We believe in beauty without cruelty.
                        </dd>
                    </div>

                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-amber-500 text-white">
                                <!-- Heroicon name: outline/truck -->
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Fast Shipping</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            We ship worldwide with secure packaging to ensure your perfumes arrive safely and on time.
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
