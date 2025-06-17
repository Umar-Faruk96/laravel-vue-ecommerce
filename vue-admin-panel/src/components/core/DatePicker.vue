<template>
  <div
      class="flex flex-col sm:flex-row items-end sm:justify-end sm:items-center w-3/4 gap-2 sm:gap-4 text-gray-500 dark:text-white/50">
    <label for="date_picker" class="font-medium text-xs sm:text-base lg:text-lg">See by Date Period:</label>
    <select name="date_picker" id="date_picker" v-model="modelValue" @change="handleChange"
            class="bg-gray-50 border border-gray-300 dark:border-gray-600 outline-none text-gray-900 dark:text-white/70 dark:bg-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-3/4 sm:w-auto lg:w-2/4 p-2 hover:cursor-pointer">
      <option v-for="date of dateOptions" :key="date.key" :value="date.key">{{ date.value }}</option>
    </select>
  </div>
</template>

<script setup>
import {computed} from "vue";
import state from "../../store/state.js";

const props = defineProps({
  handleChange: {
    type: Function,
    required: true,
    default: () => {
    },
  }
});

const modelValue = defineModel("modelValue");

const dateOptions = computed(() => state.dateOptions);
</script>