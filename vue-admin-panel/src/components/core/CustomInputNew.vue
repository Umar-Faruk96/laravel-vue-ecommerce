<template>
  <div>
    <label :for="label.replace(' ', '_')" class="sr-only">{{ label }}</label>

    <div class="mt-3 flex rounded-md shadow-sm">
      <span
          v-if="prepend"
          class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-black/30 bg-black/10 text-black/80 text-sm"
      >{{ prepend }}</span
      >

      <template v-if="type === 'textarea'">
        <textarea
            :name
            :required
            v-model="textarea"
            :class="inputClasses"
            :placeholder="label"
        ></textarea>
      </template>

      <template v-else-if="type === 'file'">
        <input
            :type
            :name
            :required
            :class="inputClasses"
            @change="($event) => emit('change', $event.target.files[0])"
        />
      </template>

      <template v-else-if="type === 'number'">
        <input
            :type
            :name
            :required
            v-model="number"
            :class="inputClasses"
        />
      </template>

      <template v-else-if="type === 'email'">
        <input
            :type
            :name
            :required
            v-model="email"
            :class="inputClasses"
            :placeholder="label"
        />
      </template>

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

      <template v-else>
        <input
            :type="type"
            :name="name"
            :required="required"
            v-model="title"
            :class="inputClasses"
            :placeholder="label"
        />
      </template>

      <span
          v-if="append"
          class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-white/70 bg-white/95 text-white/50 text-sm"
      >{{ append }}</span
      >
    </div>

    <small v-if="errors && errors[0]" class="text-red-600">{{ errors[0] }}</small>
  </div>
</template>

<script setup>
import {computed} from "vue";

const props = defineProps({
  label: {
    type: String,
    required: true,
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
    required: false
  }
});

const title = defineModel("title");
const textarea = defineModel("textarea");
const number = defineModel("number");
const email = defineModel("email");
const password = defineModel("password");
const confirmPassword = defineModel("confirm-password");

const inputClasses = computed(() => {
  const classes = [
    `block w-full px-3 py-2 border border-black/30 placeholder-black/80 bg-white/60 text-black/90 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-colors`,
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
    classes.push("border-red-600 focus:border-red-600")
  }

  return classes.join(" ");
});

const emit = defineEmits(["change"]);
</script>