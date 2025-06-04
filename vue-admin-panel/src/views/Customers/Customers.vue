<template>
  <section class="flex items-center justify-between mb-3">
    <h1 class="text-black/60 dark:text-white/70 sm:text-3xl font-semibold">Customers</h1>

    <button
        @click="openCustomerFormModal"
        type="button"
        class="flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white/80 bg-indigo-600 dark:bg-indigo-800 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
    >
      Add New Customer
    </button>
  </section>

  <CustomerForm
      v-model:form-modal="customerForm" />

  <CustomersTable />
</template>

<script setup>
import {ref} from "vue";
import CustomersTable from "./CustomersTable.vue";
import CustomerForm from "./CustomerForm.vue";

const customerForm = ref(false);

const openCustomerFormModal = () => {
  customerForm.value = true;
};
</script>