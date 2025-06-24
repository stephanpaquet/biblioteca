<template>
  <div v-if="totalPages > 1" class="flex justify-center my-4 gap-2">
    <button
      class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 disabled:opacity-50"
      :disabled="modelValue === 1 || loading"
      @click="$emit('update:modelValue', modelValue - 1)"
    >Previous</button>
    <span class="px-2 py-1">Page {{ modelValue }} of {{ totalPages }}</span>
    <button
      class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 disabled:opacity-50"
      :disabled="modelValue === totalPages || loading"
      @click="$emit('update:modelValue', modelValue + 1)"
    >Next</button>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  modelValue: { type: Number, required: true },
  total: { type: Number, required: true },
  perPage: { type: Number, required: true },
  loading: { type: Boolean, default: false },
});
const totalPages = computed(() => Math.ceil(props.total / props.perPage));
</script>
