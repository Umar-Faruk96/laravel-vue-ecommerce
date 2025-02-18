<x-app-layout>
    <form method="POST" action="{{ route('password.email') }}" class="mx-auto my-16 w-[400px] p-6">
        @csrf

        <h2 class="mb-5 text-center text-2xl font-semibold dark:text-gray-300">
            Enter your Email to reset password
        </h2>
        <p class="mb-6 text-center text-gray-500 dark:text-gray-400">
            or
            <a href="{{ route('login') }}"
                class="text-purple-600 hover:text-purple-500 dark:text-purple-400 hover:dark:text-purple-300">login with
                existing account</a>
        </p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div class="mb-3">
            <x-text-input id="loginEmail" type="email" name="email" placeholder="Your email address"
                :value="old('email')" required autofocus />

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <button class="btn-primary w-full bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700">
            Submit
        </button>
    </form>

    {{-- <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')"
                required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4 flex items-center justify-end">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form> --}}
</x-app-layout>
