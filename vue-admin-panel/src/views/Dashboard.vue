<template>
  <h1 class="text-gray-700 text-3xl font-bold">Dashboard</h1>

  <section
      class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 items-stretch justify-between gap-3 dark:text-gray-500 my-4">
    <!--    Active Customers-->
    <div
        class="flex flex-col justify-center items-center bg-emerald-50 border border-cyan-100 px-6 py-4 rounded shadow space-y-2">
      <h4 class="text-2xl font-semibold text-center">Active Customers</h4>

      <template v-if="!loading.activeCustomers">
        <p class="text-xl font-medium">{{ activeCustomers }}</p>
      </template>

      <Spinner text="" class="bg-transparent" v-else />
    </div>
    <!--/    Active Customers-->
    <!--    Active Products -->
    <div
        class="flex flex-col justify-center items-center bg-lime-50 border border-cyan-100 px-6 py-4 rounded shadow space-y-2">
      <h4 class="text-2xl font-semibold text-center">Active Orders</h4>

      <template v-if="!loading.activeProducts">
        <p class="text-xl font-medium">{{ activeProducts }}</p>
      </template>

      <Spinner text="" class="bg-transparent" v-else />
    </div>
    <!--/    Active Products -->
    <!--    Paid Orders -->
    <div
        class="flex flex-col justify-center items-center bg-teal-50 border border-cyan-100 px-6 py-4 rounded shadow space-y-2">
      <h4 class="text-2xl font-semibold text-center">Paid Orders</h4>

      <template v-if="!loading.paidOrders">
        <p class="text-xl font-medium">{{ paidOrders }}</p>
      </template>

      <Spinner text="" class="bg-transparent" v-else />
    </div>
    <!--/    Paid Orders -->
    <!--    Total Income -->
    <div
        class="flex flex-col justify-center items-center bg-green-50 border border-cyan-100 px-6 py-4 rounded shadow space-y-2">
      <h4 class="text-2xl font-semibold text-center">Total Sales</h4>

      <template v-if="!loading.totalSale">
        <p class="text-xl font-medium">{{ totalSale }}</p>
      </template>

      <Spinner text="" class="bg-transparent" v-else />
    </div>
    <!--/    Total Income -->
  </section>

  <section class="grid grid-rows-1 md:grid-rows-2 grid-flow-col grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
    <div
        class="md:row-span-2 lg:col-span-2 flex flex-col justify-center items-center bg-lime-50 border border-cyan-100 px-6 py-4 rounded shadow space-y-2">
      <h4 class="text-2xl font-semibold text-center">Latest Orders</h4>
      <p class="text-xl font-medium">100,000,000</p>
    </div>

    <div
        class="flex flex-col justify-center items-center bg-lime-50 border border-cyan-100 px-6 py-4 rounded shadow space-y-2">
      <h4 class="text-2xl font-semibold text-center">Orders by Country</h4>
      <!--<DoughnutChart :data="0" />-->
    </div>

    <div
        class="flex flex-col justify-center items-center bg-lime-50 border border-cyan-100 px-6 py-4 rounded shadow space-y-2">
      <h4 class="text-2xl font-semibold text-center">Latest Customers</h4>
      <p class="text-xl font-medium">100,000,000</p>
    </div>
  </section>
</template>

<script setup>
import DoughnutChart from '../components/core/Charts/Doughnut.vue';
import axios from "../utils/axios.js";
import {ref} from "vue";
import Spinner from "../components/core/Spinner.vue";

const activeCustomers = ref(0);
const activeProducts = ref(0);
const paidOrders = ref(0);
const totalSale = ref(0);

const loading = ref({
  activeCustomers: true,
  activeProducts: true,
  paidOrders: true,
  totalSale: true
});

axios.get('/dashboard/active-customers').then(({data}) => {
  activeCustomers.value = data.active_customers;
  loading.value.activeCustomers = false;
});

axios.get('/dashboard/active-products').then(({data}) => {
  activeProducts.value = data.active_products;
  loading.value.activeProducts = false;
});

axios.get('/dashboard/paid-orders').then(({data}) => {
  paidOrders.value = data.paid_orders;
  loading.value.paidOrders = false;
});

axios.get('/dashboard/total-sale').then(({data}) => {
  totalSale.value = new Intl.NumberFormat('en-BD', {
    style: 'currency',
    currency: 'BDT',
    maximumFractionDigits: 0
  }).format(data.total_sale);
  // totalSale.value = data.total_sale;
  loading.value.totalSale = false;
});

</script>