<template>
  <span
    class="px-2 py-1 rounded capitalize"
    :class="{
      'bg-gray-500 text-gray-50': order.status === 'unpaid',
      'bg-yellow-500 text-blue-700': order.status === 'pending',
      'bg-red-500 text-indigo-800': order.status === 'cancelled',
      'bg-purple-500': order.status === 'paid',
      'bg-indigo-500': order.status === 'shipped',
      'bg-green-500 text-indigo-600': order.status === 'completed',
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
