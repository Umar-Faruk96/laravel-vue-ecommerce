<template>
  <div class="flex items-center justify-between mb-3">
    <h1 class="text-black/60 dark:text-white/70 sm:text-3xl font-semibold">Orders</h1>
  </div>

  <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow 2xl:h-[780px] animate-fade-in-down overflow-hidden" style="animation-delay: 0.3s">
    <template v-if="!loading">
      <div v-if="orders.labels.length > 0">
        <BarChart :chartData="orders" />
      </div>
      <p v-else class="text-xl font-medium">{{ orders.labels.length }} orders found</p>
    </template>

    <Spinner text="" class="bg-transparent" v-else />
  </div>
</template>

<script setup>
import axiosClient from "../../utils/axios.js";
import {ref} from "vue";
import BarChart from "../../components/core/Charts/BarChart.vue";
import Spinner from "../../components/core/Spinner.vue";

const orders = ref({});
const loading = ref(true);

axiosClient.get('/reports/orders').then(({data}) => {
  orders.value = {
    labels: data.labels,
    datasets: [
      {
        label: 'Order Reports',
        backgroundColor: '#f87979',
        data: data.dataset
      }
    ]
  };

  loading.value = false;
  // console.log(data);
})
</script>