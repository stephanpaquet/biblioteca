<script setup>
import { router } from '@inertiajs/vue3';
import AddToLibraryButton from './AddToLibraryButton.vue';
import BookPlaceholder from './BookPlaceholder.vue';

defineProps({
  authors: {
    type: Array,
    default: () => [],
  },
});

function searchByAuthor(author) {
  router.visit(route('search.index'), {
    method: 'get',
    data: {
      q: author,
      type: 'author',
    },
  });
}
</script>

<template>
  <div class="text-gray-600 mb-2">
    <template v-for="(author, index) in authors" :key="index">
      <button
        class="text-primary-600 hover:text-primary-800 hover:underline transition-colors cursor-pointer"
        @click="searchByAuthor(author)"
      >
        >
        {{ author }}
      </button>
      <span v-if="index < authors.length - 1" class="text-gray-600">, </span>
    </template>
  </div>
</template>
