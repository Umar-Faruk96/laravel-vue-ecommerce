<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel Vue E-Commerce') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @if (config('app.env') === 'local')
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <link rel="stylesheet" href="{{ asset('app-Bvv67mj1.css') }}">
            <script defer src="{{ asset('app-B7tcnaoN.js') }}"></script>
        @endif

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    </head>

    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')
            <!-- Page Content -->
            <main class="p-5">
                {{ $slot }}
            </main>

            <!-- Toast -->
            <div x-data="toast" x-show="visible" x-transition x-cloak @notify.window="show($event.detail.message)"
                 class="fixed left-1/2 top-16 -ml-[200px] w-[400px] bg-emerald-500 px-4 py-2 pb-4 text-white">
                <div class="font-semibold" x-text="message"></div>

                <button @click="close"
                        class="absolute right-2 top-2 flex h-[30px] w-[30px] items-center justify-center rounded-full transition-colors hover:bg-black/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Progress -->
                <div>
                    <div class="absolute bottom-0 left-0 right-0 h-[6px] bg-black/10"
                         :style="{ 'width': `${percent}%` }">
                    </div>
                </div>
            </div>
            <!--/ Toast -->
        </div>
    </body>

</html>