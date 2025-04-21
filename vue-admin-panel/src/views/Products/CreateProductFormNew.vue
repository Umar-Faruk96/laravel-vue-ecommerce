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
              class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white/90 p-4 text-left align-middle shadow-xl transition-all"
            >
              <Spinner
                v-if="loading"
                class="absolute inset-0 flex items-center justify-center"
              />

              <header class="py-3 px-4 flex justify-between items-center">
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-black/90">
                  {{
                    product.id
                      ? `Edit Product: "${props.product.title}"`
                      : "Create new Product"
                  }}
                </DialogTitle>

                <button
                  @click="closeProductForm"
                  type="button"
                  class="text-black/60 hover:text-black/90 focus:outline-none focus:text-black/90 transition-colors cursor-pointer hover:bg-black/20 rounded-full p-1"
                >
                  <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                </button>
              </header>

              <form @submit.prevent="submit">
                <main class="bg-white/80 px-4 pt-5 pb-4">
                  <CustomInputNew
                    class="mb-2"
                    v-model:title="product.title"
                    label="Product Title"
                  />

                  <CustomInputNew
                    type="file"
                    class="mb-2"
                    label="Product Image"
                    @change="(file) => (product.image = file)"
                  />

                  <CustomInputNew
                    type="textarea"
                    class="mb-2"
                    v-model:textarea="product.description"
                    label="Description"
                  />

                  <CustomInputNew
                    type="number"
                    class="mb-2"
                    v-model:number="product.price"
                    label="Price"
                    prepend="$"
                  />
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
import CustomInputNew from "../../components/core/CustomInputNew.vue";

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
});

onUpdated(() => {
  product.value = {
    id: props.product.id,
    title: props.product.title,
    description: props.product.description,
    price: props.product.price,
    image: props.product.image,
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
