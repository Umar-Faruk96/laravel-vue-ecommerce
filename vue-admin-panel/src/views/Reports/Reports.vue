<template>
  <section class="space-y-4">
    <h1 class="text-black/60 dark:text-white/70 sm:text-3xl font-semibold">Reports</h1>

    <div
      class="flex items-center justify-between lg:justify-start gap-2 sm:gap-4 text-gray-700 dark:text-gray-300 font-semibold sm:text-lg"
    >
      <router-link
        :to="{ name: 'app.reports.orders' }"
        class="w-1/2 lg:w-1/4 bg-gray-300 dark:bg-gray-500 px-2 py-1 sm:px-4 sm:py-2 rounded-md transition-all text-center"
        active-class="bg-indigo-500 dark:bg-indigo-700"
      >
        Orders
      </router-link>
      <router-link
        :to="{ name: 'app.reports.customers' }"
        class="w-1/2 lg:w-1/4 bg-gray-300 dark:bg-gray-500 px-2 py-1 sm:px-4 sm:py-2 rounded-md transition-all text-center"
        active-class="bg-indigo-500 dark:bg-indigo-700"
      >
        Customers
      </router-link>
    </div>

    <div
      class="bg-black/10 p-4 rounded-lg shadow animate-fade-in-down"
      style="animation-delay: 0.3s"
    >
      <router-view></router-view>
    </div>
  </section>
</template>

<script setup></script>
