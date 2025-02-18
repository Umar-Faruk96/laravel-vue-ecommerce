<script setup>
import { BarsArrowDownIcon, BarsArrowUpIcon } from '@heroicons/vue/24/solid';

const { field, sortField, sortDirection } = defineProps({
    field: String,
    sortField: String,
    sortDirection: String,
});

const emit = defineEmits(['sort']);
</script>
<template>
    <th class="border-b-2 p-2 text-left tracking-wider cursor-pointer bg-white/70" @click="emit('sort', field)"
        :class="field === sortField ? 'bg-indigo-100' : ''">
        <span class="flex items-center justify-between">
            <slot></slot>
            <span v-if="sortField === field">
                <BarsArrowUpIcon class="h-4 w-4" v-if="sortDirection === 'asc'" />
                <BarsArrowDownIcon class="h-4 w-4" v-else />
            </span>
        </span>
    </th>
</template>