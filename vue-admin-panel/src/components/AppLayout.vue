<template>
  <section
    v-if="loggedInUser.id"
    class="block sm:flex min-h-screen bg-white/75 dark:bg-gray-700"
  >
    <!-- Sidebar -->
    <Sidebar :class="{ 'hidden xl:-ml-60': !showSidebar }" />
    <!--/ Sidebar -->

    <!-- Main Content -->
    <section class="flex-1 overflow-hidden">
      <Navbar @toggleSidebar="toggleSidebar" />
      <main class="p-3 md:p-6">
        <div class="p-4 rounded bg-white/70 dark:bg-gray-600">
          <router-view></router-view>
        </div>
      </main>
    </section>
    <!--/ Main Content -->
  </section>
  <!-- Page Loader -->
  <Spinner v-else class="min-h-screen" />
  <!--/ Page Loader -->

  <!-- Toast -->
  <Toast />
  <!--/ Toast -->
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from "vue";

import Sidebar from "./Sidebar.vue";
import Navbar from "./Navbar.vue";
import Spinner from "./core/Spinner.vue";
import store from "../store";
import Toast from "./core/Toast.vue";

const showSidebar = ref(true);

const loggedInUser = computed(() => store.state.user.data);

const toggleSidebar = () => {
  showSidebar.value = !showSidebar.value;
};

const makeSidebarResponsive = () => {
  showSidebar.value = window.outerWidth > 768;
};

onMounted(() => {
  store.dispatch("getUser");
  makeSidebarResponsive();
  window.addEventListener("resize", makeSidebarResponsive);
});
onUnmounted(() => {
  window.removeEventListener("resize", makeSidebarResponsive);
});
</script>
