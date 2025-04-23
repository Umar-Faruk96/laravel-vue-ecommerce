<template>
  <span
    class="text-white/90 px-2 py-1 rounded capitalize"
    :class="{
      'bg-gray-500': order.status === 'unpaid',
      'bg-yellow-500': order.status === 'pending',
      'bg-red-500': order.status === 'canceled',
      'bg-purple-500': order.status === 'paid',
      'bg-indigo-500': order.status === 'shipped',
      'bg-green-500': order.status === 'completed',
    }"
    >{{ order.status }}</span
  >
</template>

<script setup>
const { order } = defineProps({
  order: {
    type: Object,
    required: true,
  },
});
</script>
