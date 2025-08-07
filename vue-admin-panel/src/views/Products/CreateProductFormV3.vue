<template>
  <TransitionRoot appear :show="formModal" as="template">
    <Dialog as="div" @close="closeProductForm" class="relative z-10">
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
              class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white/90 dark:bg-gray-600 p-4 text-left align-middle shadow-xl transition-all"
            >
              <Spinner
                v-if="loading"
                class="absolute inset-0 flex items-center justify-center"
              />

              <header class="py-3 sm:px-4 flex justify-between items-center">
                <DialogTitle
                  as="h3"
                  class="text-sm sm:text-lg font-medium leading-6 text-black/90 dark:text-gray-300"
                >
                  {{
                    product.id
                      ? `Edit Product: "${props.product.title}"`
                      : "Create new Product"
                  }}
                </DialogTitle>

                <button
                  @click="closeProductForm"
                  type="button"
                  class="text-black/60 dark:text-gray-300 hover:text-black/90 focus:outline-none focus:text-black/90 transition-colors cursor-pointer hover:bg-white/20 rounded-full p-1"
                >
                  <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                </button>
              </header>

              <form @submit.prevent="submit">
                <main class="bg-white/80 dark:bg-gray-500 px-4 pt-5 pb-4 space-y-4">
                  <CustomInputV3
                    v-model:title="product.title"
                    label="Product Title"
                    name="product_title"
                    id-for="product_title"
                    required
                  />

                  <CustomInputV3
                    type="file"
                    label="Product Image"
                    @change="(file) => (product.image = file)"
                  />

                  <CustomInputV3
                    type="textarea"
                    v-model:textarea="product.description"
                    label="Description"
                  />

                  <CustomInputV3
                    type="number"
                    name="price"
                    id-for="product-price"
                    required
                    v-model:number="product.price"
                    label="Price"
                    prepend="&#2547;"
                  />

                  <div class="flex items-center gap-2">
                    <input
                      :id="product.id || 'product-status'"
                      type="checkbox"
                      :name="product.title || 'product-status'"
                      v-model="product.published"
                      class="w-5 h-5 appearance-none bg-gray-300 hover:bg-gray-500 dark:bg-gray-700 dark:hover:bg-gray-600 rounded checked:bg-indigo-700 checked:hover:bg-indigo-700 checked:focus:ring-2 checked:ring-indigo-700 checked:ring-offset-2 relative checked:after:absolute checked:after:top-1/2 checked:after:left-1/2 checked:after:content-['✔'] checked:after:-translate-x-1/2 checked:after:-translate-y-1/2 transition-all"
                      :checked="product.published"
                    />
                    <label
                      :for="product.id || 'product-status'"
                      class="text-sm text-black/80 dark:text-gray-300"
                      >Published</label
                    >
                  </div>
                </main>

                <footer
                  class="bg-black/5 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
                >
                  <button
                    type="submit"
                    class="py-2 w-full sm:w-auto px-4 border border-transparent text-sm font-medium rounded-md text-white/90 bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 transition-colors"
                  >
                    Submit
                  </button>
                  <button
                    type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-black/30 shadow-sm px-4 py-2 bg-black/20 dark:bg-gray-500 text-base font-medium text-black/80 dark:text-gray-300 dark:hover:text-gray-600 hover:bg-black/10 dark:hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black/40 sm:mt-0 sm:w-auto sm:text-sm transition-colors"
                    @click="closeProductForm"
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
import { onUpdated, ref } from "vue";
import { XMarkIcon } from "@heroicons/vue/24/solid";
import Spinner from "../../components/core/Spinner.vue";
import store from "../../store/index.js";
import CustomInputV3 from "../../components/core/CustomInputV3.vue";

const props = defineProps({
  product: {
    type: Object,
    required: true,
  },
});

const product = ref({
  id: props.product.id,
  title: props.product.title,
  description: props.product.description,
  price: props.product.price,
  image: props.product.image,
  published: props.product.published || false,
});

onUpdated(() => {
  product.value = {
    id: props.product.id,
    title: props.product.title,
    description: props.product.description,
    price: props.product.price,
    image: props.product.image,
    published: props.product.published || false,
  };
});

const formModal = defineModel("formModal");
const emit = defineEmits(["close"]);

function closeProductForm() {
  formModal.value = false;
  emit("close");
}

const loading = ref(false);

function submit() {
  loading.value = true;

  if (product.value.id) {
    store.dispatch("updateProduct", product.value).then((response) => {
      loading.value = false;

      if (response.status === 200) {
        // TODO: Show update success message
        store.dispatch("getProducts");
        closeProductForm();
      }
    });
  } else {
    store
      .dispatch("createProduct", product.value)
      .then((response) => {
        loading.value = false;
        // console.log("response", response);

        if (response.status === 201) {
          // TODO: Show create success message
          store.dispatch("getProducts");
          closeProductForm();
        }
      })
      .catch((error) => {
        loading.value = false;
        console.error("Error creating product:", error);
      });
  }
}
</script>
