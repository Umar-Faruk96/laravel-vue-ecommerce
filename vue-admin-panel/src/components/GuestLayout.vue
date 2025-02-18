<script setup>

const { title } = defineProps({
  title: {
    type: String,
    required: true,
  },
});

</script>

<template>
  <section class="flex flex-col min-h-screen bg-gray-200">
    <div class="flex min-h-full flex-1 flex-col justify-center py-12 sm:px-6 lg:px-8">
      <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- <img
          alt="Your Company"
          src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&amp;shade=600"
          class="mx-auto h-10 w-auto"
        /> -->
        <h2 class="mt-6 text-center text-2xl font-bold tracking-tight text-gray-700">
          {{ title }}
        </h2>
      </div>

      <slot />
    </div>
  </section>
</template>

<style lang="scss" scoped></style>