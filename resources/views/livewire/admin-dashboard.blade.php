<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Welcome Message -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Welcome back, Admin!</h2>
            <p class="text-gray-600 mt-2">Here is an overview of your store's performance.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Products -->
            <div class="bg-indigo-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-indigo-500 bg-opacity-75">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="mb-2 text-sm font-medium opacity-80">Total Products</p>
                        <p class="text-3xl font-bold">{{ $totalProducts }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Customers -->
            <div class="bg-green-500 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-400 bg-opacity-75">
                         <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="mb-2 text-sm font-medium opacity-80">Total Customers</p>
                        <p class="text-3xl font-bold">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>

            <!-- Active Admins -->
            <div class="bg-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-500 bg-opacity-75">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="mb-2 text-sm font-medium opacity-80">Active Admins</p>
                        <p class="text-3xl font-bold">{{ $totalAdmins }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Quick Management</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h4 class="font-bold text-lg mb-2">Product Management</h4>
                    <p class="text-gray-600 mb-4">Add, edit, or remove perfumes from the catalog.</p>
                    <button class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Go to Products</button>
                    <!-- Note: This button will need wire:click or an 'a' tag to the route -->
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h4 class="font-bold text-lg mb-2">User Management</h4>
                    <p class="text-gray-600 mb-4">View and manage registered comments and users.</p>
                    <button class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">View Comments</button>
                </div>
            </div>
        </div>
    </div>
</div>
