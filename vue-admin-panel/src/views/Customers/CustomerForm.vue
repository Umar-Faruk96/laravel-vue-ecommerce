<template>
  <TransitionRoot appear :show="formState" as="template">
    <Dialog as="div" @close="closeCustomerForm" class="relative z-10">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black/60 transition-colors" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel
              class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white/90 dark:bg-gray-400 p-4 text-left align-middle shadow-xl transition-all"
            >
              <Spinner
                v-if="loading"
                class="absolute inset-0 flex items-center justify-center"
              />

              <header class="py-3 px-4 flex justify-between items-center">
                <DialogTitle
                  as="h3"
                  class="text-lg font-medium leading-6 text-black/90 dark:text-gray-900"
                >
                  Create new Customer
                </DialogTitle>

                <button
                  @click="closeCustomerForm"
                  type="button"
                  class="text-black/60 hover:text-black/90 focus:outline-none focus:text-black/90 transition-colors cursor-pointer hover:bg-black/20 rounded-full p-1"
                >
                  <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                </button>
              </header>

              <form @submit.prevent="submit">
                <main
                  class="bg-white/80 dark:bg-gray-500 rounded-t-lg px-4 pt-5 pb-4 space-y-4"
                >
                  <!-- Customer Info -->
                  <section class="space-y-2">
                    <!--<pre class="dark:text-gray-600">{{ customer }}</pre>-->
                    <!--<pre class="dark:text-gray-600">{{ customer.status }}</pre>-->
                    <!--<pre class="dark:text-gray-600">{{ countries }}</pre>-->
                    <!--<pre class="dark:text-gray-600">{{ billingCountry.states }}</pre>-->
                    <!--<pre class="dark:text-gray-600">{{ billingStates }}</pre>-->

                    <h2
                      class="text-lg mb-3 font-semibold dark:text-gray-200 text-gray-600"
                    >
                      Customer Info
                    </h2>

                    <div class="flex gap-3 flex-1">
                      <CustomInputV3
                        v-model:first-name="customer.first_name"
                        label="First Name"
                        name="first_name"
                        required
                        :errors="errors['first_name']"
                      />

                      <CustomInputV3
                        v-model:last-name="customer.last_name"
                        label="Last Name"
                        name="last_name"
                        required
                        :errors="errors['last_name']"
                      />
                    </div>

                    <CustomInputV3
                      type="email"
                      v-model:email="customer.email"
                      label="Email"
                      name="email"
                      required
                      :errors="errors['email']"
                    />

                    <div class="flex gap-3 items-center">
                      <div class="w-3/4">
                        <input
                          type="tel"
                          v-model="customer.phone"
                          name="phone"
                          required
                          class="block w-full px-3 py-2 border border-black/30 dark:border-gray-600 placeholder-black/80 dark:placeholder-gray-300 bg-white/60 dark:bg-gray-600 text-black/90 dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:bg-gray-700 focus:z-10 sm:text-sm transition-colors rounded-md"
                          placeholder="Phone Number"
                        />
                        <small
                          v-if="errors['phone'] && errors['phone'][0]"
                          class="text-red-600"
                          >{{ errors["phone"][0] }}</small
                        >
                      </div>

                      <CustomInputV3
                        type="checkbox"
                        v-model:status="customer.status"
                        :name="customer.status ? 'active' : 'inactive'"
                        required
                        :errors="errors['status']"
                        :id="customer.id === '' ? 1 : customer.id"
                      />
                    </div>
                  </section>
                  <!--/ Customer Info -->

                  <!-- Billing Address -->
                  <section class="space-y-2">
                    <h3
                      class="text-lg mb-3 font-semibold dark:text-gray-200 text-gray-600"
                    >
                      Billing Address
                    </h3>

                    <div class="flex gap-3 flex-1">
                      <div class="w-1/2">
                        <input
                          type="text"
                          v-model="customer.billingAddress.house_number"
                          name="house_number"
                          required
                          class="block w-full px-3 py-2 border border-black/30 dark:border-gray-600 placeholder-black/80 dark:placeholder-gray-300 bg-white/60 dark:bg-gray-600 text-black/90 dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 focus:bg-gray-700 sm:text-sm transition-colors rounded-md"
                          placeholder="House Number"
                        />
                        <small
                          v-if="
                            errors['billingAddress.house_number'] &&
                            errors['billingAddress.house_number'][0]
                          "
                          class="text-red-600"
                          >{{ errors["billingAddress.house_number"][0] }}</small
                        >
                      </div>

                      <div class="w-1/2">
                        <input
                          type="text"
                          v-model="customer.billingAddress.area"
                          name="area"
                          required
                          class="block w-full px-3 py-2 border border-black/30 dark:border-gray-600 placeholder-black/80 dark:placeholder-gray-300 bg-white/60 dark:bg-gray-600 text-black/90 dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 focus:bg-gray-700 sm:text-sm transition-colors rounded-md"
                          placeholder="Area"
                        />
                        <small
                          v-if="
                            errors['billingAddress.area'] &&
                            errors['billingAddress.area'][0]
                          "
                          class="text-red-600"
                          >{{ errors["billingAddress.area"][0] }}</small
                        >
                      </div>
                    </div>

                    <div class="flex gap-3 flex-1">
                      <div class="w-1/2">
                        <input
                          type="text"
                          v-model="customer.billingAddress.city"
                          name="city"
                          required
                          class="block w-full px-3 py-2 border border-black/30 dark:border-gray-600 placeholder-black/80 dark:placeholder-gray-300 bg-white/60 dark:bg-gray-600 text-black/90 dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 focus:bg-gray-700 sm:text-sm transition-colors rounded-md"
                          placeholder="City"
                        />

                        <small
                          v-if="
                            errors['billingAddress.city'] &&
                            errors['billingAddress.city'][0]
                          "
                          class="text-red-600"
                          >{{ errors["billingAddress.city"][0] }}</small
                        >
                      </div>

                      <div class="w-1/2">
                        <input
                          type="text"
                          v-model="customer.billingAddress.zip_code"
                          name="zip_code"
                          required
                          class="block w-full px-3 py-2 border border-black/30 dark:border-gray-600 placeholder-black/80 dark:placeholder-gray-300 bg-white/60 dark:bg-gray-600 text-black/90 dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 focus:bg-gray-700 sm:text-sm transition-colors rounded-md"
                          placeholder="Zip Code"
                        />
                        <small
                          v-if="
                            errors['billingAddress.zip_code'] &&
                            errors['billingAddress.zip_code'][0]
                          "
                          class="text-red-600"
                          >{{ errors["billingAddress.zip_code"][0] }}</small
                        >
                      </div>
                    </div>

                    <div class="flex gap-3 flex-1">
                      <div class="w-1/2">
                        <select
                          v-model="customer.billingAddress.country_code"
                          required
                          class="block w-full px-3 py-2 border border-black/30 dark:border-gray-600 placeholder-black/80 dark:placeholder-gray-300 bg-white/60 dark:bg-gray-600 text-black/90 dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 focus:bg-gray-700 sm:text-sm transition-colors rounded-md"
                        >
                          <option v-for="country of countries" :value="country.code">
                            {{ country.name }}
                          </option>
                        </select>

                        <small
                          v-if="
                            errors['billingAddress.country_code'] &&
                            errors['billingAddress.country_code'][0]
                          "
                          class="text-red-600"
                          >{{ errors["billingAddress.country_code"][0] }}</small
                        >
                      </div>

                      <div class="w-1/2">
                        <template v-if="billingCountry && !billingCountry.states">
                          <input
                            type="text"
                            v-model="customer.billingAddress.state"
                            name="state"
                            required
                            class="block w-full px-3 py-2 border border-black/30 dark:border-gray-600 placeholder-black/80 dark:placeholder-gray-300 bg-white/60 dark:bg-gray-600 text-black/90 dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 focus:bg-gray-700 sm:text-sm transition-colors rounded-md"
                            placeholder="State"
                          />
                        </template>

                        <template v-else>
                          <select
                            v-model="customer.billingAddress.state"
                            required
                            class="block w-full px-3 py-2 border border-black/30 dark:border-gray-600 placeholder-black/80 dark:placeholder-gray-300 bg-white/60 dark:bg-gray-600 text-black/90 dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 focus:bg-gray-700 sm:text-sm transition-colors rounded-md"
                          >
                            <option v-for="state of billingStates" :value="state.key">
                              {{ state.value }}
                            </option>
                          </select>
                        </template>

                        <small
                          v-if="
                            errors['billingAddress.state'] &&
                            errors['billingAddress.state'][0]
                          "
                          class="text-red-600"
                          >{{ errors["billingAddress.state"][0] }}</small
                        >
                      </div>
                    </div>
                  </section>
                  <!--/ Billing Address -->

                  <!-- Shipping Address -->
                  <section class="space-y-2">
                    <h3
                      class="text-lg mb-3 font-semibold dark:text-gray-200 text-gray-600"
                    >
                      Shipping Address
                    </h3>

                    <div class="flex gap-3 flex-1">
                      <div class="w-1/2">
                        <input
                          type="text"
                          v-model="customer.shippingAddress.house_number"
                          name="house_number"
                          required
                          class="block w-full px-3 py-2 border border-black/30 dark:border-gray-600 placeholder-black/80 dark:placeholder-gray-300 bg-white/60 dark:bg-gray-600 text-black/90 dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 focus:bg-gray-700 sm:text-sm transition-colors rounded-md"
                          placeholder="House Number"
                        />

                        <small
                          v-if="
                            errors['shippingAddress.house_number'] &&
                            errors['shippingAddress.house_number'][0]
                          "
                          class="text-red-600"
                          >{{ errors["shippingAddress.house_number"][0] }}</small
                        >
                      </div>

                      <div class="w-1/2">
                        <input
                          type="text"
                          v-model="customer.shippingAddress.area"
                          name="area"
                          required
                          class="block w-full px-3 py-2 border border-black/30 dark:border-gray-600 placeholder-black/80 dark:placeholder-gray-300 bg-white/60 dark:bg-gray-600 text-black/90 dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 focus:bg-gray-700 sm:text-sm transition-colors rounded-md"
                          placeholder="Area"
                        />

                        <small
                          v-if="
                            errors['shippingAddress.area'] &&
                            errors['shippingAddress.area'][0]
                          "
                          class="text-red-600"
                          >{{ errors["shippingAddress.area"][0] }}</small
                        >
                      </div>
                    </div>

                    <div class="flex gap-3 flex-1">
                      <div class="w-1/2">
                        <input
                          type="text"
                          v-model="customer.shippingAddress.city"
                          name="city"
                          required
                          class="block w-full px-3 py-2 border border-black/30 dark:border-gray-600 placeholder-black/80 dark:placeholder-gray-300 bg-white/60 dark:bg-gray-600 text-black/90 dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 focus:bg-gray-700 sm:text-sm transition-colors rounded-md"
                          placeholder="City"
                        />

                        <small
                          v-if="
                            errors['shippingAddress.city'] &&
                            errors['shippingAddress.city'][0]
                          "
                          class="text-red-600"
                          >{{ errors["shippingAddress.city"][0] }}</small
                        >
                      </div>

                      <div class="w-1/2">
                        <input
                          type="text"
                          v-model="customer.shippingAddress.zip_code"
                          name="zip_code"
                          required
                          class="block w-full px-3 py-2 border border-black/30 dark:border-gray-600 placeholder-black/80 dark:placeholder-gray-300 bg-white/60 dark:bg-gray-600 text-black/90 dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 focus:bg-gray-700 sm:text-sm transition-colors rounded-md"
                          placeholder="Zip Code"
                        />

                        <small
                          v-if="
                            errors['shippingAddress.zip_code'] &&
                            errors['shippingAddress.zip_code'][0]
                          "
                          class="text-red-600"
                          >{{ errors["shippingAddress.zip_code"][0] }}</small
                        >
                      </div>
                    </div>

                    <div class="flex gap-3 flex-1">
                      <div class="w-1/2">
                        <select
                          v-model="customer.shippingAddress.country_code"
                          class="block w-full px-3 py-2 border border-black/30 dark:border-gray-600 placeholder-black/80 dark:placeholder-gray-300 bg-white/60 dark:bg-gray-600 text-black/90 dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 focus:bg-gray-700 sm:text-sm transition-colors rounded-md"
                        >
                          <option v-for="country of countries" :value="country.code">
                            {{ country.name }}
                          </option>
                        </select>

                        <small
                          v-if="
                            errors['shippingAddress.country_code'] &&
                            errors['shippingAddress.country_code'][0]
                          "
                          class="text-red-600"
                          >{{ errors["shippingAddress.country_code"][0] }}</small
                        >
                      </div>

                      <div class="w-1/2">
                        <template v-if="shippingCountry && !shippingCountry.states">
                          <input
                            type="text"
                            v-model="customer.shippingAddress.state"
                            name="state"
                            required
                            class="block w-full px-3 py-2 border border-black/30 dark:border-gray-600 placeholder-black/80 dark:placeholder-gray-300 bg-white/60 dark:bg-gray-600 text-black/90 dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 focus:bg-gray-700 sm:text-sm transition-colors rounded-md"
                            placeholder="State"
                          />
                        </template>

                        <template v-else>
                          <select
                            v-model="customer.shippingAddress.state"
                            required
                            class="block w-full px-3 py-2 border border-black/30 dark:border-gray-600 placeholder-black/80 dark:placeholder-gray-300 bg-white/60 dark:bg-gray-600 text-black/90 dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 focus:bg-gray-700 sm:text-sm transition-colors rounded-md"
                          >
                            <option v-for="state of shippingStates" :value="state.key">
                              {{ state.value }}
                            </option>
                          </select>
                        </template>

                        <small
                          v-if="
                            errors['shippingAddress.state'] &&
                            errors['shippingAddress.state'][0]
                          "
                          class="text-red-600"
                          >{{ errors["shippingAddress.state"][0] }}</small
                        >
                      </div>
                    </div>
                  </section>
                  <!--/ Shipping Address -->
                </main>
                <footer
                  class="bg-black/5 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-lg"
                >
                  <button
                    type="submit"
                    class="py-2 w-full sm:w-auto px-4 border border-transparent text-sm font-medium rounded-md text-white/90 bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 transition-colors"
                  >
                    Submit
                  </button>
                  <button
                    type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-black/30 shadow-sm px-4 py-2 bg-black/20 hover:bg-black/10 text-base font-medium text-white/70 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black/40 sm:mt-0 sm:w-auto sm:text-sm transition-colors"
                    @click="closeCustomerForm"
                    ref="cancelButton"
                  >
                    Cancel
                  </button>
                </footer>
              </form>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import {
  Dialog,
  DialogPanel,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from "@headlessui/vue";
import { computed, onMounted, ref } from "vue";
import { XMarkIcon } from "@heroicons/vue/24/solid";
import Spinner from "../../components/core/Spinner.vue";
import store from "../../store/index.js";
import CustomInputV3 from "../../components/core/CustomInputV3.vue";
import { useRouter } from "vue-router";

const DEFAULT_CUSTOMER = {
  id: "",
  first_name: "",
  last_name: "",
  phone: "",
  email: "",
  status: "",
  billingAddress: {
    house_number: "",
    area: "",
    city: "",
    zip_code: "",
    country_code: "bd",
    state: "",
  },
  shippingAddress: {
    house_number: "",
    area: "",
    city: "",
    zip_code: "",
    country_code: "bd",
    state: "",
  },
};

const customer = ref({ ...DEFAULT_CUSTOMER });
const formState = defineModel("modelValue");
const router = useRouter();

function closeCustomerForm() {
  formState.value = false;
  customer.value = { ...DEFAULT_CUSTOMER };
}

const loading = ref(false);
const errors = ref({});

function submit() {
  loading.value = true;

  store
    .dispatch("createCustomer", customer.value)
    .then((response) => {
      loading.value = false;
      // console.log("response", response);

      if (response.status === 201) {
        // TODO: Show create success message
        formState.value = false;
        router.push({ name: "app.customers" });
      }
    })
    .catch((error) => {
      loading.value = false;
      errors.value = error.data.errors;
      console.error("Error creating customer:", error);
    });
}

onMounted(() => {
  store.dispatch("getCountries");
});

const countries = computed(() => store.state.countries);

const billingCountry = computed(() =>
  store.state.countries.find(
    (country) => country.code === customer.value.billingAddress.country_code
  )
);
const billingStates = computed(() => {
  return billingCountry.value.states !== null
    ? Object.entries(billingCountry.value.states).map((state) => ({
        key: state[0],
        value: state[1],
      }))
    : {};
});

const shippingCountry = computed(() =>
  store.state.countries.find(
    (country) => country.code === customer.value.shippingAddress.country_code
  )
);
const shippingStates = computed(() => {
  return shippingCountry.value.states !== null
    ? Object.entries(shippingCountry.value.states).map((state) => ({
        key: state[0],
        value: state[1],
      }))
    : {};
});
</script>
