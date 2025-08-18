<script setup>

import {ref} from "vue";

const props = defineProps({
  errors: {
    type: Array,
    required: false,
  },
  name: {
    type: String,
    required: true,
  }
})

const images = defineModel('images');
const deletedImages = defineModel('deletedImages');
const imagePositions = defineModel('imagePositions');

const files = ref([]);
const imageUrls = ref([]);

const onFileChange = (event) => {
  // console.log(event);
  files.value = [...files.value, event.target.files];

  for (const file of event.target.files) {
    readFile(file).then(url => {
      imageUrls.value = [...imageUrls.value, url];
    })
  }
}

const readFile = (file) => {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => resolve(reader.result);
    reader.onerror = (error) => reject(error);
  })
}

</script>

<template>
  <section>
    <div class="flex flex-wrap gap-1">
      <div
          class="relative w-[120px] h-[120px] rounded border border-dashed flex items-center justify-center hover:border-purple-500 overflow-hidden">

      </div>
      <div
          class="relative w-[120px] h-[120px] rounded border border-dashed flex items-center justify-center hover:border-purple-500 dark:hover:border-purple-300 overflow-hidden">
      <span>
        Upload
      </span>
        <input type="file"
               class="absolute w-full h-full opacity-0"
               @change="onFileChange" multiple>
      </div>
    </div>
    <small v-if="errors && errors[0]"
           class="text-red-600 dark:text-red-300 dark:font-semibold">{{
        errors[0]
      }}</small>
  </section>
</template>

<style scoped>

</style>