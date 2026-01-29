@extends('layouts.customer')

@section('content')
<div class="bg-gray-50 py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="text-center">
            <h2 class="text-base text-amber-600 font-semibold tracking-wide uppercase">Contact Us</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">Get in touch</p>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                Have a question about our fragrances or your order? We'd love to hear from you.
            </p>
        </div>

        <div class="mt-16 lg:grid lg:grid-cols-3 lg:gap-8">
            <!-- Contact Info -->
            <div class="col-span-1 bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-8">
                    <h3 class="text-lg font-medium text-gray-900">Contact Information</h3>
                    <p class="mt-4 text-base text-gray-500">
                        Our support team is available Monday through Friday, 9am to 6pm.
                    </p>
                    <div class="mt-6 flex items-center">
                        <svg class="flex-shrink-0 h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span class="ml-3 text-base text-gray-500">+1 (555) 123-4567</span>
                    </div>
                     <div class="mt-4 flex items-center">
                        <svg class="flex-shrink-0 h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="ml-3 text-base text-gray-500">support@aurascents.com</span>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="mt-8 lg:mt-0 col-span-2 bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-8">
                    <form action="#" method="POST" class="grid grid-cols-1 gap-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" autocomplete="name" class="py-3 px-4 block w-full shadow-sm focus:ring-amber-500 focus:border-amber-500 border-gray-300 rounded-md">
                            </div>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <div class="mt-1">
                                <input type="email" name="email" id="email" autocomplete="email" class="py-3 px-4 block w-full shadow-sm focus:ring-amber-500 focus:border-amber-500 border-gray-300 rounded-md">
                            </div>
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                            <div class="mt-1">
                                <textarea id="message" name="message" rows="4" class="py-3 px-4 block w-full shadow-sm focus:ring-amber-500 focus:border-amber-500 border border-gray-300 rounded-md"></textarea>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="w-full inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
