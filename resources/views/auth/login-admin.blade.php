<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">

        <div class="max-w-md w-full bg-white rounded-2xl shadow-lg p-8">

            {{-- Logo / Title --}}
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold">Welcome Back</h1>
                <p class="text-gray-500 mt-1">
                    Admin sign in
                </p>
            </div>

            {{-- Validation Errors --}}
            <x-validation-errors class="mb-4" />

            {{-- Login Form --}}
            <form method="POST" action="{{ route('admin.login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <x-label for="email" value="Email Address" />
                    <x-input
                        id="email"
                        class="block mt-1 w-full rounded-xl"
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
                        class="block mt-1 w-full rounded-xl"
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
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
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
                        class="w-full bg-indigo-600 text-white py-2 rounded-full hover:bg-indigo-700 transition"
                    >
                        Login
                    </button>

                    <div class="text-center text-sm">
                        <a
                            href="{{ route('password.request') }}"
                            class="text-indigo-600 hover:underline"
                        >
                            Forgot your password?
                        </a>
                    </div>

                    <div class="text-center text-sm text-gray-600">
                        User account?
                        <a
                            href="{{ route('login') }}"
                            class="text-indigo-600 hover:underline"
                        >
                            User Login
                        </a>
                    </div>

                </div>
            </form>

        </div>
    </div>
</x-guest-layout>
