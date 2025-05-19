<template>
  <h1 class="text-gray-700 text-3xl font-bold">Dashboard</h1>

  <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 items-stretch justify-between gap-3 dark:text-gray-500 my-4">
    <div
        class="flex flex-col justify-center items-center bg-emerald-50 border border-cyan-100 px-6 py-4 rounded shadow space-y-2">
      <h4 class="text-2xl font-semibold text-center">Active Customers</h4>
      <p class="text-xl font-medium">100,000,000</p>
    </div>
    <div
        class="flex flex-col justify-center items-center bg-lime-50 border border-cyan-100 px-6 py-4 rounded shadow space-y-2">
      <h4 class="text-2xl font-semibold text-center">Active Orders</h4>
      <p class="text-xl font-medium">100,000,000</p>
    </div>
    <div
        class="flex flex-col justify-center items-center bg-teal-50 border border-cyan-100 px-6 py-4 rounded shadow space-y-2">
      <h4 class="text-2xl font-semibold text-center">Paid Orders</h4>
      <p class="text-xl font-medium">100,000,000</p>
    </div>
    <div
        class="flex flex-col justify-center items-center bg-green-50 border border-cyan-100 px-6 py-4 rounded shadow space-y-2">
      <h4 class="text-2xl font-semibold text-center">Total Sales</h4>
      <p class="text-xl font-medium">$100,000,000</p>
    </div>
  </section>
</template>

<script setup>

</script>