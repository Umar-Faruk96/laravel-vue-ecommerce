<template>
    <main class="bg-white/85 mx-auto py-16 min-h-screen flex flex-col items-center justify-center sm:rounded-lg shadow">
        <div
            class="w-full text-center md:text-left md:flex items-center justify-center gap-4 md:gap-8 md:divide-x-2 divide-black/10">
            <h1 class="text-5xl md:text-7xl font-bold text-indigo-400">404</h1>
            <div class="px-8 space-y-1">
                <h4 class="text-3xl md:text-5xl font-extrabold text-black/70">Page not found</h4>
                <p class="text-base md:text-2xl text-black/60">Please check the URL in the address bar and try again.
                </p>
            </div>
        </div>
        <div class="mt-6 md:mt-12 md:-ml-16 font-semibold sm:flex sm:gap-3">
            <router-link :to="{ name: 'app' }"
                class="block bg-gradient-to-r from-indigo-400 to-blue-500 hover:from-indigo-500 hover:to-blue-600 text-white/80 transition-colors px-3 py-1.5 md:px-6 md:py-3 rounded-md">
                Go back home
            </router-link>
            <router-link :to="{ name: 'app.dashboard' }"
                class="block bg-indigo-400/45 text-indigo-700 hover:bg-indigo-400/35 hover:text-indigo-600 transition-colors px-3 py-1.5 md:px-6 md:py-3 rounded-md mt-1 sm:mt-0">
                Contact support
            </router-link>
        </div>
    </main>

</template>

<script setup>

</script>