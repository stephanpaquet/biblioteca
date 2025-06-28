<template>
  <div class="w-full h-48 bg-gradient-to-br from-blue-100 to-blue-200 rounded flex flex-col items-center justify-center text-blue-600">
    <svg class="w-16 h-16 mb-2 opacity-60" fill="currentColor" viewBox="0 0 24 24">
      <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
    </svg>
    <span class="text-xs font-medium text-center px-2">{{ displayTitle }}</span>
    <span v-if="author" class="text-xs opacity-75 mt-1">{{ author }}</span>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  title: {
    type: String,
    default: 'No Image Available'
  },
  author: {
    type: String,
    default: ''
  }
});

const displayTitle = computed(() => {
  if (props.title.length > 30) {
    return props.title.substring(0, 30) + '...';
  }
  return props.title;
});
</script>
