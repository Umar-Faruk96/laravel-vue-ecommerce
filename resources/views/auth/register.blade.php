<x-app-layout>
    <form method="POST" action="{{ route('register') }}" class="mx-auto my-16 w-[400px] p-6">
        @csrf

        <h2 class="mb-4 text-center text-2xl font-semibold dark:text-gray-300">Create an account</h2>
        <p class="mb-3 text-center text-gray-500 dark:text-gray-400">
            or
            <a href="{{ route('login') }}"
                class="text-sm text-purple-700 hover:text-purple-600 dark:text-purple-400 hover:dark:text-purple-300">login
                with existing
                account</a>
        </p>

        <!-- Name -->
        <div class="mb-4">
            <x-text-input placeholder="Your name" type="text" name="name" :value="old('name')" required autofocus
                autocomplete="name" />

            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mb-4">
            <x-text-input placeholder="Your Email" type="email" name="email" :value="old('email')" required
                autocomplete="username" />

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-text-input placeholder="Password" type="password" name="password" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <x-text-input placeholder="Repeat Password" type="password" name="password_confirmation" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button class="btn-primary w-full bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700">
            Signup
        </button>
    </form>

    {{-- <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')"
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="mt-1 block w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-4 flex items-center justify-end">
            <a class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form> --}}
</x-app-layout>
