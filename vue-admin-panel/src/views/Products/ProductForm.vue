<template>
  <Spinner
      v-if="loading"
      class="absolute inset-0 flex items-center justify-center"
  />

  <template v-else>
    <header class="py-3 sm:px-4 flex justify-between items-center">
      <h2 class="dark:text-white/70 sm:text-xl font-semibold leading-6 text-black/90">
        {{
          product.id
              ? `Edit Product: "${product.title}"`
              : "Create new Product"
        }}</h2>

      <router-link
          :to="{ name: 'app.products' }"
          type="button"
          class="w-full inline-flex justify-center rounded-md border border-black/30 shadow-sm px-4 py-2 bg-black/20 dark:bg-gray-800 text-base font-medium text-black/80 dark:text-gray-100 hover:bg-black/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black/40 sm:mt-0 sm:w-auto sm:text-sm transition-colors"
      >
        Cancel
      </router-link>
    </header>

    <form @submit.prevent="submit">
      <main
          class="bg-white/80 dark:bg-gray-500 px-4 pt-5 pb-4 space-y-4 rounded-t-md">
        <CustomInputV3
            v-model:title="product.title"
            label="Product Title"
            name="product_title"
            id-for="product_title"
            required
            :errors="errors['title']"
        />

        <CustomInputV3
            type="file"
            label="Product Image"
            name="product_image"
            :errors="errors['product_image']"
            @change="(file) => (product.image = file)"
        />

        <CKEditor v-model="product.description"/>

        <CustomInputV3
            type="number"
            name="price"
            id-for="product-price"
            required
            v-model:number="product.price"
            label="Price"
            prepend="&#2547;"
            :errors="errors['price']"
        />

        <CustomInputV3
            type="number"
            name="quantity"
            id-for="product-quantity"
            required
            v-model:number="product.quantity"
            label="Quantity"
            :errors="errors['quantity']"
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
          class="bg-black/5 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-md"
      >
        <button
            type="submit"
            class="py-2 w-full sm:w-auto px-4 border border-transparent text-sm font-medium rounded-md text-white/90 bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 transition-colors"
        >
          Save
        </button>
        <button
            type="button"
            @click="submit($event, true)"
            class="py-2 w-full sm:w-auto px-4 border border-transparent text-sm font-medium rounded-md text-white/90 bg-fuchsia-500 hover:bg-fuchsia-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-fuchsia-500 sm:ml-3 transition-colors"
        >
          Save & Close
        </button>
        <router-link
            :to="{ name: 'app.products' }"
            type="button"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-black/30 shadow-sm px-4 py-2 bg-black/20 dark:bg-gray-500 text-base font-medium text-black/80 dark:text-gray-300 dark:hover:text-gray-600 hover:bg-black/10 dark:hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black/40 sm:mt-0 sm:w-auto sm:text-sm transition-colors"
        >
          Cancel
        </router-link>
      </footer>
    </form>
  </template>
</template>

<script setup>
import {onMounted, ref} from "vue";
import Spinner from "../../components/core/Spinner.vue";
import store from "../../store/index.js";
import CustomInputV3 from "../../components/core/CustomInputV3.vue";
import {useRouter} from "vue-router";
import CKEditor from "../../components/core/CKEditor.vue";

const props = defineProps({
  productId: {
    type: String,
    required: false,
  }
})

const product = ref({
  id: null,
  title: null,
  description: "",
  price: null,
  quantity: null,
  image: null,
  published: null,
});

onMounted(() => {
  if (props.productId) {
    loading.value = true;
    store.dispatch('getProduct', props.productId).then(({data}) => {
      product.value = data;
      loading.value = false;
    })
  }
});

const loading = ref(false);
const errors = ref({});
const router = useRouter();

function submit($event, close = false) {
  loading.value = true;

  if (product.value.id) {
    store.dispatch("updateProduct", product.value).then((response) => {
      loading.value = false;

      if (response.status === 200) {
        store.commit('showToast', 'Product was successfully updated.');
        if (close) {
          store.dispatch("getProducts");
          router.push({name: "app.products"})
        }
      }
    }).catch((error) => {
      loading.value = false;
      errors.value = error.data.errors;
      // console.error("Error updating product:", error);
    })
  } else {
    store
        .dispatch("createProduct", product.value)
        .then((response) => {
          loading.value = false;
          // console.log("response", response);

          if (response.status === 201) {
            store.commit('showToast', 'Product was successfully created.');
            if (close) {
              store.dispatch("getProducts");
              router.push({name: "app.products"});
            } else {
              product.value = response.data;
              router.push({
                name: "app.products.edit", params: {
                  productId:
                  response.data.id
                }
              });
            }
          }
        })
        .catch((error) => {
          loading.value = false;
          errors.value = error.data.errors;
          // console.error("Error creating product:", error);
        });
  }
}
</script>
