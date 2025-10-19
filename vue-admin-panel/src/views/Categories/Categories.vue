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

  <CategoriesTable @click="editCategory" />

  <CategoryForm
    v-model="categoryFormState"
    :category="categoryData"
    @close="closeCategoryForm"
  />
</template>

<script setup>
import { ref } from "vue";
import CategoryForm from "./CategoryForm.vue";
import CategoriesTable from "./CategoriesTable.vue";

const DEFAULT_CATEGORY = {
  id: 0,
  name: "",
  active: 0,
  parent_id: 0,
};

const categoryData = ref({ ...DEFAULT_CATEGORY });
const categoryFormState = ref(false);

function showCategoryForm() {
  categoryFormState.value = true;
}

function editCategory(category) {
  // console.log(category);
  category.parent_id = category.parent_id ?? 0;
  categoryData.value = category;
  showCategoryForm();
}

function closeCategoryForm() {
  categoryData.value = { ...DEFAULT_CATEGORY };
}
</script>
