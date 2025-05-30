<template>
  <h1 class="text-gray-700 text-3xl font-bold">Dashboard</h1>

  <section
      class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 items-stretch justify-between gap-3 dark:text-gray-500 my-4"
  >
    <!--    Active Customers-->
    <div
        class="flex flex-col justify-center items-center bg-emerald-50 border border-cyan-100 px-6 py-4 rounded shadow space-y-2"
    >
      <h4 class="text-2xl font-semibold text-center">Active Customers</h4>

      <template v-if="!loading.activeCustomers">
        <p class="text-xl font-medium">{{ activeCustomers }}</p>
      </template>

      <Spinner text="" class="bg-transparent" v-else />
    </div>
    <!--/    Active Customers-->
    <!--    Active Products -->
    <div
        class="flex flex-col justify-center items-center bg-lime-50 border border-cyan-100 px-6 py-4 rounded shadow space-y-2"
    >
      <h4 class="text-2xl font-semibold text-center">Active Orders</h4>

      <template v-if="!loading.activeProducts">
        <p class="text-xl font-medium">{{ activeProducts }}</p>
      </template>

      <Spinner text="" class="bg-transparent" v-else />
    </div>
    <!--/    Active Products -->
    <!--    Paid Orders -->
    <div
        class="flex flex-col justify-center items-center bg-teal-50 border border-cyan-100 px-6 py-4 rounded shadow space-y-2"
    >
      <h4 class="text-2xl font-semibold text-center">Paid Orders</h4>

      <template v-if="!loading.paidOrders">
        <p class="text-xl font-medium">{{ paidOrders }}</p>
      </template>

      <Spinner text="" class="bg-transparent" v-else />
    </div>
    <!--/    Paid Orders -->
    <!--    Total Income -->
    <div
        class="flex flex-col justify-center items-center bg-green-50 border border-cyan-100 px-6 py-4 rounded shadow space-y-2"
    >
      <h4 class="text-2xl font-semibold text-center">Total Sales</h4>

      <template v-if="!loading.totalSale">
        <p class="text-xl font-medium">{{ totalSale }}</p>
      </template>

      <Spinner text="" class="bg-transparent" v-else />
    </div>
    <!--/    Total Income -->
  </section>

  <section
      class="grid grid-rows-1 md:grid-rows-2 md: grid-flow-col grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 dark:text-gray-500"
  >
    <div
        class="md:row-span-2 lg:col-span-2 flex flex-col justify-center items-center bg-lime-50 border border-cyan-100 px-6 py-4 rounded shadow space-y-2"
    >
      <h4 class="text-2xl font-semibold text-center">Latest Orders</h4>

      <template v-if="!loading.latestOrders">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 shadow">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
          <tr>
            <th scope="col" class="px-6 py-3">Order ID</th>
            <th scope="col" class="px-6 py-3">Customer Name</th>
            <th scope="col" class="px-6 py-3">Order Created At</th>
            <th scope="col" class="px-6 py-3">Order Items</th>
            <th scope="col" class="px-6 py-3">Order Total Price</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="order in latestOrders" :key="order.id"
              class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <td class="px-6 py-4 flex items-center gap-3">
              <span>{{ order.id }}</span>
              <router-link :to="{ name: 'app.orders.show', params: { id: order.id } }" class="flex items-center gap-2">
                <EyeIcon class="size-6" />
              </router-link>
            </td>
            <td class="px-6 py-4">{{ order.full_name }}</td>
            <td class="px-6 py-4">{{ order.created_at }}</td>
            <td class="px-6 py-4">{{ order.items_count }}</td>
            <!-- make order total price in BD taka-->
            <td class="px-6 py-4">{{
                new Intl.NumberFormat("en-BD", {
                  style: "currency",
                  currency: "BDT",
                  maximumFractionDigits: 0
                }).format(order.total_price)
              }}
            </td>
          </tr>
          </tbody>
        </table>
      </template>

      <Spinner text="" class="bg-transparent" v-else />
    </div>

    <div
        class="flex flex-col justify-center items-center lg:h-[18rem] bg-lime-50 border border-cyan-100 px-6 py-4 rounded shadow overflow-y-scroll space-y-2"
    >
      <h4 class="text-2xl font-semibold text-center mt-4">Orders by Country</h4>

      <template v-if="!loading.ordersByCountry">
        <DoughnutChart :chartData="ordersByCountry" />
      </template>

      <Spinner text="" class="bg-transparent" v-else />
    </div>

    <div
        class="flex flex-col justify-center items-center bg-lime-50 border border-cyan-100 px-6 py-4 rounded shadow space-y-2"
    >
      <h4 class="text-2xl font-semibold text-center">Latest Customers: {{ latestCustomers.length }}</h4>

      <template v-if="!loading.latestCustomers">
        <router-link to="/" v-for="customer in latestCustomers" :key="customer.user_id"
                     class="flex gap-4  items-center w-2/4 group space-y-2">
          <UserIcon class="w-12 h-12 text-gray-600 bg-slate-300 p-2 rounded-full group-hover:bg-indigo-200" />
          <div class="space-y-1 group-hover:text-indigo-600">
            <h6 class="text-lg font-medium">{{ customer.full_name }}</h6>
            <p class="font-medium">{{ customer.email }}</p>
          </div>
        </router-link>
      </template>

      <Spinner text="" class="bg-transparent" v-else />
    </div>
  </section>
</template>

<script setup>
import DoughnutChart from "../components/core/Charts/DoughnutChart.vue";
import axios from "../utils/axios.js";
import {ref} from "vue";
import Spinner from "../components/core/Spinner.vue";
import {UserIcon, EyeIcon} from "@heroicons/vue/24/outline";

const activeCustomers = ref(0);
const activeProducts = ref(0);
const paidOrders = ref(0);
const totalSale = ref(0);
const ordersByCountry = ref({});
const latestCustomers = ref([]);
const latestOrders = ref({});

const loading = ref({
  activeCustomers: true,
  activeProducts: true,
  paidOrders: true,
  totalSale: true,
  ordersByCountry: true,
  latestCustomers: true,
  latestOrders: true
});

axios.get("/dashboard/active-customers").then(({data}) => {
  activeCustomers.value = data.active_customers;
  loading.value.activeCustomers = false;
});

axios.get("/dashboard/active-products").then(({data}) => {
  activeProducts.value = data.active_products;
  loading.value.activeProducts = false;
});

axios.get("/dashboard/paid-orders").then(({data}) => {
  paidOrders.value = data.paid_orders;
  loading.value.paidOrders = false;
});

axios.get("/dashboard/total-sale").then(({data}) => {
  // Format the total sale value to BDT currency format
  totalSale.value = new Intl.NumberFormat("en-BD", {
    style: "currency",
    currency: "BDT",
    maximumFractionDigits: 0,
  }).format(data.total_sale);
  // totalSale.value = data.total_sale;
  loading.value.totalSale = false;
});

axios.get("/dashboard/orders-by-country").then(({data}) => {
  const countries = data.orders_by_country;

  const chartData = {
    labels: [],
    datasets: [
      {
        backgroundColor: ["#41B883", "#E46651", "#00D8FF", "#DD1B16"],
        data: [],
      },
    ],
  };

  countries.forEach((country) => {
    chartData.labels.push(country.countryName);
    chartData.datasets[0].data.push(country.count);
  });

  ordersByCountry.value = chartData;
  loading.value.ordersByCountry = false;
});

axios.get("/dashboard/latest-customers").then(({data}) => {
  latestCustomers.value = data.latest_customers;
  loading.value.latestCustomers = false;
});

axios.get("/dashboard/latest-orders").then(({data}) => {
  latestOrders.value = data.latest_orders;
  loading.value.latestOrders = false;
});
</script>