<template>
  <div class="flex items-center justify-between mb-3">
    <h1 class="text-black/60 dark:text-white/70 text-xl sm:text-3xl font-semibold">
      Dashboard
    </h1>

    <DatePicker :handle-change="handleDateChange" v-model="selectedDate" />
  </div>

  <div class="animate-fade-in-down" style="animation-delay: 0.2s">
    <section
      class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 items-stretch justify-between gap-3 text-gray-500 dark:text-white/65 my-4"
    >
      <!--    Active Customers-->
      <div
        class="flex flex-col justify-center items-center bg-emerald-950 border border-cyan-950 px-6 py-4 rounded shadow space-y-4"
      >
        <h4 class="text-lg sm:text-xl 2xl:text-2xl font-semibold text-center">Active Customers</h4>

        <template v-if="!loading.activeCustomers">
          <p class="text-lg sm:text-xl font-medium">{{ activeCustomers }}</p>
        </template>

        <Spinner text="" class="bg-transparent" v-else />
      </div>
      <!--/    Active Customers-->
      <!--    Active Products -->
      <div
        class="flex flex-col justify-center items-center bg-lime-950 border border-cyan-950 px-6 py-4 rounded shadow space-y-4"
      >
        <h4 class="text-lg sm:text-xl 2xl:text-2xl font-semibold text-center">Active Orders</h4>

        <template v-if="!loading.activeProducts">
          <p class="text-lg sm:text-xl font-medium">{{ activeProducts }}</p>
        </template>

        <Spinner text="" class="bg-transparent" v-else />
      </div>
      <!--/    Active Products -->
      <!--    Paid Orders -->
      <div
        class="flex flex-col justify-center items-center bg-teal-950 border border-cyan-950 px-6 py-4 rounded shadow space-y-4"
      >
        <h4 class="text-lg sm:text-xl 2xl:text-2xl font-semibold text-center">Paid Orders</h4>

        <template v-if="!loading.paidOrders">
          <p class="text-lg sm:text-xl font-medium">{{ paidOrders }}</p>
        </template>

        <Spinner text="" class="bg-transparent" v-else />
      </div>
      <!--/    Paid Orders -->
      <!--    Total Sale -->
      <div
        class="flex flex-col justify-center items-center bg-green-950 border border-cyan-950 px-6 py-4 rounded shadow space-y-4"
      >
        <h4 class="text-lg sm:text-xl 2xl:text-2xl font-semibold text-center">Total Sales</h4>

        <template v-if="!loading.totalSale">
          <p class="text-lg sm:text-xl font-medium">{{ totalSale }}</p>
        </template>

        <Spinner text="" class="bg-transparent" v-else />
      </div>
      <!--/    Total Sale -->
    </section>

    <section
      class="grid grid-rows-1 xl:grid-rows-2 xl:grid-flow-col grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3 text-gray-500 dark:text-white/70"
    >
      <!--    Latest Orders -->
      <div
        class="xl:row-span-2 sm:col-span-2 flex flex-col justify-center items-center bg-lime-950 border border-cyan-950 sm:px-6 py-4 rounded shadow space-y-4"
      >
        <h4 class="text-lg sm:text-xl 2xl:text-2xl font-semibold text-center">Latest Orders</h4>

        <template v-if="!loading.latestOrders">
          <div class="w-full overflow-scroll">
            <table
              class="w-full text-sm text-left text-gray-500 dark:text-gray-400 shadow"
            >
              <thead
                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
              >
                <tr>
                  <th scope="col" class="px-6 py-3">Order ID</th>
                  <th scope="col" class="px-6 py-3">Customer Name</th>
                  <th scope="col" class="px-6 py-3">Order Created At</th>
                  <th scope="col" class="px-6 py-3">Order Items</th>
                  <th scope="col" class="px-6 py-3">Order Total Price</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="order in latestOrders"
                  :key="order.id"
                  class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                >
                  <td class="px-6 py-4 flex items-center gap-3">
                    <span>{{ order.id }}</span>
                    <router-link
                      :to="{ name: 'app.orders.show', params: { id: order.id } }"
                      class="flex items-center gap-2"
                    >
                      <EyeIcon class="size-6" />
                    </router-link>
                  </td>
                  <td class="px-6 py-4">{{ order.full_name }}</td>
                  <td class="px-6 py-4">{{ order.created_at }}</td>
                  <td class="px-6 py-4">{{ order.items_count }}</td>
                  <!-- make order total price in BD taka-->
                  <td class="px-6 py-4">
                    {{
                      new Intl.NumberFormat("en-BD", {
                        style: "currency",
                        currency: "BDT",
                        maximumFractionDigits: 0,
                      }).format(order.total_price)
                    }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </template>

        <Spinner text="" class="bg-transparent" v-else />
      </div>
      <!--/    Latest Orders -->

      <!--    Orders by Country -->
      <div
        class="flex flex-col justify-center items-center 2xl:h-[20rem] bg-teal-950 border border-cyan-950 px-6 py-4 rounded shadow overflow-y-scroll space-y-4"
      >
        <h4 class="text-lg sm:text-xl 2xl:text-2xl font-semibold text-center mt-4">
          Orders by Country
        </h4>

        <template v-if="!loading.ordersByCountry">
          <div v-if="ordersByCountry.labels.length > 0">
            <DoughnutChart :chartData="ordersByCountry" />
          </div>
          <p v-else class="text-xl font-medium">
            {{ ordersByCountry.labels.length }} orders found
          </p>
        </template>

        <Spinner text="" class="bg-transparent" v-else />
      </div>
      <!--/    Orders by Country -->

      <!--    Latest Customers -->
      <div
        class="flex flex-col justify-center items-center bg-emerald-950 border border-cyan-950 px-6 py-4 rounded shadow space-y-4"
      >
        <h4 class="text-lg sm:text-xl 2xl:text-2xl font-semibold text-center">
          Latest Customers: {{ latestCustomers.length }}
        </h4>

        <template v-if="!loading.latestCustomers">
          <router-link
            :to="{ name: 'app.customers.show', params: { id: customer.user_id } }"
            v-for="customer in latestCustomers"
            :key="customer.user_id"
            class="flex gap-4 w-full items-center justify-center group space-y-2"
          >
            <div class="w-12">
              <UserIcon
                class="size-12 text-gray-600 bg-slate-300 p-2 rounded-full group-hover:bg-indigo-200"
              />
            </div>
            <div class="space-y-1 group-hover:text-emerald-400">
              <h6 class="text-base sm:text-lg font-medium">{{ customer.full_name }}</h6>
              <p class="font-medium">{{ customer.email }}</p>
            </div>
          </router-link>
        </template>

        <Spinner text="" class="bg-transparent" v-else />
      </div>
      <!--/    Latest Customers -->
    </section>
  </div>
</template>

<script setup>
import DoughnutChart from "../components/core/Charts/DoughnutChart.vue";
import axios from "../utils/axios.js";
import { onMounted, ref } from "vue";
import Spinner from "../components/core/Spinner.vue";
import { UserIcon, EyeIcon } from "@heroicons/vue/24/outline";
import DatePicker from "../components/core/DatePicker.vue";

const activeCustomers = ref(0);
const activeProducts = ref(0);
const paidOrders = ref(0);
const totalSale = ref(0);
const ordersByCountry = ref({});
const latestCustomers = ref([]);
const latestOrders = ref({});
const selectedDate = ref("all");

const loading = ref({
  activeCustomers: true,
  activeProducts: true,
  paidOrders: true,
  totalSale: true,
  ordersByCountry: true,
  latestCustomers: true,
  latestOrders: true,
});

function updateDashboard() {
  const dateQuery = selectedDate.value;

  axios.get("/dashboard/active-customers").then(({ data }) => {
    activeCustomers.value = data.active_customers;
    loading.value.activeCustomers = false;
  });

  axios.get("/dashboard/active-products").then(({ data }) => {
    activeProducts.value = data.active_products;
    loading.value.activeProducts = false;
  });

  axios.get("/dashboard/paid-orders", { params: { dateQuery } }).then(({ data }) => {
    paidOrders.value = data.paid_orders;
    loading.value.paidOrders = false;
  });

  axios.get("/dashboard/total-sale", { params: { dateQuery } }).then(({ data }) => {
    // Format the total sale value to BDT currency format
    totalSale.value = new Intl.NumberFormat("en-BD", {
      style: "currency",
      currency: "BDT",
      maximumFractionDigits: 0,
    }).format(data.total_sale);
    // totalSale.value = data.total_sale;
    loading.value.totalSale = false;
  });

  axios
    .get("/dashboard/orders-by-country", { params: { dateQuery } })
    .then(({ data }) => {
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

  axios.get("/dashboard/latest-customers").then(({ data }) => {
    latestCustomers.value = data.latest_customers;
    loading.value.latestCustomers = false;
  });

  axios.get("/dashboard/latest-orders").then(({ data }) => {
    latestOrders.value = data.latest_orders;
    loading.value.latestOrders = false;
  });
}

onMounted(() => {
  updateDashboard();
});

function handleDateChange() {
  loading.value = {
    activeCustomers: true,
    activeProducts: true,
    paidOrders: true,
    totalSale: true,
    ordersByCountry: true,
    latestCustomers: true,
    latestOrders: true,
  };

  updateDashboard();
}
</script>