@extends('layouts.customer')

@section('content')

<section class="max-w-4xl mx-auto px-6 py-12">
    <h1 class="text-3xl font-serif font-bold mb-8">Checkout</h1>
    
    <div class="bg-white p-8 rounded-xl shadow">
        <h2 class="text-xl font-semibold mb-6">Order Summary</h2>
        
        @if(session()->has('cart') && !empty(session()->get('cart')))
            <div class="space-y-4 mb-8">
                @php $total = 0; @endphp
                
                @foreach(session()->get('cart') as $item)
                    @php
                        $itemTotal = $item['price'] * $item['quantity'];
                        $total += $itemTotal;
                    @endphp
                    
                    <div class="flex justify-between items-center border-b pb-3">
                        <span>{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                        <span>Rs. {{ number_format($itemTotal, 2) }}</span>
                    </div>
                @endforeach
                
                <div class="flex justify-between items-center font-bold text-lg pt-3">
                    <span>Total:</span>
                    <span>Rs. {{ number_format($total, 2) }}</span>
                </div>
            </div>
            
            {{-- Show validation errors --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('checkout.place') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 mb-2">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="w-full border rounded-lg px-4 py-2" required>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="w-full border rounded-lg px-4 py-2" required>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" class="w-full border rounded-lg px-4 py-2" required>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Phone</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" class="w-full border rounded-lg px-4 py-2" required>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 mb-2">Address</label>
                        <input type="text" name="address" value="{{ old('address') }}" class="w-full border rounded-lg px-4 py-2" required>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 mb-2">City</label>
                        <input type="text" name="city" value="{{ old('city') }}" class="w-full border rounded-lg px-4 py-2" required>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">State</label>
                        <input type="text" name="state" value="{{ old('state') }}" class="w-full border rounded-lg px-4 py-2" required>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">ZIP Code</label>
                        <input type="text" name="zip" value="{{ old('zip') }}" class="w-full border rounded-lg px-4 py-2" required>
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Payment Method</label>
                    <select name="payment_method" class="w-full border rounded-lg px-4 py-2" required>
                        <option value="">Select Payment Method</option>
                        <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                        <option value="paypal" {{ old('payment_method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    </select>
                </div>
                
                <button type="submit" class="w-full bg-black text-white py-3 rounded-full hover:bg-gray-800">
                    Place Order
                </button>
            </form>
        @else
            <p>Your cart is empty. <a href="{{ route('products') }}" class="text-indigo-600">Continue shopping</a></p>
        @endif
    </div>
</section>

@endsection