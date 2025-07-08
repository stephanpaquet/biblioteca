<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import Button from './Button.vue';

const props = defineProps({
  initialQuery: {
    type: String,
    default: '',
  },
  initialType: {
    type: String,
    default: 'general',
  },
});

const searchQuery = ref(props.initialQuery || '');
const searchType = ref(props.initialType || 'general');
const isLoading = ref(false);
const showAdvanced = ref(false);

const filters = ref({
  language: '',
  publishedAfter: '',
  publishedBefore: '',
  printType: '',
  orderBy: 'relevance',
  maxResults: '20',
});

const searchTypes = [
  { value: 'general', label: 'General', placeholder: 'Search for books, authors, or topics...' },
  { value: 'isbn', label: 'ISBN', placeholder: 'Enter ISBN (e.g., 9780123456789)' },
  { value: 'title', label: 'Title', placeholder: 'Enter book title...' },
  { value: 'author', label: 'Author', placeholder: 'Enter author name...' },
  { value: 'publisher', label: 'Publisher', placeholder: 'Enter publisher name...' },
  { value: 'subject', label: 'Subject', placeholder: 'Enter subject or category...' },
  { value: 'description', label: 'Description', placeholder: 'Search in book descriptions...' },
];

const currentPlaceholder = computed(() => {
  const type = searchTypes.find((t) => t.value === searchType.value);
  return type ? type.placeholder : 'Search for books...';
});

function handleSearch() {
  if (!searchQuery.value || !searchQuery.value.trim()) return;

  isLoading.value = true;

  // Build search parameters
  const searchParams = {
    q: searchQuery.value.trim(),
    type: searchType.value,
    ...filters.value,
  };

  // Remove empty filters
  Object.keys(searchParams).forEach((key) => {
    if (searchParams[key] === '' || searchParams[key] === null || searchParams[key] === undefined) {
      delete searchParams[key];
    }
  });

  // Navigate to search results
  router.get('/search', searchParams, {
    preserveState: true,
    preserveScroll: true,
    onFinish: () => {
      isLoading.value = false;
    },
  });
}

function clearFilters() {
  filters.value = {
    language: '',
    publishedAfter: '',
    publishedBefore: '',
    printType: '',
    orderBy: 'relevance',
    maxResults: '20',
  };
}
</script>

<template>
  <form class="w-full max-w-4xl mx-auto" @submit.prevent="handleSearch">
    <!-- Search Type Selector -->
    <div class="mb-4">
      <div class="flex flex-wrap gap-2 justify-center">
        <Button
          v-for="type in searchTypes"
          :key="type.value"
          type="button"
          :variant="searchType === type.value ? 'primary' : 'neutral'"
          size="sm"
          @click="searchType = type.value"
        >
          {{ type.label }}
        </Button>
      </div>
    </div>

    <!-- Main Search Input -->
    <div class="relative mb-4">
      <input
        v-model="searchQuery"
        type="text"
        :placeholder="currentPlaceholder"
        class="w-full px-4 py-3 pl-12 pr-24 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        :class="{ 'bg-gray-50': isLoading }"
        :disabled="isLoading"
      />

      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
          />
        </svg>
      </div>

      <Button
        type="submit"
        :disabled="isLoading || !searchQuery || !searchQuery.trim()"
        :loading="isLoading"
        variant="primary"
        class="absolute inset-y-0 right-0 rounded-l-none"
      >
        {{ isLoading ? 'Searching...' : 'Search' }}
      </Button>
    </div>

    <!-- Advanced Search Options (collapsible) -->
    <div class="text-center">
      <Button type="button" variant="link" size="sm" @click="showAdvanced = !showAdvanced">
        {{ showAdvanced ? 'Hide Advanced Options' : 'Show Advanced Options' }}
      </Button>
    </div>

    <!-- Advanced Search Fields -->
    <div v-if="showAdvanced" class="mt-4 p-4 bg-gray-50 rounded-lg">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Language</label>
          <select
            v-model="filters.language"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option value="">Any Language</option>
            <option value="en">English</option>
            <option value="fr">French</option>
            <option value="es">Spanish</option>
            <option value="de">German</option>
            <option value="it">Italian</option>
            <option value="pt">Portuguese</option>
            <option value="ru">Russian</option>
            <option value="ja">Japanese</option>
            <option value="zh">Chinese</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Published After</label>
          <input
            v-model="filters.publishedAfter"
            type="number"
            min="1000"
            max="2024"
            placeholder="e.g., 2000"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Published Before</label>
          <input
            v-model="filters.publishedBefore"
            type="number"
            min="1000"
            max="2024"
            placeholder="e.g., 2023"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Print Type</label>
          <select
            v-model="filters.printType"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option value="">All</option>
            <option value="books">Books</option>
            <option value="magazines">Magazines</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Order By</label>
          <select
            v-model="filters.orderBy"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option value="relevance">Relevance</option>
            <option value="newest">Newest</option>
            <option value="oldest">Oldest</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Max Results</label>
          <select
            v-model="filters.maxResults"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="40">40</option>
          </select>
        </div>
      </div>

      <div class="mt-4 flex justify-center">
        <Button type="button" variant="neutral" size="sm" @click="clearFilters">
          Clear Filters
        </Button>
      </div>
    </div>
  </form>
</template>
