<x-app-layout>
    <form method="POST" action="{{ route('login') }}" class="mx-auto my-16 w-[400px] p-6">
        @csrf

        <h2 class="mb-5 text-center text-2xl font-semibold dark:text-gray-300">
            Login to your account
        </h2>
        <p class="mb-6 text-center text-gray-500 dark:text-gray-400">
            or
            <a href="{{ route('register') }}"
                class="text-sm text-purple-700 hover:text-purple-600 dark:text-purple-400 hover:dark:text-purple-300">create
                new account</a>
        </p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Email Address -->
        <div class="mb-4">
            <x-text-input id="loginEmail" type="email" name="email" placeholder="Your email address" required
                :value="old('email')" autofocus />

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-text-input id="loginPassword" type="password" name="password" placeholder="Your password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="mb-5 flex items-center justify-between">
            <div class="flex items-center">
                <input id="loginRememberMe" type="checkbox"
                    class="mr-3 rounded border-gray-300 text-purple-500 focus:ring-purple-500" name="remember" />
                <label for="loginRememberMe" class="dark:text-gray-400">Remember Me</label>
            </div>

            {{-- Forgot Password --}}
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    class="text-sm text-purple-700 hover:text-purple-600 dark:text-purple-400 hover:dark:text-purple-300">Forgot
                    Password?</a>
            @endif

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="btn-primary w-full bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700">
            Login
        </button>
    </form>
    <!-- Session Status -->
    {{-- <x-auth-session-status class="mb-4" :status="session('status')" />

    <form>
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="mt-4 block">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-4 flex items-center justify-end">
            @if (Route::has('password.request'))
                <a class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form> --}}
</x-app-layout>
