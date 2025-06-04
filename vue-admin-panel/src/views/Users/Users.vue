<template>
  <section class="flex items-center justify-between mb-3">
    <h1 class="text-black/60 dark:text-white/70 sm:text-3xl font-semibold">Users</h1>

    <button
        @click="openUserFormModal"
        type="button"
        class="flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white/80 bg-indigo-600 dark:bg-indigo-800 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
    >
      Add New User
    </button>
  </section>

  <CreateUserForm
      v-model:form-modal="userForm"
      :user="userData"
      @close="clearUserForm"
  />

  <UsersTable @edit-user="editUserForm" />
</template>

<script setup>
import {ref} from "vue";
import UsersTable from "./UsersTable.vue";
import CreateUserForm from "./CreateUserForm.vue";
import store from "../../store/index.js";

const userForm = ref(false);

const openUserFormModal = () => {
  userForm.value = true;
};

const DEFAULT_USER = {
  id: "",
  name: "",
  email: "",
};

const userData = ref({...DEFAULT_USER});

const editUserForm = (user) => {
  // store.dispatch("getSelectedUser", user.id).then(({ data }) => {
  // userData.value = data;
  userData.value = user;
  openUserFormModal();
  // });
};

const clearUserForm = () => {
  userData.value = {...DEFAULT_USER};
};
</script>