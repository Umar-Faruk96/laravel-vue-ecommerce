<template>
  <section class="space-y-4">
    <h1 class="text-black/60 dark:text-white/70 sm:text-3xl font-semibold">Reports</h1>

    <div
      class="flex flex-col-reverse sm:flex-row items-start sm:items-center gap-4 sm:gap-2 text-gray-700 dark:text-gray-300 font-semibold sm:text-lg"
    >
      <div class="flex items-center justify-between gap-2 sm:gap-4 w-full sm:w-1/3">
        <router-link
          :to="{ name: 'app.reports.orders', query: { dateQuery: selectedDate} }"
          class="w-full bg-gray-300 dark:bg-gray-500 px-2 py-1 sm:px-4 sm:py-2 rounded-md transition-all text-center"
          active-class="bg-indigo-500 dark:bg-indigo-700"
        >
          Orders
        </router-link>
        <router-link
          :to="{ name: 'app.reports.customers', query: { dateQuery: selectedDate} }"
          class="w-full bg-gray-300 dark:bg-gray-500 px-2 py-1 sm:px-4 sm:py-2 rounded-md transition-all text-center"
          active-class="bg-indigo-500 dark:bg-indigo-700"
        >
          Customers
        </router-link>
      </div>
      <div class="w-full flex justify-end sm:w-2/3">
        <DatePicker :handle-change="handleDateChange" v-model="selectedDate" />
      </div>
    </div>

    <div
      class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow animate-fade-in-down"
      style="animation-delay: 0.3s"
    >
      <router-view></router-view>
    </div>
  </section>
</template>

<script setup>
import DatePicker from "../../components/core/DatePicker.vue";
import {ref} from "vue";
import router from "../../router/index.js";
import {useRoute} from "vue-router";

const selectedDate = ref("all");
const route = useRoute();

const handleDateChange = () => {
  router.push({ name: route.name, query: { dateQuery: selectedDate.value } });
};
</script>