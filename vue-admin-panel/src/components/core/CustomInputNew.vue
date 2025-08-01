<template>
  <div>
    <label v-if="label" :for="label.replace(' ', '_').toLowerCase()" class="sr-only">{{
      label
    }}</label>

    <div class="flex rounded-md shadow-sm items-stretch">
      <span
        v-if="prepend"
        :class="[
          prepend === '৳' ? 'text-2xl' : 'text-sm',
          'inline-flex items-center px-3 rounded-l-md border border-r-0 border-black/30 dark:border-black/15 bg-black/10 text-black/80 dark:text-gray-200',
        ]"
        >{{ prepend }}</span
      >

      <!-- textarea -->
      <template v-if="type === 'textarea'">
        <textarea
          :name
          :required
          v-model="textarea"
          :class="inputClasses"
          :placeholder="label"
        ></textarea>
      </template>

      <!-- file input -->
      <template v-else-if="type === 'file'">
        <input
          :type
          :name
          :required
          :class="inputClasses"
          @change="($event) => emit('change', $event.target.files[0])"
        />
      </template>

      <!-- number input -->
      <template v-else-if="type === 'number'">
        <input
          :type
          :name
          :id="idFor"
          :required
          v-model="number"
          :class="inputClasses"
          :placeholder="label"
        />
      </template>

      <!-- email input -->
      <template v-else-if="type === 'email'">
        <input
          :type
          :name
          :required
          :id="idFor"
          v-model="email"
          :class="inputClasses"
          :placeholder="label"
        />
      </template>

      <!-- password input -->
      <template v-else-if="type === 'password'">
        <input
          :type
          :name
          :required
          v-model="password"
          :class="inputClasses"
          :placeholder="label"
        />
      </template>

      <template v-else-if="name === 'password_confirmation'">
        <input
          type="password"
          :name
          :required
          v-model="confirmPassword"
          :class="inputClasses"
          :placeholder="label"
        />
      </template>

      <!-- checkbox input -->
      <template v-else-if="type === 'checkbox'">
        <!--<pre class="dark:text-gray-600">{{ status }}</pre>-->
        <input
          :id="`${name}-${id}`"
          :type
          :name
          v-model="status"
          class="w-5 h-5 appearance-none bg-gray-300 hover:bg-gray-500 dark:bg-gray-700 dark:hover:bg-gray-500 rounded checked:bg-indigo-700 checked:hover:bg-indigo-700 checked:focus:ring-2 checked:ring-indigo-700 checked:ring-offset-2 relative checked:after:absolute checked:after:top-1/2 checked:after:left-1/2 checked:after:content-['✔'] checked:after:-translate-x-1/2 checked:after:-translate-y-1/2 transition-all"
          :checked="status"
        />

        <label
          :for="`${name}-${id}`"
          class="ml-2 block font-medium capitalize cursor-pointer"
          :class="{
            'text-gray-400': !status,
            'text-indigo-700 dark:text-indigo-200': status,
          }"
          >{{ name }}</label
        >
      </template>

      <!-- text input -->
      <template v-else-if="name === 'first_name'">
        <input
          :type
          :name
          :required
          :id="idFor"
          v-model="firstName"
          :class="inputClasses"
          :placeholder="label"
        />
      </template>

      <template v-else-if="name === 'last_name'">
        <input
          :type
          :name
          :required
          :id="idFor"
          v-model="lastName"
          :class="inputClasses"
          :placeholder="label"
        />
      </template>

      <template v-else>
        <input
          :type
          :name
          :id="idFor"
          :required
          v-model="title"
          :class="inputClasses"
          :placeholder="label"
        />
      </template>

      <span
        v-if="append"
        class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-white/70 dark:border-gray-600 bg-white/95 dark:bg-gray-700 text-white/50 dark:text-gray-600 text-sm"
        >{{ append }}</span
      >
    </div>

    <small v-if="errors && errors[0]" class="text-red-600">{{ errors[0] }}</small>
  </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  label: {
    type: String,
    default: "",
  },
  type: {
    type: String,
    default: "text",
  },
  required: {
    type: Boolean,
    default: false,
  },
  prepend: {
    type: String,
    default: "",
  },
  append: {
    type: String,
    default: "",
  },
  name: {
    type: String,
    default: "",
  },
  errors: {
    type: Array,
    required: false,
  },
  id: {
    type: Number,
    default: 0,
  },
  idFor: {
    type: String,
    default: "",
  },
});

const title = defineModel("title");
const firstName = defineModel("first-name");
const lastName = defineModel("last-name");
const textarea = defineModel("textarea");
const number = defineModel("number");
const email = defineModel("email");
const password = defineModel("password");
const confirmPassword = defineModel("confirm-password");
const status = defineModel("status");

const inputClasses = computed(() => {
  const classes = [
    `block w-full px-3 py-2 border border-black/30 dark:border-black/15 placeholder-black/80 dark:placeholder-gray-300 bg-white/60 dark:bg-gray-600 text-black/90 dark:text-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors`,
  ];

  if (props.append && !props.prepend) {
    classes.push("rounded-l-md");
  } else if (props.prepend && !props.append) {
    classes.push("rounded-r-md");
  } else if (props.prepend && props.append) {
    classes.push("rounded-none");
  } else {
    classes.push("rounded-md");
  }

  if (props.errors && props.errors[0]) {
    classes.push("border-red-600 focus:border-red-600");
  }

  return classes.join(" ");
});

const emit = defineEmits(["change"]);
</script>
