<template>
  <div class="flex items-center justify-between mb-3">
    <h1 class="text-black/60 text-3xl font-semibold">Order Summary</h1>

    <!-- <pre>{{ order }}</pre> -->
  </div>

  <div v-if="order" class="text-black/60 bg-black/10 p-4 rounded-lg shadow">
    <div class="flex justify-between">
      <!--  Order Info-->
      <div>
        <h2
          class="flex justify-between items-center text-xl font-semibold pb-2 border-b border-gray-600"
        >
          Order Info
        </h2>
        <table>
          <tbody>
            <tr>
              <td class="font-bold py-1 px-2">Order #</td>
              <td>{{ order.id }}</td>
            </tr>
            <tr>
              <td class="font-bold py-1 px-2">Order Date</td>
              <td>{{ order.created_at }}</td>
            </tr>
            <tr>
              <td class="font-bold py-1 px-2">Order Status</td>
              <td>{{ order.status }}</td>
            </tr>
            <tr>
              <td class="font-bold py-1 px-2">SubTotal</td>
              <td>${{ order.total_price }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <!--/  Order Info-->

      <!--  Customer Details-->
      <div>
        <h2 class="text-xl font-semibold pb-2 border-b border-gray-600">
          Customer Details
        </h2>
        <table>
          <tbody>
            <tr>
              <td class="font-bold py-1 px-2">Full Name</td>
              <td>{{ order.customer.first_name }} {{ order.customer.last_name }}</td>
            </tr>
            <tr>
              <td class="font-bold py-1 px-2">Email</td>
              <td>{{ order.customer.email }}</td>
            </tr>
            <tr>
              <td class="font-bold py-1 px-2">Phone</td>
              <td>{{ order.customer.phone }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <!--/  Customer Details-->

      <!--  Addresses Details-->
      <div class="flex flex-col gap-3">
        <div>
          <h2 class="text-xl font-semibold pb-2 border-b border-gray-600">
            Billing Address
          </h2>
          <!--  Billing Address Details-->
          <div>
            {{ order.customer.billing_address.house_number }},
            {{ order.customer.billing_address.area }} <br />
            {{ order.customer.billing_address.city }},
            {{ order.customer.billing_address.zipcode }} <br />
            {{ order.customer.billing_address.state }},
            {{ order.customer.billing_address.country.name }} <br />
          </div>
          <!--/  Billing Address Details-->
        </div>
        <div>
          <h2 class="text-xl font-semibold pb-2 border-b border-gray-600">
            Shipping Address
          </h2>
          <!--  Shipping Address Details-->
          <div>
            {{ order.customer.shipping_address.house_number }},
            {{ order.customer.shipping_address.area }} <br />
            {{ order.customer.shipping_address.city }},
            {{ order.customer.shipping_address.zipcode }} <br />
            {{ order.customer.shipping_address.state }},
            {{ order.customer.shipping_address.country.name }} <br />
          </div>
          <!--/  Shipping Address Details-->
        </div>
      </div>
      <!--/  Customer Details-->
    </div>

    <!--    Order Items-->
    <div>
      <h2 class="text-xl font-semibold mt-6 pb-2 border-b border-gray-600">
        Order Items
      </h2>
      <div v-for="item of order.items">
        <!-- Order Item -->
        <div class="flex flex-col sm:flex-row items-center gap-4 mt-6">
          <a href="#" class="w-36 h-32 flex items-center justify-center overflow-hidden">
            <img :src="item.product.image" class="object-cover" alt="" />
          </a>
          <div class="flex flex-col justify-between flex-1">
            <div class="flex justify-between mb-3">
              <h3>
                {{ item.product.title }}
              </h3>
            </div>
            <div class="flex justify-between items-center">
              <div class="flex items-center">Qty: {{ item.quantity }}</div>
              <span class="text-lg font-semibold"> ${{ item.unit_price }} </span>
            </div>
          </div>
        </div>
        <!--/ Order Item -->
        <hr class="my-3" />
      </div>
    </div>
    <!--/    Order Items-->
  </div>
</template>

<script setup>
import { useRoute } from "vue-router";
import { onMounted, ref } from "vue";
import store from "../../store/index.js";

const route = useRoute();
const order = ref(null);

onMounted(() => {
  store.dispatch("getOrder", route.params.id).then(({ data }) => {
    order.value = data;
  });
});

// const orders = computed(() => store.state.orders);
</script>
