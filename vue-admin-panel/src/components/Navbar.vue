<script setup>
import { defineEmits, ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { Bars3Icon, ChevronDownIcon, UserIcon, ArrowRightStartOnRectangleIcon, ArrowPathIcon } from '@heroicons/vue/24/outline';
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue';

import store from '../store';
// import router from '../router';

const router = useRouter();

const loggedInUser = computed(() => store.state.user.data);

const emit = defineEmits(['toggleSidebar']);

let loading = ref(false);
let errorMsg = ref("");

const logout = () => {
    loading.value = true;
    store
        .dispatch('logout')
        .then(() => router.push({ name: 'Login' }))
        .catch(error => errorMsg.value = error.response.data.message)
        .finally(() => loading.value = false);
};
</script>

<template>
    <header class="h-16 shadow bg-white/70 dark:bg-gray-600 flex justify-between items-center px-4">
        <button @click="emit('toggleSidebar')" type="button"
            class="focus-visible:outline-none active:bg-black/15 rounded-sm p-0.5 transition-all">
            <span class="sr-only">Dropdown</span>
            <Bars3Icon class="size-6 text-black/60 dark:text-white/70 active:dark:text-white/50" />
        </button>
        <section class="w-56 text-right">
            <Menu as="div" class="relative inline-block text-left">
                <div>
                    <MenuButton
                        class="inline-flex w-full justify-center items-center px-4 py-2 gap-3 focus:outline-none">
                        <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="avatar"
                            class="size-10 rounded-full">
                        <span class="text-black/70 hover:text-black/50 dark:text-white/70 dark:hover:text-white/50 font-medium transition-colors">{{
                            loggedInUser.name.length > 10 ? loggedInUser.name.substring(0, 10) + '...' :
                            loggedInUser.name }}</span>
                        <ChevronDownIcon class="size-6 text-black/50 hover:text-black/40 dark:text-white/70 dark:hover:text-white/50 transition-colors"
                            aria-hidden="true" />
                    </MenuButton>
                </div>

                <transition enter-active-class="transition duration-100 ease-out"
                    enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100"
                    leave-active-class="transition duration-75 ease-in"
                    leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
                    <MenuItems
                        class="absolute right-0 mt-2 w-52 origin-top-right rounded-md bg-white/90 shadow-lg ring-1 ring-black/5 focus:outline-none">
                        <nav class="p-1">
                            <MenuItem v-slot="{ active }">
                            <button type="button" :class="[
                                active ? 'bg-indigo-500 text-white/70' : 'text-gray-900',
                                'group flex w-full items-center rounded-md p-2 text-sm',
                            ]">
                                <UserIcon :active="active" class="mr-2 size-6 text-indigo-400" aria-hidden="true" />
                                Profile
                            </button>
                            </MenuItem>
                            <MenuItem v-slot="{ active }">
                            <button @click="logout" type="button" :class="[
                                active ? 'bg-indigo-500 text-white/70' : 'text-gray-900',
                                loading ? 'cursor-not-allowed hover:bg-indigo-400' : '',
                                'group flex w-full items-center rounded-md p-2 text-sm',
                            ]">
                                <ArrowRightStartOnRectangleIcon :active="active" class="mr-2 h-5 w-5 text-indigo-400"
                                    aria-hidden="true" />
                                <span>{{ loading ? 'Logging out...' : 'Logout' }}</span>
                                <ArrowPathIcon v-if="loading" class="animate-spin ml-3 h-5 w-5 text-white/90" />
                            </button>
                            </MenuItem>
                        </nav>
                    </MenuItems>
                </transition>
            </Menu>
        </section>
    </header>
</template>