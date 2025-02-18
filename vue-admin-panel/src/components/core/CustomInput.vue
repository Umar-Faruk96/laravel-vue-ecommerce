<script setup>
import { computed } from "vue";

const props = defineProps({
  modelValue: [String, Number, File],
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
});

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

  return classes.join(" ");
});

const emit = defineEmits(["update:modelValue", "change"]);
</script>

<template>
  <section>
    <label :for="label.replace(' ', '_')" class="sr-only">{{ label }}</label>
    <div class="mt-1 flex rounded-md shadow-sm">
      <span
        v-if="prepend"
        class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-black/30 bg-black/10 text-black/80 text-sm"
        >{{ prepend }}</span
      >

      <template v-if="type === 'textarea'">
        <textarea
          :name="name"
          :required="required"
          :value="props.modelValue"
          :class="inputClasses"
          @input="emit('update:modelValue', $event.target.value)"
          :placeholder="label"
        ></textarea>
      </template>

      <template v-else-if="type === 'file'">
        <input
          :type="type"
          :name="name"
          :required="required"
          :class="inputClasses"
          @input="emit('change', $event.target.files[0])"
        />
      </template>

      <template v-else-if="type === 'number'">
        <input
          :type="type"
          :name="name"
          :required="required"
          :value="props.modelValue"
          :class="inputClasses"
          @input="emit('update:modelValue', $event.target.value)"
          :placeholder="label"
          step="0.01"
        />
      </template>

      <template v-else>
        <input
          :type="type"
          :name="name"
          :required="required"
          :value="props.modelValue"
          :class="inputClasses"
          @input="emit('update:modelValue', $event.target.value)"
          :placeholder="label"
        />
      </template>

      <span
        v-if="append"
        class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-white/70 bg-white/95 text-white/50 text-sm"
        >{{ append }}</span
      >
    </div>
  </section>
</template>
