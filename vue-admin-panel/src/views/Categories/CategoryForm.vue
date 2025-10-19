<template>
  <TransitionRoot as="template" :show="categoryShow">
    <Dialog as="div" class="relative z-10" @close="categoryShow = false">
      <TransitionChild
        as="template"
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black/60 transition-colors" />
      </TransitionChild>

      <section class="fixed z-10 inset-0 overflow-y-auto">
        <div
          class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0"
        >
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 sm:scale-100"
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <DialogPanel
              class="relative bg-white/90 dark:bg-gray-400 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-[700px] sm:w-full"
            >
              <Spinner
                v-if="loading"
                class="absolute inset-0 flex items-center justify-center"
              />
              <header class="py-3 px-4 flex justify-between items-center">
                <DialogTitle as="h3" class="text-lg leading-6 font-medium text-gray-900">
                  {{
                    category.id
                      ? `Update Category: "${props.category.name}"`
                      : "Create new Category"
                  }}
                </DialogTitle>
                <button
                  @click="closeCategoryForm"
                  class="w-8 h-8 flex items-center justify-center rounded-full transition-colors cursor-pointer hover:bg-[rgba(0,0,0,0.2)]"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"
                    />
                  </svg>
                </button>
              </header>

              <pre>{{ category }}</pre>

              <form @submit.prevent="submitCategory">
                <section class="bg-white/80 dark:bg-gray-500 px-4 pt-5 pb-4 space-y-2">
                  <div>
                    <input
                      type="text"
                      v-model="category.name"
                      label="Name"
                      idFor="name"
                      name="name"
                      required
                      class="block w-full px-3 py-2 border border-black/30 dark:border-gray-600 placeholder-black/80 dark:placeholder-gray-300 bg-white/60 dark:bg-gray-600 text-black/90 dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 focus:bg-gray-700 sm:text-sm transition-colors rounded-md"
                      placeholder="Category Name"
                    />
                    <small
                      v-if="errors['name'] && errors['name'][0]"
                      class="text-red-600"
                      >{{ errors["name"][0] }}</small
                    >
                  </div>

                  <div>
                    <select
                      v-model="category.parent_id"
                      required
                      class="block w-full px-3 py-2 border border-black/30 dark:border-gray-600 placeholder-black/80 dark:placeholder-gray-300 bg-white/60 dark:bg-gray-600 text-black/90 dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 focus:bg-gray-700 sm:text-sm transition-colors rounded-md"
                    >
                      <option
                        v-for="(parentCategory, index) in parentCategories"
                        :key="index"
                        :value="parentCategory.key"
                      >
                        {{ parentCategory.name }}
                      </option>
                    </select>

                    <small
                      v-if="errors['parent_id'] && errors['parent_id'][0]"
                      class="text-red-600"
                      >{{ errors["parent_id"][0] }}</small
                    >
                  </div>

                  <div class="flex gap-2 items-center">
                    <input
                      :id="category.id"
                      type="checkbox"
                      name="category_state"
                      v-model="category.active"
                      :checked="category.active"
                      class="w-5 h-5 appearance-none rounded relative transition-all checked:after:absolute checked:after:top-1/2 after:left-1/2 checked:after:content-['✔'] checked:after:-translate-x-1/2 checked:after:-translate-y-1/2"
                      :class="[
                        category.active
                          ? 'bg-indigo-700 hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-700 focus:ring-offset-2'
                          : 'bg-gray-300 hover:bg-gray-500 dark:bg-gray-700 dark:hover:bg-gray-800',
                      ]"
                    />
                    <label
                      :for="category.id"
                      class="font-medium capitalize cursor-pointer"
                      :class="{
                        'text-gray-300': !category.active,
                        'text-indigo-700 dark:text-indigo-100': category.active,
                      }"
                      >{{ category.active ? "Active" : "Inactive" }}</label
                    >
                  </div>
                </section>
                <footer
                  class="bg-gray-50 dark:bg-black/5 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
                >
                  <button
                    type="submit"
                    class="mt-3 w-full inline-flex justify-center rounded-md shadow-sm px-4 py-2 text-base font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm text-white/90 bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500 transition-colors"
                  >
                    Submit
                  </button>
                  <button
                    type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-black/30 shadow-sm px-4 py-2 bg-black/20 hover:bg-black/10 text-base font-medium text-white/70 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black/40 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors"
                    @click="closeCategoryForm"
                    ref="cancelButtonRef"
                  >
                    Cancel
                  </button>
                </footer>
              </form>
            </DialogPanel>
          </TransitionChild>
        </div>
      </section>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { computed, onUpdated, ref } from "vue";
import {
  Dialog,
  DialogPanel,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from "@headlessui/vue";
import store from "@/store/index.js";
import Spinner from "@/components/core/Spinner.vue";

const loading = ref(false);
const errors = ref({});

const props = defineProps({
  category: {
    required: true,
    type: Object,
  },
});

const category = ref({
  id: props.category.id,
  name: props.category.name,
  active: props.category.active === 1 ? true : false,
  parent_id: props.category.parent_id,
});

const categoryForm = defineModel("modelValue");
const emit = defineEmits(["close"]);
const DEFAULT_CATEGORY = {
  id: 0,
  name: "",
  active: 0,
  parent_id: 0,
};

const categoryShow = computed({
  get: () => categoryForm.value,
  set: (value) => (categoryForm.value = value),
});

const parentCategories = computed(() => {
  return [
    { key: 0, name: "Select Parent Category" },
    ...store.state.categories.data
      .filter((stateCategory) => {
        if (category.value.id) {
          return stateCategory.id !== category.value.id;
        }
        return true;
      })
      .map((category) => ({ key: category.id, name: category.name }))
      .sort((category1, category2) => {
        if (category1.text < category2.text) return -1;
        if (category1.text > category2.text) return 1;
        return 0;
      }),
  ];
});

onUpdated(() => {
  category.value = {
    id: props.category.id,
    name: props.category.name,
    active: props.category.active === 1 ? true : false,
    parent_id: props.category.parent_id,
  };
});

function closeCategoryForm() {
  categoryShow.value = false;
  emit("close");
  errors.value = {};
  category.value = { ...DEFAULT_CATEGORY };
}

function submitCategory() {
  loading.value = true;
  category.value.active = !!category.value.active;
  if (category.value.id) {
    store
      .dispatch("updateCategory", category.value)
      .then((response) => {
        loading.value = false;
        if (response.status === 200) {
          store.commit("showToast", "Category was successfully updated");
          store.dispatch("getCategories");
          closeCategoryForm();
        }
      })
      .catch((err) => {
        loading.value = false;
        errors.value = err.response.data.errors;
      });
  } else {
    store
      .dispatch("createCategory", category.value)
      .then((response) => {
        loading.value = false;
        if (response.status === 201) {
          store.commit("showToast", "Category was successfully created");
          store.dispatch("getCategories");
          closeCategoryForm();
        }
      })
      .catch((err) => {
        loading.value = false;
        errors.value = err.response.data.errors;
      });
  }
}
</script>
