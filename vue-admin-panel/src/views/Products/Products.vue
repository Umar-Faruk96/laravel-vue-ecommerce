<template>
  <section class="flex items-center justify-between mb-3">
    <h1 class="text-black/60 dark:text-white/70 sm:text-3xl font-semibold">Products</h1>

    <button
      @click="openProductFormModal"
      type="button"
      class="flex justify-center py-2 px-4 border border-transparent shadow-sm text-xs sm:text-sm font-medium rounded-md text-white/80 bg-indigo-600 dark:bg-indigo-800 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
    >
      Add New Product
    </button>
  </section>

  <!-- <CreateProductForm v-model:form-modal="productForm" :product="product" /> -->
  <CreateProductFormNew
    v-model:form-modal="productForm"
    :product="productData"
    @close="clearProductForm"
  />

  <ProductsTable @edit-product="editProductForm" />
</template>

<script setup>
import { ref } from "vue";
import ProductsTable from "./ProductsTable.vue";
// import CreateProductForm from './CreateProductForm.vue';
import CreateProductFormNew from "./CreateProductFormNew.vue";
import store from "../../store/index.js";

const productForm = ref(false);

const openProductFormModal = () => {
  productForm.value = true;
};

const DEFAULT_PRODUCT = {
  id: "",
  title: "",
  description: "",
  price: "",
  image: "",
};

const productData = ref({ ...DEFAULT_PRODUCT });

const editProductForm = (product) => {
  store.dispatch("getProduct", product.id).then(({ data }) => {
    productData.value = data;
    openProductFormModal();
  });
};

const clearProductForm = () => {
  productData.value = { ...DEFAULT_PRODUCT };
};
</script>
