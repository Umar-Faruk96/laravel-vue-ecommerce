<template>
  <div class="flex items-center justify-between mb-3">
    <h1 class="text-black/60 dark:text-white/70 sm:text-3xl font-semibold">Categories</h1>
    <button
      type="button"
      @click="showCategoryForm()"
      class="py-2 px-4 border border-transparent shadow-sm text-xs sm:text-sm font-medium rounded-md text-white/80 bg-indigo-600 dark:bg-indigo-800 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
    >
      Add New Category
    </button>
  </div>

  <CategoriesTable @clickEdit="editCategory" />

  <!-- <CategoryForm v-model="categoryForm" :category="categoryData" @close="closeCategoryForm" /> -->
</template>

<script setup>
import { computed, ref } from "vue";
import store from "@/store";
import CategoryForm from "./CategoryForm.vue";
import CategoriesTable from "./CategoriesTable.vue";

const DEFAULT_CATEGORY = {
  id: "",
  title: "",
  description: "",
  image: "",
  price: "",
};

const categories = computed(() => store.state.categories);

const categoryData = ref({ ...DEFAULT_CATEGORY });
const categoryForm = ref(false);

function showCategoryForm() {
  categoryForm.value = true;
}

function editCategory(category) {
  categoryData.value = category;
  showCategoryForm();
}

function closeCategoryForm() {
  categoryData.value = { ...DEFAULT_CATEGORY };
}
</script>
