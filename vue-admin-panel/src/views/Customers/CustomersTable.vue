<template>
  <section
      class="bg-black/10 p-4 rounded-lg shadow animate-fade-in-down"
      style="animation-delay: 0.2s"
  >
    <!-- Sorting & Search -->
    <div class="flex justify-between border-b-2 dark:border-b-gray-400 pb-3">
      <section class="flex items-center">
        <span class="whitespace-nowrap mr-3 text-black/60 dark:text-white/70"
        >Per Page</span
        >

        <!-- Per Page Input -->
        <div class="grid">
          <ChevronDownIcon
              class="pointer-events-none z-10 right-1 relative col-start-1 row-start-1 h-4 w-4 self-center justify-self-end forced-colors:hidden fill-black/60"
              aria-hidden="true"
          />

          <select
              name="per-page"
              id="per-page"
              title="per-page"
              @change="getCustomers()"
              v-model="perPage"
              class="appearance-none forced-colors:appearance-auto row-start-1 col-start-1 relative block w-24 px-3 py-1 sm:py-2 border border-black/20 placeholder-black/60 dark:placeholder-white/50 bg-black/10 dark:bg-white/70 text-black/60 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
          >
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
        </div>
        <!--/ Per Page Input -->

        <!-- Customer Total Output -->
        <span class="ml-3 text-black/60 dark:text-white/70"
        >Found {{ customers.total }} customers</span
        >
      </section>

      <!-- Search -->
      <section class="grid">
        <MagnifyingGlassIcon
            class="cursor-pointer z-20 right-1 relative col-start-1 row-start-1 h-4 w-4 self-center justify-self-end forced-colors:hidden fill-black/60"
            aria-hidden="true"
        />

        <input
            type="text"
            name="search"
            id="search"
            placeholder="Type to Search Customers"
            v-model="search"
            @change="getCustomers()"
            class="appearance-none forced-colors:appearance-auto row-start-1 col-start-1 relative block px-6 py-1 sm:py-2 border border-black/20 placeholder-black/60 dark:bg-white/70 bg-black/10 text-black/60 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
        />
      </section>
      <!--/ Search -->
    </div>
    <!--/ Sorting & Search -->

    <!-- Customers Table -->
    <div class="overflow-scroll sm:overflow-visible">
      <table class="table-auto w-full text-black/60 dark:text-white/70">
        <thead>
        <tr>
          <TableHeaderCell @sort="sortCustomers" field="id" :sortField :sortDirection
          >ID
          </TableHeaderCell>
          <TableHeaderCell @sort="sortCustomers" field="name" :sortField :sortDirection
          >Name
          </TableHeaderCell>
          <TableHeaderCell @sort="sortCustomers" field="email" :sortField :sortDirection
          >Email
          </TableHeaderCell>
          <TableHeaderCell @sort="sortCustomers" field="phone">Phone</TableHeaderCell>
          <TableHeaderCell
              @sort="sortCustomers"
              field="status"
              :sortField
              :sortDirection
          >Status
          </TableHeaderCell>
          <TableHeaderCell
              @sort="sortCustomers"
              field="created_at"
              :sortField
              :sortDirection
          >Created At
          </TableHeaderCell>
          <TableHeaderCell field="actions">Actions</TableHeaderCell>
        </tr>
        </thead>
        <!-- Customers Loading or No Customers -->
        <tbody v-if="customers.loading || !customers.data.length">
        <tr>
          <td colspan="7">
            <Spinner v-if="customers.loading" class="mt-4" />
            <p v-else class="text-center py-8 text-gray-700 dark:text-white/70">
              There are no customers
            </p>
          </td>
        </tr>
        </tbody>
        <!--/ Customers Loading or No Customers -->
        <!-- Customers Data -->
        <tbody v-else>
        <tr
            v-for="(customer, index) of customers.data"
            :key="index"
            class="animate-fade-in-down"
            :style="{ animationDelay: `${index * 0.1}s`, position: 'relative', zIndex: Math.abs(index - 10) }"
        >
          <td class="border-b-2 dark:border-b-gray-400 p-2">{{ customer.id }}</td>
          <td
              class="border-b-2 dark:border-b-gray-400 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis"
          >
            {{ customer.first_name }} {{ customer.last_name }}
          </td>
          <td class="border-b-2 dark:border-b-gray-400 p-2">{{ customer.email }}</td>
          <td class="border-b-2 dark:border-b-gray-400 p-2">{{ customer.phone }}</td>
          <td
              :class="[
                customer.status ? 'text-indigo-600 dark:text-indigo-300 font-medium' : 'text-gray-500 dark:text-gray-400',
                'border-b-2 dark:border-b-gray-400 p-2 capitalize',
              ]"
          >
            {{ customer.status ? "active" : "inactive" }}
          </td>
          <td class="border-b-2 dark:border-b-gray-400 p-2">
            {{ customer.created_at }}
          </td>
          <td class="border-b-2 dark:border-b-gray-400 p-2">
            <Menu as="section" class="relative inline-block text-left">
              <div>
                <MenuButton
                    as="button"
                    class="inline-flex items-center justify-center rounded-full w-10 h-10 bg-black bg-opacity-0 text-sm font-medium text-white/90 hover:bg-opacity-5 focus:bg-opacity-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/90 focus-visible:ring-opacity-75"
                >
                  <EllipsisVerticalIcon
                      class="h-6 w-6 text-indigo-500 dark:text-indigo-300"
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
                    class="absolute right-0 z-10 mt-2 w-32 origin-top-right rounded-md bg-white/90 dark:bg-gray-400 shadow-lg ring-1 ring-black/90 ring-opacity-5 focus:outline-none"
                >
                  <div class="px-1 py-1">
                    <!-- Edit Customer -->
                    <MenuItem as="span" v-slot="{ active }">
                      <router-link
                          :to="{
                            name: 'app.customers.show',
                            params: { id: customer.id },
                          }"
                          :class="[
                            active ? 'bg-indigo-600 text-white/90' : 'text-black/80',
                            'group flex items-center w-full px-4 py-2 text-sm rounded-md',
                          ]"
                      >
                        <PencilIcon
                            :class="[
                              active
                                ? 'text-white/90'
                                : 'text-black/60 group-hover:text-black/80',
                              'w-5 h-5 mr-2',
                            ]"
                            aria-hidden="true"
                        />
                        Edit
                      </router-link>
                    </MenuItem>
                    <!--/ Edit Customer -->
                    <!-- Delete Customer -->
                    <MenuItem as="span" v-slot="{ active }">
                      <button
                          :class="[
                            active ? 'bg-indigo-600 text-white/90' : 'text-black/80',
                            'group flex items-center w-full px-4 py-2 text-sm rounded-md',
                          ]"
                          @click="deleteCustomer(customer.id)"
                      >
                        <TrashIcon
                            :class="[
                              active
                                ? 'text-white/90'
                                : 'text-black/60 group-hover:text-black/80',
                              'w-5 h-5 mr-2',
                            ]"
                            aria-hidden="true"
                        />
                        Delete
                      </button>
                    </MenuItem>
                    <!--/ Delete Customer -->
                  </div>
                </MenuItems>
              </transition>
            </Menu>
          </td>
        </tr>
        </tbody>
        <!--/ Customers Data -->
      </table>
    </div>
    <!--/ Customers Table -->

    <!-- Pagination -->
    <section
        v-if="!customers.loading && customers.data.length"
        class="flex justify-between items-center mt-5"
    >
      <span class="text-black/40 dark:text-white/70"
      >Showing from {{ customers.from }} to {{ customers.to }}</span
      >

      <nav
          v-if="customers.total > customers.limit"
          class="relative z-10 inline-flex justify-center rounded-md shadow-sm -space-x-px"
      >
        <button
            type="button"
            v-for="(link, index) in customers.links"
            :key="index"
            :disabled="!link.url"
            @click.prevent="paginate(link)"
            aria-current="page"
            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap transition-colors duration-200 ease-in-out"
            :class="[
            link.active
              ? 'z-10 bg-indigo-200 dark:bg-indigo-400 border-indigo-500 text-indigo-600'
              : 'bg-black/10 dark:bg-gray-600 border-black/10 text-black/60 dark:text-gray-400 dark:hover:text-gray-300 hover:bg-white/10',
            index === 0 ? 'rounded-l-md' : '',
            index === customers.links.length - 1 ? 'rounded-r-md' : '',
            !link.url ? 'pointer-events-none' : '',
          ]"
            v-html="link.label"
        ></button>
      </nav>
    </section>
    <!--/ Pagination -->
  </section>
</template>

<script setup>
import {ref, onMounted, computed} from "vue";
import {
  ChevronDownIcon,
  MagnifyingGlassIcon,
  EllipsisVerticalIcon,
  PencilIcon,
  TrashIcon,
} from "@heroicons/vue/24/solid";
import {Menu, MenuButton, MenuItems, MenuItem} from "@headlessui/vue";
import Spinner from "../../components/core/Spinner.vue";
import store from "../../store/index.js";
import {CUSTOMERS_PER_PAGE} from "../../utils/constants.js";
import TableHeaderCell from "../../components/core/ProductsTable/TableHeaderCell.vue";

const perPage = ref(CUSTOMERS_PER_PAGE);
const search = ref("");
const sortField = ref("created_at");
const sortDirection = ref("desc");

const getCustomers = (url = null) => {
  store.dispatch("getCustomers", {
    url,
    search: search.value,
    per_page: perPage.value,
    sort_by: sortField.value,
    sort_to: sortDirection.value,
  });
};

onMounted(() => {
  getCustomers();
});

const paginate = (link) => {
  if (link.url && !link.active) {
    getCustomers(link.url);
  }
};

const sortCustomers = (field) => {
  if (field === sortField.value) {
    sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
  } else {
    sortField.value = field;
    sortDirection.value = "asc";
  }
  getCustomers();
};

const deleteCustomer = (id) => {
  if (!confirm("Are you sure you want to delete this customer?")) return;

  store.dispatch("deleteCustomer", id).then(() => {
    getCustomers();
  });
};

const customers = computed(() => store.state.customers);
</script>