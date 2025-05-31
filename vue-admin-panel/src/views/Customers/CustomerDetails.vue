<template>
  <Spinner v-if="loading" class="mt-4" />
  <template v-else>
    <div v-if="customer">
      <div class="flex flex-col items-center justify-between mb-3 text-black/60">
        <h1 class="text-3xl font-semibold">Customer Details</h1>
        <!--<pre>{{ customer }}</pre>-->
      </div>

      <header class="py-3 px-4 flex justify-between items-center">
        <h2 class="text-xl font-semibold leading-6 text-black/90">
          {{
            `Edit Customer: "${customer.first_name + " " + customer.last_name}"`
          }}
        </h2>

        <router-link :to="{ name: 'app.customers' }" type="button"
                     class="mt-3 w-full inline-flex justify-center rounded-md border border-black/30 shadow-sm px-4 py-2 bg-black/20 text-base font-medium text-black/80 hover:bg-black/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black/40 sm:mt-0 sm:w-auto sm:text-sm transition-colors"
        >
          Cancel
        </router-link>
      </header>

      <form @submit.prevent="submit">
        <main class="bg-white/80 px-4 pt-5 pb-4 space-y-8">
          <!-- Customer Info -->
          <section class="space-y-2">
            <!--<pre class="dark:text-gray-600">{{ customer }}</pre>-->
            <!--<pre class="dark:text-gray-600">{{ customer.status }}</pre>-->
            <!--<pre class="dark:text-gray-600">{{ countries }}</pre>-->
            <!--<pre class="dark:text-gray-600">{{ billingCountry.states }}</pre>-->
            <!--<pre class="dark:text-gray-600">{{ billingStates }}</pre>-->

            <h2 class="text-lg font-semibold dark:text-gray-600">Customer Info</h2>

            <div class="flex gap-3 flex-1 justify-between">
              <div class="flex text-gray-500 items-center gap-3">
                <label for="first_name" class="mt-3 font-medium">First Name:</label>
                <CustomInputNew v-model:first-name="customer.first_name" label="First Name" name="first_name"
                                idFor="first_name"
                                required :errors="errors['first_name']"
                />
              </div>

              <div class="flex text-gray-500 items-center gap-3">
                <label for="last_name" class="mt-3 font-medium">Last Name:</label>
                <CustomInputNew v-model:last-name="customer.last_name" label="Last Name" name="last_name"
                                idFor="last_name" required
                                :errors="errors['last_name']"
                />
              </div>

              <div class="flex text-gray-500 items-center gap-3">
                <label for="email" class="mt-3 font-medium">Email:</label>
                <CustomInputNew type="email" v-model:email="customer.email" label="Email" name="email" idFor="email"
                                required
                                :errors="errors['email']"
                />
              </div>

              <div class="flex text-gray-500 items-center gap-3">
                <label for="phone" class="mt-3 font-medium">Phone:</label>
                <input type="tel" v-model="customer.phone" name="phone" id="phone" required
                       class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md"
                       placeholder="Phone Number"
                />
                <small v-if="errors['phone'] && errors['phone'][0]" class="text-red-600">{{
                    errors['phone'][0]
                  }}</small>
              </div>

              <div class="flex items-center gap-3">
                <label :for="`${customer.status ? 'active' : 'inactive'}-${customer.id}`"
                       class="mt-3 text-gray-500 font-medium">Status:</label>
                <CustomInputNew type="checkbox" v-model:status="customer.status"
                                :name="customer.status ? 'active' : 'inactive'" required
                                :errors="errors['status']" :id="customer.id === '' ? 1 : customer.id"
                />
              </div>
            </div>
          </section>
          <!--/ Customer Info -->

          <!-- Billing Address -->
          <section class="space-y-4">
            <h3 class="text-lg font-semibold dark:text-gray-600">Billing Address</h3>

            <div class="flex gap-3 flex-1 text-gray-500">
              <div class="w-1/3 space-y-2">
                <label for="house_number" class="font-medium">House Number:</label>
                <input type="text" v-model="customer.billingAddress.house_number" name="house_number" id="house_number"
                       required
                       class="block w-full px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md"
                       placeholder="House Number"
                />
                <small v-if="errors['billingAddress.house_number'] && errors['billingAddress.house_number'][0]"
                       class="text-red-600">{{
                    errors['billingAddress.house_number'][0]
                  }}</small>
              </div>

              <div class="w-1/3 space-y-2">
                <label for="area" class="font-medium">Area:</label>
                <input type="text" v-model="customer.billingAddress.area" name="area" id="area" required
                       class="block w-full px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md"
                       placeholder="Area"
                />
                <small v-if="errors['billingAddress.area'] && errors['billingAddress.area'][0]"
                       class="text-red-600">{{
                    errors['billingAddress.area'][0]
                  }}</small>
              </div>

              <div class="w-1/3 space-y-2">
                <label for="city" class="font-medium">City:</label>
                <input type="text" v-model="customer.billingAddress.city" name="city" id="city" required
                       class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md"
                       placeholder="City"
                />

                <small v-if="errors['billingAddress.city'] && errors['billingAddress.city'][0]"
                       class="text-red-600">{{
                    errors['billingAddress.city'][0]
                  }}</small>
              </div>
            </div>

            <div class="flex gap-3 flex-1 text-gray-500">
              <div class="w-1/3 space-y-2">
                <label for="zip_code" class="font-medium">Zip Code:</label>
                <input type="text" v-model="customer.billingAddress.zip_code" name="zip_code" id="zip_code" required
                       class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md"
                       placeholder="Zip Code"
                />
                <small v-if="errors['billingAddress.zip_code'] && errors['billingAddress.zip_code'][0]"
                       class="text-red-600">{{
                    errors['billingAddress.zip_code'][0]
                  }}</small>
              </div>

              <div class="w-1/3 space-y-2">
                <label for="country_code" class="font-medium">Country:</label>
                <select v-model="customer.billingAddress.country_code" name="country_code" id="country_code" required
                        class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md">
                  <option v-for="country of countries" :value="country.code">{{ country.name }}</option>
                </select>

                <small
                    v-if="errors['billingAddress.country_code'] && errors['billingAddress.country_code'][0]"
                    class="text-red-600">{{
                    errors['billingAddress.country_code'][0]
                  }}</small>
              </div>

              <div class="w-1/3 space-y-2">
                <template v-if="billingCountry && !billingCountry.states">
                  <label for="state" class="font-medium">State:</label>
                  <input type="text" name="state" required placeholder="State" v-model="customer.billingAddress.state"
                         class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md"
                  />
                </template>

                <template v-else>
                  <label for="state" class="font-medium">State:</label>
                  <select v-model="customer.billingAddress.state" name="state" id="state" required
                          class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md">
                    <option v-for="state of billingStates" :value="state.key">{{ state.value }}</option>
                  </select>
                </template>

                <small v-if="errors['billingAddress.state'] && errors['billingAddress.state'][0]"
                       class="text-red-600">{{
                    errors['billingAddress.state'][0]
                  }}</small>
              </div>
            </div>
          </section>
          <!--/ Billing Address -->

          <!-- Shipping Address -->
          <section class="space-y-4">
            <h3 class="text-lg font-semibold dark:text-gray-600">Shipping Address</h3>

            <div class="flex gap-3 flex-1 text-gray-500">
              <div class="w-1/3 space-y-2">
                <label for="house_number" class="font-medium">House Number:</label>
                <input type="text" v-model="customer.shippingAddress.house_number" name="house_number" id="house_number"
                       required
                       class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md"
                       placeholder="House Number"
                />

                <small
                    v-if="errors['shippingAddress.house_number'] && errors['shippingAddress.house_number'][0]"
                    class="text-red-600">{{
                    errors['shippingAddress.house_number'][0]
                  }}</small>
              </div>

              <div class="w-1/3 space-y-2">
                <label for="area" class="font-medium">Area:</label>
                <input type="text" v-model="customer.shippingAddress.area" name="area" id="area" required
                       class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md"
                       placeholder="Area"
                />

                <small v-if="errors['shippingAddress.area'] && errors['shippingAddress.area'][0]"
                       class="text-red-600">{{
                    errors['shippingAddress.area'][0]
                  }}</small>
              </div>

              <div class="w-1/3 space-y-2">
                <label for="city" class="font-medium">City:</label>
                <input type="text" v-model="customer.shippingAddress.city" name="city" id="city" required
                       class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md"
                       placeholder="City"
                />

                <small v-if="errors['shippingAddress.city'] && errors['shippingAddress.city'][0]"
                       class="text-red-600">{{
                    errors['shippingAddress.city'][0]
                  }}</small>
              </div>
            </div>

            <div class="flex gap-3 flex-1 text-gray-500">
              <div class="w-1/3 space-y-2">
                <label for="zip_code" class="font-medium">Zip Code:</label>
                <input type="text" v-model="customer.shippingAddress.zip_code" name="zip_code" id="zip_code" required
                       class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md"
                       placeholder="Zip Code" />

                <small v-if="errors['shippingAddress.zip_code'] && errors['shippingAddress.zip_code'][0]"
                       class="text-red-600">{{
                    errors['shippingAddress.zip_code'][0]
                  }}</small>
              </div>

              <div class="w-1/3 space-y-2">
                <label for="country_code" class="font-medium">Country:</label>
                <select v-model="customer.shippingAddress.country_code" name="country_code" id="country_code" required
                        class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md">
                  <option v-for="country of countries" :value="country.code">{{ country.name }}</option>
                </select>

                <small
                    v-if="errors['shippingAddress.country_code'] && errors['shippingAddress.country_code'][0]"
                    class="text-red-600">{{
                    errors['shippingAddress.country_code'][0]
                  }}</small>
              </div>

              <div class="w-1/3 space-y-2">
                <template v-if="shippingCountry && !shippingCountry.states">
                  <label for="state" class="font-medium">State:</label>
                  <input type="text" v-model="customer.shippingAddress.state" name="state" id="state" required
                         class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md"
                         placeholder="State"
                  />
                </template>

                <template v-else>
                  <label for="state" class="font-medium">State:</label>
                  <select v-model="customer.shippingAddress.state" name="state" id="state" required
                          class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md">
                    <option v-for="state of shippingStates" :value="state.key">{{ state.value }}</option>
                  </select>
                </template>

                <small v-if="errors['shippingAddress.state'] && errors['shippingAddress.state'][0]"
                       class="text-red-600">{{
                    errors['shippingAddress.state'][0]
                  }}</small>
              </div>
            </div>
          </section>
          <!--/ Shipping Address -->
        </main>

        <footer class="bg-black/5 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button
              type="submit"
              class="py-2 w-full sm:w-auto px-4 border border-transparent text-sm font-medium rounded-md text-white/90 bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 transition-colors"
          >
            Submit
          </button>
          <router-link :to="{ name: 'app.customers' }" type="button" ref="cancelButton"
                       class="mt-3 w-full inline-flex justify-center rounded-md border border-black/30 shadow-sm px-4 py-2 bg-black/20 text-base font-medium text-black/80 hover:bg-black/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black/40 sm:mt-0 sm:w-auto sm:text-sm transition-colors"
          >
            Cancel
          </router-link>
        </footer>
      </form>
    </div>
  </template>
</template>

<script setup>
import {useRoute, useRouter} from "vue-router";
import {computed, onMounted, ref} from "vue";
import store from "../../store/index.js";
import CustomInputNew from "../../components/core/CustomInputNew.vue";
import Spinner from "../../components/core/Spinner.vue";

const route = useRoute();
const router = useRouter();
const customer = ref({});
const loading = ref(true);
const errors = ref({});

onMounted(() => {
  store.dispatch("getCustomer", route.params.id).then(({data}) => {
    customer.value = {
      ...data,
      billingAddress: {
        ...data.billing_address,
      },
      shippingAddress: {
        ...data.shipping_address,
      },
    };
    loading.value = false;
  });
});

function submit() {

  if (customer.value.id) {
    store.dispatch("updateCustomer", customer.value).then((response) => {
      loading.value = false;
      // console.log("response", response);

      if (response.status === 200) {
        // TODO: Show update success message
        router.push({name: "app.customers"});
      }
    }).catch((error) => {
      loading.value = false;
      errors.value = error.data.errors;
      console.error("Error updating customer:", error);
    });
  }
}

const countries = computed(() => store.state.countries);

const billingCountry = computed(() => store.state.countries.find(country => country.code === customer.value.billingAddress.country_code));
const billingStates = computed(() => {
  return billingCountry.value.states !== null ? Object.entries(billingCountry.value.states).map(state => ({
    key: state[0],
    value: state[1]
  })) : {}
});

const shippingCountry = computed(() => store.state.countries.find(country => country.code === customer.value.shippingAddress.country_code));
const shippingStates = computed(() => {
  return shippingCountry.value.states !== null ? Object.entries(shippingCountry.value.states).map(state => ({
    key: state[0],
    value: state[1]
  })) : {}
});
</script>