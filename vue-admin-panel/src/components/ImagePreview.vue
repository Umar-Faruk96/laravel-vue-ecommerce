<script setup>

import {ref, onMounted, watch} from "vue";
import {v4 as uuIdv4} from 'uuid';

const props = defineProps({
  errors: {
    type: Array,
    required: false,
  },
  name: {
    type: String,
    required: true,
  },
  imageCollections: {
    type: Array,
    required: false,
  }
})

const images = defineModel('images');
const deletedImages = defineModel('deletedImages');
const imagePositions = defineModel('imagePositions');

const files = ref([]);
const imageUrls = ref([]);

onMounted(() => {
  updateModels();
  /*if (props.imageCollections?.length) {
    imageUrls.value = props.imageCollections;
  }*/
})

const updateModels = () => {
  images.value = images.value || files.value;
  deletedImages.value = deletedImages.value || [];
}

watch(() => props.imageCollections, (newImages) => {
  if (!newImages?.length) return;
  // debugger

  const newImageUrls = newImages
      .filter(image => image?.url &&
          !imageUrls.value.some(existingImage => existingImage?.id ===
              image?.id))
      .map(newImage => ({
        ...newImage,
        imagesFound: true
      }))

  if (newImageUrls.length) {
    imageUrls.value = [...newImageUrls];
  }
}, {immediate: true, deep: true})

const uploadFiles = (event) => {
  const filesWithIds = [...event.target.files].map(file => {
    file.id = uuIdv4();
    return file;
  });

  files.value = [...files.value, ...filesWithIds];
  images.value = [...files.value];

  filesWithIds.forEach(file => {
    if (file instanceof File) {
      readFile(file)
          .then(url => {
            imageUrls.value = [
              ...imageUrls.value,
              {
                id: file.id,
                url,
                imagesFound: false
              }
            ];
          })
          .catch(err => {
            console.error('Error reading file:', err);
          });
    } else {
      console.error('Invalid file object:', file);
    }
  });

  event.target.value = null;
}

const readFile = (file) => {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => resolve(reader.result);
    reader.onerror = (error) => reject(error);
  })
}

const removeImage = (image) => {
  if (image.imagesFound) {
    deletedImages.value.push(image.id);
    image.toBeDeleted = true

    deletedImages.value = [...deletedImages.value];
  } else {
    files.value = files.value.filter(file => file.id !== image.id);
    imageUrls.value = imageUrls.value.filter(img => img.id !== image.id);

    images.value = [...files.value];
  }
}

const revertImageDelete = (image) => {
  image.toBeDeleted = false;
  deletedImages.value = deletedImages.value.filter(id => id !== image.id);
}
</script>

<template>
  <section>
    <div class="flex flex-wrap gap-1">
      <div v-for="image of imageUrls"
           class="relative w-[120px] h-[120px] rounded border border-dashed flex items-center justify-center hover:border-purple-500 dark:hover:border-purple-300 overflow-hidden">
        <img :src="image.url" alt="" class="w-full h-full object-cover"
             :class="{'opacity-50': image.toBeDeleted}"/>

        <small v-if="image.toBeDeleted"
               class="absolute left-0 bottom-0 right-0 py-1 px-2 bg-black w-100 text-white justify-between items-center flex">
          To be deleted
          <svg xmlns="http://www.w3.org/2000/svg" fill="none"
               viewBox="0 0 24 24" stroke-width="1.5"
               stroke="currentColor" class="w-4 h-4 cursor-pointer"
               @click="revertImageDelete(image)">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/>
          </svg>
        </small>

        <button type="button" class="absolute top-1 right-1 cursor-pointer"
                @click="removeImage(image)">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none"
               viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
               class="size-7 text-red-600">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
          </svg>
        </button>

      </div>
      <div
          class="relative w-[120px] h-[120px] rounded border border-dashed flex items-center justify-center hover:border-purple-500 dark:hover:border-purple-300 overflow-hidden">
      <span>
        Upload
      </span>
        <input type="file" :name
               class="absolute w-full h-full opacity-0"
               @change="uploadFiles" multiple>
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