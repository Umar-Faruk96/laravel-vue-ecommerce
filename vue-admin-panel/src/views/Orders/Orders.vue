<template>
  <div class="flex items-center justify-between mb-3">
    <h1 class="text-black/60 text-3xl font-semibold">Orders</h1>
  </div>

  <OrdersTable @show-order="viewOrder" />
</template>

<script setup>
import { computed, ref } from "vue";
import store from "../../store/index.js";
import OrdersTable from "./OrdersTable.vue";

const orders = computed(() => store.state.orders);

function viewOrder(p) {}
</script>
