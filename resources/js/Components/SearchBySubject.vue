<script setup>
import { router } from '@inertiajs/vue3';

defineProps({
  categories: {
    type: Array,
    default: () => [],
  },
  currentPage: {
    type: Number,
    default: 1,
  },
});

function searchBySubject(subject) {
  router.visit(route('search.index'), {
    method: 'get',
    data: {
      q: subject,
      type: 'subject',
    },
  });
}
</script>

<template>
  <div v-if="categories && categories.length > 0" class="text-gray-600 mb-2">
    <div class="flex items-center justify-between">
      <span class="text-gray-400 text-sm">Categories:</span>
      <span v-if="currentPage > 1" class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
        Page {{ currentPage }}
      </span>
    </div>
    <div class="flex flex-wrap gap-1 mt-1">
      <template v-for="(category, index) in categories.slice(0, 3)" :key="index">
        <button
          class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-secondary-100 text-secondary-800 hover:bg-secondary-200 transition-colors cursor-pointer"
          @click="searchBySubject(category)"
        >
          {{ category }}
        </button>
      </template>
      <span v-if="categories.length > 3" class="text-xs text-gray-500 self-center">
        +{{ categories.length - 3 }} more
      </span>
    </div>
  </div>
</template>
