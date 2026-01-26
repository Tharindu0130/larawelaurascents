<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">

        <div class="max-w-md w-full bg-white rounded-2xl shadow-lg p-8">

            {{-- Logo / Title --}}
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold">Welcome Back</h1>
                <p class="text-gray-500 mt-1">
                    Sign in to continue shopping
                </p>
            </div>

            {{-- Validation Errors --}}
            <x-validation-errors class="mb-4" />

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

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
                        autofocus
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

                {{-- Remember Me --}}
                <div class="flex items-center mb-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input
                            id="remember_me"
                            type="checkbox"
                            class="rounded border-gray-300 text-amber-600 shadow-sm focus:ring-amber-500"
                            name="remember"
                        >
                        <span class="ms-2 text-sm text-gray-600">
                            Remember me
                        </span>
                    </label>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col gap-4">

                    <button
                        type="submit"
                        class="w-full bg-amber-600 text-white py-2 rounded-full hover:bg-amber-700 transition"
                    >
                        Login
                    </button>

                    <div class="text-center text-sm">
                        <a
                            href="{{ route('password.request') }}"
                            class="text-amber-600 hover:text-amber-700 hover:underline transition-colors"
                        >
                            Forgot your password?
                        </a>
                    </div>

                    <div class="text-center text-sm text-gray-600">
                        Don’t have an account?
                        <a
                            href="{{ route('register') }}"
                            class="text-amber-600 hover:text-amber-700 hover:underline transition-colors"
                        >
                            Register
                        </a>
                    </div>

                    <div class="text-center text-sm text-gray-600">
                        Admin?
                        <a
                            href="{{ route('admin.login.form') }}"
                            class="text-amber-600 hover:text-amber-700 hover:underline transition-colors"
                        >
                            Admin Login
                        </a>
                    </div>

                </div>
            </form>

        </div>
    </div>
</x-guest-layout>
