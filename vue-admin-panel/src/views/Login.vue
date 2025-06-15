<script setup>
import { ref } from "vue";
import { XMarkIcon } from "@heroicons/vue/24/outline";
import store from "../store";
import router from "../router";
import GuestLayout from "../components/GuestLayout.vue";

let loading = ref(false);
let errorMsg = ref("");

const user = {
  email: "",
  password: "",
  remember: false,
};

const login = () => {
  loading.value = true;
  store
    .dispatch("login", user)
    .then(() => router.push({ name: "app.dashboard" }))
    .catch(({ data }) => (errorMsg.value = data.message))
    // .catch(error => (console.log(error)))
    .finally(() => (loading.value = false));
};
</script>

<template>
  <GuestLayout title="Sign in to your account">
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
      <div class="bg-gray-50 px-6 py-12 shadow rounded-lg sm:px-12 dark:bg-gray-700">
        <form @submit.prevent="login" method="POST" class="space-y-6 text-gray-700 dark:text-gray-200">
          <div v-if="errorMsg" class="flex items-center justify-between py-3 px-5 bg-red-500 text-white/90 rounded">
            <p>{{ errorMsg }}</p>
            <span @click="errorMsg = ''"
              class="w-8 h-8 flex items-center justify-center rounded-full transition-colors cursor-pointer hover:bg-black/20">
              <XMarkIcon class="size-5" />
            </span>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium">Email address</label>
            <div class="mt-2">
              <input v-model="user.email" id="email" name="email" type="email" required="" autocomplete="email"
                placeholder="Email address"
                class="block w-full rounded-md bg-gray-50 dark:bg-gray-600 px-3 py-1.5 text-base outline outline-1 outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500 sm:text-sm" />
            </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium">Password</label>
            <div class="mt-2">
              <input v-model="user.password" id="password" name="password" type="password" required=""
                autocomplete="current-password" placeholder="Password"
                class="block w-full rounded-md bg-gray-50 dark:bg-gray-600 px-3 py-1.5 text-base outline outline-1 outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500 sm:text-sm" />
            </div>
          </div>

          <div class="flex items-center justify-between">
            <div class="flex gap-3">
              <div class="flex h-6 shrink-0 items-center">
                <div class="checkbox grid size-4 grid-cols-1">
                  <input v-model="user.remember" id="remember-me" name="remember-me" type="checkbox"
                    class="col-start-1 row-start-1 appearance-none rounded-sm border bg-gray-50 dark:bg-gray-600 checked:border-indigo-500 checked:bg-indigo-500 indeterminate:text-indigo-500 indeterminate:bg-indigo-500 focus-visible:outline focus-visible:outline-indigo-500 focus-visible:outline-offset-2 disabled:text-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                  <svg fill="none" viewBox="0 0 14 14"
                    class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center text-gray-50 stroke-gray-50">
                    <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="opacity-0 checkbox-checked"></path>
                    <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="opacity-0 checkbox-indeterminate"></path>
                  </svg>
                </div>
              </div>
              <label for="remember-me" class="block text-sm">Remember me</label>
            </div>

            <div class="text-sm">
              <router-link :to="{ name: 'RequestPassword', params: { token: 1 } }"
                class="font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">Forgot password?</router-link>
            </div>
          </div>

          <div>
            <button type="submit" :disabled="loading" :class="{ 'cursor-not-allowed hover:bg-indigo-400': loading }"
              class="flex w-full justify-center rounded-md bg-indigo-700 px-3 py-1.5 text-sm font-semibold text-white/80 shadow-sm hover:bg-indigo-600 focus-visible:outline focus-visible:outline-indigo-500 focus-visible:outline-offset-2">
              <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white/90"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
              </svg>
              Sign in
            </button>
          </div>
        </form>

        <div>
          <div class="relative mt-10">
            <div aria-hidden="true" class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-300 dark:border-gray-400"></div>
            </div>
            <div class="relative flex justify-center text-sm font-medium">
              <span class="bg-gray-50 dark:bg-gray-600 px-6 text-gray-700 dark:text-gray-200">Or continue with</span>
            </div>
          </div>

          <div class="mt-6 grid grid-cols-2 gap-4">
            <a href="#"
              class="flex w-full items-center justify-center gap-3 rounded-md bg-gray-50 dark:bg-gray-600 px-3 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 ring-1 shadow-sm ring-gray-300 dark:ring-gray-400 ring-inset hover:bg-gray-50 dark:hover:bg-gray-500 focus-visible:ring-transparent"><svg
                viewBox="0 0 24 24" aria-hidden="true" class="size-5">
                <path
                  d="M12.0003 4.75C13.7703 4.75 15.3553 5.36002 16.6053 6.54998L20.0303 3.125C17.9502 1.19 15.2353 0 12.0003 0C7.31028 0 3.25527 2.69 1.28027 6.60998L5.27028 9.70498C6.21525 6.86002 8.87028 4.75 12.0003 4.75Z"
                  fill="#EA4335"></path>
                <path
                  d="M23.49 12.275C23.49 11.49 23.415 10.73 23.3 10H12V14.51H18.47C18.18 15.99 17.34 17.25 16.08 18.1L19.945 21.1C22.2 19.01 23.49 15.92 23.49 12.275Z"
                  fill="#4285F4"></path>
                <path
                  d="M5.26498 14.2949C5.02498 13.5699 4.88501 12.7999 4.88501 11.9999C4.88501 11.1999 5.01998 10.4299 5.26498 9.7049L1.275 6.60986C0.46 8.22986 0 10.0599 0 11.9999C0 13.9399 0.46 15.7699 1.28 17.3899L5.26498 14.2949Z"
                  fill="#FBBC05"></path>
                <path
                  d="M12.0004 24.0001C15.2404 24.0001 17.9654 22.935 19.9454 21.095L16.0804 18.095C15.0054 18.82 13.6204 19.245 12.0004 19.245C8.8704 19.245 6.21537 17.135 5.2654 14.29L1.27539 17.385C3.25539 21.31 7.3104 24.0001 12.0004 24.0001Z"
                  fill="#34A853"></path>
              </svg>
              <span class="text-sm font-semibold">Google</span>
            </a>

            <a href="#"
              class="flex w-full items-center justify-center gap-3 rounded-md bg-gray-50 dark:bg-gray-600 px-3 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 ring-1 shadow-sm ring-gray-300 dark:ring-gray-400 ring-inset hover:bg-gray-50 dark:hover:bg-gray-500 focus-visible:ring-transparent"><svg
                fill="currentColor" viewBox="0 0 20 20" aria-hidden="true" class="size-5 fill-[#24292F]">
                <path
                  d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z"
                  clip-rule="evenodd" fill-rule="evenodd"></path>
              </svg>
              <span class="text-sm font-semibold">GitHub</span>
            </a>
          </div>
        </div>
      </div>

      <!-- <p class="mt-10 text-center text-sm text-gray-500">
          Not a member?
          <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500"
            >Start a 14 day free trial</a
          >
        </p> -->
    </div>

  </GuestLayout>
</template>