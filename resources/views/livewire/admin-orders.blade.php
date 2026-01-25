<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Order Management</h2>

        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow" role="alert">
                <p>{{ session('message') }}</p>
            </div>
        @endif

        <!-- Filters -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex-1">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by Order ID or Customer..." class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-indigo-500 transition">
            </div>
            <div class="w-full md:w-48">
                <select wire:model.live="statusFilter" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap font-mono text-sm">
                                #{{ $order->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $order->user->name ?? 'Unknown' }}</div>
                                <div class="text-sm text-gray-500">{{ $order->user->email ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->product->name ?? 'Deleted Product' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-sm">
                                Rs. {{ number_format($order->total_price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->created_at->format('M d, H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select wire:change="updateStatus({{ $order->id }}, $event.target.value)" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                                    {{ $order->status === 'completed' ? 'text-green-700 bg-green-50' : 
                                       ($order->status === 'pending' ? 'text-yellow-700 bg-yellow-50' : 
                                       ($order->status === 'cancelled' ? 'text-red-700 bg-red-50' : 'text-blue-700 bg-blue-50')) }}">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="viewDetails({{ $order->id }})" class="text-indigo-600 hover:text-indigo-900">Details</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

    <!-- Order Details Modal -->
    @if($isDetailModalOpen && $selectedOrder)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeDetailModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4 text-center sm:text-left">
                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                                Order #{{ $selectedOrder->id }} Details
                            </h3>
                            <p class="text-sm text-gray-500">Placed on {{ $selectedOrder->created_at->format('F d, Y at h:i A') }}</p>
                        </div>

                        <div class="border-t border-b border-gray-200 py-4 my-4">
                            <h4 class="font-semibold text-sm text-gray-700 mb-2">Customer Info</h4>
                            <p class="text-sm"><span class="font-medium">Name:</span> {{ $selectedOrder->user->name }}</p>
                            <p class="text-sm"><span class="font-medium">Email:</span> {{ $selectedOrder->user->email }}</p>
                            <div class="mt-2 text-sm">
                                <span class="font-medium">Shipping Address:</span><br>
                                {{-- We would use the address if stored in order table, but simplified model was used --}}
                                <span class="text-gray-500 italic">Address stored in order details if available</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h4 class="font-semibold text-sm text-gray-700 mb-3">Product</h4>
                            <div class="flex items-center">
                                @if($selectedOrder->product->image)
                                    <img src="{{ $selectedOrder->product->image }}" class="h-16 w-16 rounded object-cover border">
                                @endif
                                <div class="ml-4">
                                    <p class="font-bold text-gray-900">{{ $selectedOrder->product->name }}</p>
                                    <p class="text-sm text-gray-500">Qty: {{ $selectedOrder->quantity }}</p>
                                    <p class="text-sm font-bold text-indigo-600">Rs. {{ number_format($selectedOrder->total_price, 2) }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="closeDetailModal" type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
