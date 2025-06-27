<template>
  <form @submit.prevent="handleSearch" class="w-full max-w-2xl mx-auto">
    <div class="relative">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search for books, authors, or topics..."
        class="w-full px-4 py-3 pl-12 pr-24 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        :class="{ 'bg-gray-50': isLoading }"
        :disabled="isLoading"
      />
      
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
      </div>
      
      <button
        type="submit"
        :disabled="isLoading || !searchQuery || !searchQuery.trim()"
        class="absolute inset-y-0 right-0 px-6 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white rounded-r-lg transition-colors"
      >
        {{ isLoading ? 'Searching...' : 'Search' }}
      </button>
    </div>
  </form>
</template>

<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
  initialQuery: {
    type: String,
    default: ''
  }
});

const searchQuery = ref(props.initialQuery || '');
const isLoading = ref(false);

function handleSearch() {
  if (!searchQuery.value || !searchQuery.value.trim()) return;
  
  isLoading.value = true;
  
  Inertia.get('/', { q: searchQuery.value }, {
    preserveState: true,
    preserveScroll: true,
    onFinish: () => {
      isLoading.value = false;
    }
  });
}
</script>
