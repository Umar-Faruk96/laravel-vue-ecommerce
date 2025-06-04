<template>
  <div class="flex items-center justify-between mb-3">
    <h1 class="text-black/60 dark:text-white/70 sm:text-3xl font-semibold">Orders</h1>
  </div>

  <OrdersTable />
</template>

<script setup>
import OrdersTable from "./OrdersTable.vue";
// import { computed, ref } from "vue";
// import store from "../../store/index.js";

// const orders = computed(() => store.state.orders);
</script>
