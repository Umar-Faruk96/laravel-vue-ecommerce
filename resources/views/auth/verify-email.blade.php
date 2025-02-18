<x-app-layout>
    <div class="w-[400px] mx-auto p-8 bg-gray-200 dark:bg-gray-700 rounded-sm">
        <div class="mb-4 text-gray-600 dark:text-gray-300">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>
        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 text-sm font-medium text-green-600 dark:text-green-400">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif
        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <div>
                    <button class="btn-primary w-full bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700">
                        {{ __('Resend Verification Email') }}
                    </button>
                </div>
            </form>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="rounded-md text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
