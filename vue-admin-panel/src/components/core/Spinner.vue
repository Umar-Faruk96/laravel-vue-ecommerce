<template>
  <section class="flex items-center justify-center" :class>
    <main class="flex flex-col items-center text-gray-700 dark:text-gray-200">
      <svg class="animate-spin -ml-1 h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
        </path>
      </svg>
      <div class="mt-2" v-if="text">{{ text }}</div>
    </main>
  </section>
</template>
<script setup>
defineProps({
  text: {
    type: String,
    default: "Please Wait..."
  },
  class: {
    type: String,
    default: "bg-gray-200"
  }
})
</script>