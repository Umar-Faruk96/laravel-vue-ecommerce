<template>
  <aside class="flex min-h-full justify-center transition-all">
    <nav class="w-44 xl:w-60 bg-blue-900 text-white/75 px-2 py-4">
      <router-link
        :to="{ name: 'app.dashboard' }"
        class="flex items-center gap-2 py-2 px-4 rounded transition-colors hover:bg-black/30"
      >
        <span>
          <HomeIcon class="size-6" />
        </span>
        <span class="text-white/60">Dashboard</span>
      </router-link>

      <router-link
        :to="{ name: 'app.products' }"
        class="flex items-center gap-2 py-2 px-4 rounded transition-colors hover:bg-black/30"
      >
        <span>
          <ShoppingBagIcon class="size-6" />
        </span>

        <span class="text-white/60">Products</span>
      </router-link>

      <router-link
        :to="{ name: 'app.orders' }"
        class="flex items-center gap-2 py-2 px-4 rounded transition-colors hover:bg-black/30"
      >
        <span>
          <ShoppingCartIcon class="size-6" />
        </span>

        <span class="text-white/60">Orders</span>
      </router-link>

      <router-link
        :to="{ name: 'app.users' }"
        class="flex items-center gap-2 py-2 px-4 rounded transition-colors hover:bg-black/30"
      >
        <span>
          <UsersIcon class="size-6" />
        </span>
        <span class="text-white/60">Users</span>
      </router-link>

      <router-link
        :to="{ name: 'app.reports' }"
        class="flex items-center gap-2 py-2 px-4 rounded transition-colors hover:bg-black/30"
      >
        <span>
          <ChartBarIcon class="size-6" />
        </span>
        <span class="text-white/60">Reports</span>
      </router-link>
    </nav>
  </aside>
</template>

<script setup>
import {
  HomeIcon,
  UsersIcon,
  ShoppingBagIcon,
  ShoppingCartIcon,
  ChartBarIcon,
} from "@heroicons/vue/24/outline";
</script>
