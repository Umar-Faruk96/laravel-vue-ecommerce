<template>
  <section class="flex items-center justify-between mb-3">
    <h1 class="text-black/60 text-3xl font-semibold">Customers</h1>

    <button
        @click="openCustomerFormModal"
        type="button"
        class="flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white/80 bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
    >
      Add New Customer
    </button>
  </section>

  <CreateCustomerForm
      v-model:form-modal="customerForm"
      :customer="customerData"
      @close="clearCustomerForm"
  />

  <CustomersTable @edit-customer="editCustomerForm" />
</template>

<script setup>
import {ref} from "vue";
import CustomersTable from "./CustomersTable.vue";
import CreateCustomerForm from "./CreateCustomerForm.vue";

const customerForm = ref(false);

const openCustomerFormModal = () => {
  customerForm.value = true;
};

const DEFAULT_CUSTOMER = {
  id: "",
  first_name: "",
  last_name: "",
  phone: "",
  email: "",
  status: "",
};

const customerData = ref({...DEFAULT_CUSTOMER});

const editCustomerForm = (customer) => {
  customerData.value = customer;
  openCustomerFormModal();
};

const clearCustomerForm = () => {
  customerData.value = {...DEFAULT_CUSTOMER};
};
</script>