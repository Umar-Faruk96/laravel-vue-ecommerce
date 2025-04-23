<template>
  <!-- Toast -->
  <div
    v-show="toast.visible"
    class="fixed left-1/2 top-16 -ml-[200px] w-[400px] bg-emerald-500 px-4 py-2 pb-4 text-white"
  >
    <div class="font-semibold">{{ toast.message }}</div>

    <button
      @click="close"
      class="absolute right-2 top-2 flex h-[30px] w-[30px] items-center justify-center rounded-full transition-colors hover:bg-black/10"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="h-6 w-6"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="2"
      >
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>

    <!-- Progress -->
    <div>
      <div
        class="absolute bottom-0 left-0 right-0 h-[6px] bg-black/10"
        :style="{ width: `${percent}%` }"
      ></div>
    </div>
  </div>
  <!--/ Toast -->
</template>

<script setup>
import { computed, watch } from "vue";
import store from "../../store";

const toast = computed(() => store.state.toast);

const percent = computed(() => {
  if (toast.value.interval) {
    return toast.value.percent;
  }
});

watch(
  () => toast.value.visible,
  (newValue) => {
    if (newValue) {
      if (toast.value.interval) {
        clearInterval(toast.value.interval);
        toast.value.interval = null;
      }

      if (toast.value.timeout) {
        clearTimeout(toast.value.timeout);
        toast.value.timeout = null;
      }

      toast.value.timeout = setTimeout(() => {
        close();
      }, toast.value.delay);

      toast.value.interval = setInterval(() => {
        if (toast.value.percent >= 100) {
          clearInterval(toast.value.interval);
          toast.value.interval = null;
        } else {
          toast.value.percent += 1;
        }
      }, toast.value.delay / 100);
    }
  }
);

const close = () => {
  store.commit("closeToast");
};
</script>
