<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center relative overflow-hidden px-4">

        {{-- Video Background --}}
        <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover">
            <source src="{{ asset('videos/register-bg.mp4') }}" type="video/mp4">
        </video>
        
        {{-- Dark Overlay --}}
        <div class="absolute inset-0 bg-black/40"></div>

        <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8 relative z-10">

            {{-- Title --}}
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold">Create Account</h1>
                <p class="text-gray-500 mt-1">
                    Join us and start shopping
                </p>
            </div>

            {{-- Validation Errors --}}
            <x-validation-errors class="mb-4" />

            {{-- Register Form --}}
            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Name --}}
                <div class="mb-4">
                    <x-label for="name" value="Full Name" />
                    <x-input
                        id="name"
                        class="block mt-1 w-full rounded-xl border-gray-300 focus:border-amber-500 focus:ring-amber-500"
                        type="text"
                        name="name"
                        :value="old('name')"
                        required
                        autofocus
                    />
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <x-label for="email" value="Email Address" />
                    <x-input
                        id="email"
                        class="block mt-1 w-full rounded-xl border-gray-300 focus:border-amber-500 focus:ring-amber-500"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                    />
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <x-label for="password" value="Password" />
                    <x-input
                        id="password"
                        class="block mt-1 w-full rounded-xl border-gray-300 focus:border-amber-500 focus:ring-amber-500"
                        type="password"
                        name="password"
                        required
                    />
                </div>

                {{-- Confirm Password --}}
                <div class="mb-6">
                    <x-label for="password_confirmation" value="Confirm Password" />
                    <x-input
                        id="password_confirmation"
                        class="block mt-1 w-full rounded-xl border-gray-300 focus:border-amber-500 focus:ring-amber-500"
                        type="password"
                        name="password_confirmation"
                        required
                    />
                </div>

                {{-- Actions --}}
                <div class="flex flex-col gap-4">

                    <button
                        type="submit"
                        class="w-full bg-amber-600 text-white py-2 rounded-full hover:bg-amber-700 transition"
                    >
                        Register
                    </button>

                    <div class="text-center text-sm text-gray-600">
                        Already have an account?
                        <a
                            href="{{ route('login') }}"
                            class="text-amber-600 hover:text-amber-700 hover:underline transition-colors"
                        >
                            Login
                        </a>
                    </div>

                </div>
            </form>

        </div>
    </div>
</x-guest-layout>
