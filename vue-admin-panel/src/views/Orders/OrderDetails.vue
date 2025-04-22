<template>
  <div class="flex items-center justify-between mb-3">
    <h1 class="text-black/60 text-3xl font-semibold">Orders</h1>
  </div>

  Order Details
</template>

<script setup>
import { useRoute } from "vue-router";
import { onMounted, ref } from "vue";
import store from "../../store/index.js";

const route = useRoute();

onMounted(() => {
  store.dispatch("getOrder", route.params.id);
});

// const orders = computed(() => store.state.orders);
</script>
