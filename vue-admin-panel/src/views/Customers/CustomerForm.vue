<template>
  <TransitionRoot appear :show="formModal" as="template">
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
        <div class="fixed inset-0 bg-black/60" />
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
                class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white/90 p-4 text-left align-middle shadow-xl transition-all"
            >
              <Spinner
                  v-if="loading"
                  class="absolute inset-0 flex items-center justify-center"
              />

              <header class="py-3 px-4 flex justify-between items-center">
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-black/90">
                  {{
                    customer.id ? `Edit Customer: "${props.customer.first_name + " " + props.customer.last_name}"` : "Create new Customer"
                  }}
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
                <main class="bg-white/80 px-4 pt-5 pb-4 space-y-4">
                  <section>
                    <h2 class="text-lg font-semibold dark:text-gray-600">Customer Info</h2>

                    <div class="flex gap-3 flex-1">
                      <CustomInputNew v-model:first-name="customer.first_name" label="First Name" name="first_name"
                                      required :errors="errors['first_name']"
                      />

                      <CustomInputNew v-model:last-name="customer.last_name" label="Last Name" name="last_name" required
                                      :errors="errors['last_name']"
                      />
                    </div>

                    <CustomInputNew type="email" v-model:email="customer.email" label="Email" name="email" required
                                    :errors="errors['email']"
                    />

                    <div class="flex gap-3 items-center">
                      <div class="w-3/4">
                        <input type="text" v-model="customer.phone" name="phone" required
                               class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md"
                               placeholder="Phone Number"
                        />
                        <small v-if="errors['phone'] && errors['phone'][0]" class="text-red-600">{{
                            errors['phone'][0]
                          }}</small>
                      </div>

                      <CustomInputNew type="checkbox" v-model:status="customer.status" name="status" required
                                      :errors="errors['status']" :id="customer.id === '' ? 1 : customer.id"
                      />
                    </div>
                  </section>

                  <section>
                    <h3 class="text-lg font-semibold dark:text-gray-600">Billing Address</h3>

                    <div class="flex gap-3 flex-1">
                      <div class="w-1/2">
                        <input type="text" v-model="customer.billingAddress.house_number" name="house_number" required
                               class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md"
                               placeholder="House Number"
                        />
                        <small v-if="errors['billingAddress.house_number'] && errors['billingAddress.house_number'][0]"
                               class="text-red-600">{{
                            errors['billingAddress.house_number'][0]
                          }}</small>
                      </div>

                      <div class="w-1/2">
                        <input type="text" v-model="customer.billingAddress.area" name="area" required
                               class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md"
                               placeholder="Area"
                        />
                        <small v-if="errors['billingAddress.area'] && errors['billingAddress.area'][0]"
                               class="text-red-600">{{
                            errors['billingAddress.area'][0]
                          }}</small>
                      </div>
                    </div>

                    <div class="flex gap-3 flex-1">
                      <div class="w-1/2">
                        <input type="text" v-model="customer.billingAddress.city" name="city" required
                               class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md"
                               placeholder="City"
                        />

                        <small v-if="errors['billingAddress.city'] && errors['billingAddress.city'][0]"
                               class="text-red-600">{{
                            errors['billingAddress.city'][0]
                          }}</small>
                      </div>

                      <div class="w-1/2">
                        <input type="text" v-model="customer.billingAddress.state" name="state" required
                               class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md"
                               placeholder="State"
                        />
                        <small v-if="errors['billingAddress.state'] && errors['billingAddress.state'][0]"
                               class="text-red-600">{{
                            errors['billingAddress.state'][0]
                          }}</small>
                      </div>
                    </div>
                    <div class="flex gap-3 flex-1">
                      <div class="w-1/2">
                        <input type="text" v-model="customer.billingAddress.zip_code" name="zip_code" required
                               class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md"
                               placeholder="Zip Code"
                        />
                        <small v-if="errors['billingAddress.zip_code'] && errors['billingAddress.zip_code'][0]"
                               class="text-red-600">{{
                            errors['billingAddress.zip_code'][0]
                          }}</small>
                      </div>

                      <div class="w-1/2">
                        <select v-model="customer.billingAddress.country_code"
                                class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md">
                          <option v-for="country of countries" :value="country.code">{{ country.name }}</option>
                        </select>

                        <small
                            v-if="errors['billingAddress.country_code'] && errors['billingAddress.country_code'][0]"
                            class="text-red-600">{{
                            errors['billingAddress.country_code'][0]
                          }}</small>
                      </div>
                    </div>
                  </section>

                  <section>
                    <h3 class="text-lg font-semibold dark:text-gray-600">Shipping Address</h3>

                    <div class="flex gap-3 flex-1">
                      <div class="w-1/2">
                        <input type="text" v-model="customer.shippingAddress.house_number" name="house_number" required
                               class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md"
                               placeholder="House Number"
                        />

                        <small
                            v-if="errors['shippingAddress.house_number'] && errors['shippingAddress.house_number'][0]"
                            class="text-red-600">{{
                            errors['shippingAddress.house_number'][0]
                          }}</small>
                      </div>

                      <div class="w-1/2">
                        <input type="text" v-model="customer.shippingAddress.area" name="area" required
                               class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md"
                               placeholder="Area"
                        />

                        <small v-if="errors['shippingAddress.area'] && errors['shippingAddress.area'][0]"
                               class="text-red-600">{{
                            errors['shippingAddress.area'][0]
                          }}</small>
                      </div>
                    </div>

                    <div class="flex gap-3 flex-1">
                      <div class="w-1/2">
                        <input type="text" v-model="customer.shippingAddress.city" name="city" required
                               class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md"
                               placeholder="City"
                        />

                        <small v-if="errors['shippingAddress.city'] && errors['shippingAddress.city'][0]"
                               class="text-red-600">{{
                            errors['shippingAddress.city'][0]
                          }}</small>
                      </div>

                      <div class="w-1/2">
                        <input type="text" v-model="customer.shippingAddress.state" name="state" required
                               class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md"
                               placeholder="State"
                        />

                        <small v-if="errors['shippingAddress.state'] && errors['shippingAddress.state'][0]"
                               class="text-red-600">{{
                            errors['shippingAddress.state'][0]
                          }}</small>
                      </div>
                    </div>

                    <div class="flex gap-3 flex-1">
                      <div class="w-1/2">
                        <input type="text" v-model="customer.shippingAddress.zip_code" name="zip_code" required
                               class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md"
                               placeholder="Zip Code" />

                        <small v-if="errors['shippingAddress.zip_code'] && errors['shippingAddress.zip_code'][0]"
                               class="text-red-600">{{
                            errors['shippingAddress.zip_code'][0]
                          }}</small>
                      </div>

                      <div class="w-1/2">
                        <select v-model="customer.shippingAddress.country_code"
                                class="block w-full mt-3 px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors rounded-md">
                          <option v-for="country of countries" :value="country.code">{{ country.name }}</option>
                        </select>

                        <small
                            v-if="errors['shippingAddress.country_code'] && errors['shippingAddress.country_code'][0]"
                            class="text-red-600">{{
                            errors['shippingAddress.country_code'][0]
                          }}</small>
                      </div>
                    </div>
                  </section>
                </main>

                <footer class="bg-black/5 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                  <button
                      type="submit"
                      class="py-2 w-full sm:w-auto px-4 border border-transparent text-sm font-medium rounded-md text-white/90 bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 transition-colors"
                  >
                    Submit
                  </button>
                  <button
                      type="button"
                      class="mt-3 w-full inline-flex justify-center rounded-md border border-black/30 shadow-sm px-4 py-2 bg-black/20 text-base font-medium text-black/80 hover:bg-black/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black/40 sm:mt-0 sm:w-auto sm:text-sm transition-colors"
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
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from "@headlessui/vue";
import {computed, onUpdated, ref} from "vue";
import {XMarkIcon} from "@heroicons/vue/24/solid";
import Spinner from "../../components/core/Spinner.vue";
import store from "../../store/index.js";
import CustomInputNew from "../../components/core/CustomInputNew.vue";

const props = defineProps({
  customer: {
    type: Object,
    required: true,
  },
});

const customer = ref({
  id: props.customer.id,
  first_name: props.customer.first_name,
  last_name: props.customer.last_name,
  email: props.customer.email,
  phone: props.customer.phone,
  status: props.customer.status,
  billingAddress: {
    ...props.customer.billing_address,
  },
  shippingAddress: {
    ...props.customer.shipping_address
  }
});

onUpdated(() => {
  customer.value = {
    id: props.customer.id,
    first_name: props.customer.first_name,
    last_name: props.customer.last_name,
    email: props.customer.email,
    phone: props.customer.phone,
    status: props.customer.status,
    billingAddress: {
      ...props.customer.billing_address
    },
    shippingAddress: {
      ...props.customer.shipping_address
    }
  };
});

const formModal = defineModel("formModal");
const emit = defineEmits(["close"]);

function closeCustomerForm() {
  formModal.value = false;
  emit("close");
}

const loading = ref(false);
const errors = ref({});

function submit() {
  loading.value = true;

  if (customer.value.id) {
    store.dispatch("updateCustomer", customer.value).then((response) => {
      loading.value = false;

      if (response.status === 200) {
        // TODO: Show update success message
        store.dispatch("getCustomers");
        closeCustomerForm();
      }
    }).catch((error) => {
      loading.value = false;
      errors.value = error.data.errors;
    });
  } else {
    store
        .dispatch("createCustomer", customer.value)
        .then((response) => {
          loading.value = false;
          // console.log("response", response);

          if (response.status === 201) {
            // TODO: Show create success message
            store.dispatch("getCustomers");
            closeCustomerForm();
          }
        })
        .catch((error) => {
          loading.value = false;
          errors.value = error.data.errors;
          console.error("Error creating customer:", error);
        });
  }
}

const countries = computed(() => store.state.countries);
</script>