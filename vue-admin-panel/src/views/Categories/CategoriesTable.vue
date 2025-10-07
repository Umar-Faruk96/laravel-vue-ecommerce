<template>
  <section
    class="bg-black/10 p-4 rounded-lg shadow animate-fade-in-down text-sm sm:text-base"
    style="animation-delay: 0.2s"
  >
    <!-- Categories Total Count -->
    <div class="flex pb-3 ml-3 text-black/60 dark:text-white/70">
      <span>Found {{ categories.data.length }} categories</span>
    </div>
    <!-- / Categories Total Count -->

    <!-- Categories List -->
    <table class="table-auto w-full text-black/60 dark:text-white/70">
      <thead>
        <tr>
          <TableHeaderCell
            field="id"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            @click="sortCategories('id')"
          >
            ID
          </TableHeaderCell>
          <TableHeaderCell
            field="name"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            @click="sortCategories('name')"
          >
            Name
          </TableHeaderCell>
          <TableHeaderCell
            field="slug"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            @click="sortCategories('slug')"
          >
            Slug
          </TableHeaderCell>
          <TableHeaderCell
            field="active"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            @click="sortCategories('active')"
          >
            Active
          </TableHeaderCell>
          <TableHeaderCell
            field="parent_id"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            @click="sortCategories('parent_id')"
          >
            Parent
          </TableHeaderCell>
          <TableHeaderCell
            field="created_at"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            @click="sortCategories('created_at')"
          >
            Create Date
          </TableHeaderCell>
          <TableHeaderCell field="actions"> Actions </TableHeaderCell>
        </tr>
      </thead>
      <!-- Categories Loading -->
      <tbody v-if="categories.loading || !categories.data.length">
        <tr>
          <td colspan="7">
            <Spinner v-if="categories.loading" class="mt-4" />
            <p v-else class="text-center py-8 text-gray-700 dark:text-white/70">
              There are no categories
            </p>
          </td>
        </tr>
      </tbody>
      <!-- / Categories Loading -->

      <!-- Categories Data -->
      <tbody v-else>
        <tr v-for="category of categories.data">
          <td class="border-b dark:border-b-gray-400 p-2">{{ category.id }}</td>
          <td class="border-b dark:border-b-gray-400 p-2">
            {{ category.name }}
          </td>
          <td
            class="border-b dark:border-b-gray-400 p-2 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis"
          >
            {{ category.slug }}
          </td>
          <td class="border-b dark:border-b-gray-400 p-2">
            {{ category.active ? "Yes" : "No" }}
          </td>
          <td class="border-b dark:border-b-gray-400 p-2">
            {{ category.parent?.name }}
          </td>
          <td class="border-b dark:border-b-gray-400 p-2">
            {{ category.created_at }}
          </td>
          <td class="border-b dark:border-b-gray-400 p-2">
            <Menu as="div" class="relative inline-block text-left">
              <div>
                <MenuButton
                  class="inline-flex items-center justify-center rounded-full w-10 h-10 bg-black bg-opacity-0 text-sm font-medium hover:bg-opacity-5 focus:bg-opacity-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/90 focus-visible:ring-opacity-75"
                >
                  <EllipsisVerticalIcon
                    class="h-5 w-5 text-indigo-500 dark:text-indigo-300"
                    aria-hidden="true"
                  />
                </MenuButton>
              </div>

              <transition
                enter-active-class="transition duration-100 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-75 ease-in"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
              >
                <MenuItems
                  class="absolute right-0 mt-2 w-32 origin-top-right rounded-md bg-white/90 dark:bg-gray-400 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                >
                  <div class="px-1 py-1">
                    <MenuItem v-slot="{ active }">
                      <button
                        :class="[
                          active ? 'bg-indigo-600 text-white/90' : 'text-gray-900',
                          'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                        ]"
                        @click="editCategory(category)"
                      >
                        <PencilIcon
                          :active="active"
                          class="mr-2 h-5 w-5 text-black/60 group-hover:text-black/80"
                          aria-hidden="true"
                        />
                        Edit
                      </button>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                      <button
                        :class="[
                          active ? 'bg-indigo-600 text-white/90' : 'text-gray-900',
                          'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                        ]"
                        @click="deleteCategory(category)"
                      >
                        <TrashIcon
                          :active="active"
                          class="mr-2 h-5 w-5 text-black/60 group-hover:text-black/80"
                          aria-hidden="true"
                        />
                        Delete
                      </button>
                    </MenuItem>
                  </div>
                </MenuItems>
              </transition>
            </Menu>
          </td>
        </tr>
      </tbody>
      <!-- / Categories Data -->
    </table>
    <!-- / Categories List -->
  </section>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import store from "@/store";
import Spinner from "@/components/core/Spinner.vue";
import TableHeaderCell from "@/components/core/ProductsTable/TableHeaderCell.vue";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { EllipsisVerticalIcon, PencilIcon, TrashIcon } from "@heroicons/vue/24/solid";

const categories = computed(() => store.state.categories);
const sortField = ref("name");
const sortDirection = ref("asc");

const emit = defineEmits(["clickEdit"]);

onMounted(() => {
  getCategories();
});

function getCategories(url = null) {
  store.dispatch("getCategories", {
    url,
    sort_field: sortField.value,
    sort_direction: sortDirection.value,
  });
}

function sortCategories(field) {
  if (field === sortField.value) {
    if (sortDirection.value === "desc") {
      sortDirection.value = "asc";
    } else {
      sortDirection.value = "desc";
    }
  } else {
    sortField.value = field;
    sortDirection.value = "asc";
  }

  getCategories();
}

function deleteCategory(category) {
  if (!confirm(`Are you sure you want to delete the category?`)) {
    return;
  }
  store.dispatch("deleteCategory", category).then((res) => {
    store.commit("showToast", "Category was successfully deleted");
    store.dispatch("getCategories");
  });
}

function editCategory(category) {
  emit("clickEdit", category);
}
</script>
