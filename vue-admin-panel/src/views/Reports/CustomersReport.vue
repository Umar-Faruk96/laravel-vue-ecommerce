<template>
  <div class="flex items-center justify-between mb-3">
    <h1 class="text-black/60 dark:text-white/70 sm:text-3xl font-semibold">Customers</h1>
  </div>

  <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow animate-fade-in-down overflow-hidden" style="animation-delay: 0.3s">
    <template v-if="!loading">
      <div v-if="customers.labels.length > 0">
        <LineChart :chartData="customers" width="400" height="300" />
      </div>
      <p v-else class="text-xl font-medium">{{ customers.labels.length }} customers found</p>
    </template>

    <Spinner text="" class="bg-transparent" v-else />
  </div>
</template>

<script setup>
import axiosClient from "../../utils/axios.js";
import {ref} from "vue";
import LineChart from "../../components/core/Charts/BarChart.vue";
import Spinner from "../../components/core/Spinner.vue";

const customers = ref({});
const loading = ref(true);

axiosClient.get('/reports/customers').then(({data}) => {
  customers.value = {
    labels: data.labels,
    datasets: [
      {
        label: 'Customer Reports',
        backgroundColor: '#d8c4d0',
        data: data.dataset
      }
    ]
  };

  loading.value = false;
  // console.log(data);
})
</script>