@extends('layouts.customer')

@section('content')

<section class="max-w-6xl mx-auto px-6 py-12">

    <h1 class="text-3xl font-serif font-bold mb-8">
        Shopping Cart
    </h1>

    @if(session()->has('cart') && !empty(session()->get('cart')))
        <div class="space-y-6">

            @php $total = 0; @endphp

            @foreach(session()->get('cart') as $productId => $item)
                @php
                    $itemTotal = $item['price'] * $item['quantity'];
                    $total += $itemTotal;
                @endphp

                <div class="flex items-center justify-between bg-white p-6 rounded-xl shadow">
                    <div class="flex items-center gap-4">
                        <img
                            src="{{ $item['image'] ?? 'https://via.placeholder.com/100' }}"
                            alt="{{ $item['name'] }}"
                            class="w-20 h-20 object-cover rounded"
                        >

                        <div>
                            <h3 class="font-semibold text-lg">
                                {{ $item['name'] }}
                            </h3>
                            <p class="text-gray-500 text-sm">
                                ${{ number_format($item['price'], 2) }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <form action="{{ route('cart.update', $productId) }}" method="POST" class="flex items-center">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" 
                                   class="w-16 border rounded px-2 py-1 text-center" onchange="updateCart(this.form)">
                        </form>
                        
                        <form action="{{ route('cart.remove', $productId) }}" method="POST" style="display:inline;" onsubmit="return removeFromCart(event, this);">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </form>
                        
                        <div class="text-right ml-4">
                            <p class="text-sm text-gray-500">
                                Qty: {{ $item['quantity'] }}
                            </p>
                            <p class="font-bold text-lg">
                                ${{ number_format($itemTotal, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Cart Summary --}}
            <div class="flex justify-end">
                <div class="bg-gray-100 p-6 rounded-xl w-80">
                    <p class="flex justify-between mb-2 text-lg">
                        <span>Total</span>
                        <span class="font-bold">
                            ${{ number_format($total, 2) }}
                        </span>
                    </p>

                    <a href="{{ route('checkout') }}"
                       class="block text-center bg-black text-white py-3 rounded-full mt-4 hover:bg-gray-800">
                        Proceed to Checkout
                    </a>
                </div>
            </div>

        </div>
    @else
        <p class="text-gray-500">Your cart is empty.</p>
    @endif

    <!-- JavaScript for cart updates -->
    <script>
        function updateCart(form) {
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'PATCH',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload(); // Reload to update cart and counts
                } else {
                    console.error('Failed to update cart item');
                }
            })
            .catch(error => {
                console.error('Error updating cart item:', error);
            });
        }

        function removeFromCart(event, form) {
            event.preventDefault();
            
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'DELETE',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload(); // Reload to update cart and counts
                } else {
                    console.error('Failed to remove cart item');
                }
            })
            .catch(error => {
                console.error('Error removing cart item:', error);
            });
        }
    </script>

</section>

@endsection