<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { useTranslationsStore } from '../stores/translations';
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

const translationsStore = useTranslationsStore();
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

// Translation helper function
function t(key) {
  return translationsStore.t(key);
}

const searchTypes = computed(() => [
  {
    value: 'general',
    label: t('searchform.general'),
    placeholder: t('searchform.placeholder_general'),
  },
  {
    value: 'isbn',
    label: t('searchform.isbn'),
    placeholder: t('searchform.placeholder_isbn'),
  },
  {
    value: 'title',
    label: t('searchform.title'),
    placeholder: t('searchform.placeholder_title'),
  },
  {
    value: 'author',
    label: t('searchform.author'),
    placeholder: t('searchform.placeholder_author'),
  },
  {
    value: 'publisher',
    label: t('searchform.publisher'),
    placeholder: t('searchform.placeholder_publisher'),
  },
  {
    value: 'subject',
    label: t('searchform.subject'),
    placeholder: t('searchform.placeholder_subject'),
  },
  {
    value: 'description',
    label: t('searchform.description'),
    placeholder: t('searchform.placeholder_description'),
  },
]);

const languageOptions = computed(() => [
  { value: '', label: t('searchform.any_language') },
  { value: 'en', label: t('searchform.english') },
  { value: 'fr', label: t('searchform.french') },
  { value: 'es', label: t('searchform.spanish') },
  { value: 'de', label: t('searchform.german') },
  { value: 'it', label: t('searchform.italian') },
  { value: 'pt', label: t('searchform.portuguese') },
  { value: 'ru', label: t('searchform.russian') },
  { value: 'ja', label: t('searchform.japanese') },
  { value: 'zh', label: t('searchform.chinese') },
]);

const printTypeOptions = computed(() => [
  { value: '', label: t('searchform.all_print_types') },
  { value: 'books', label: t('searchform.books') },
  { value: 'magazines', label: t('searchform.magazines') },
]);

const orderByOptions = computed(() => [
  { value: 'relevance', label: t('searchform.relevance') },
  { value: 'newest', label: t('searchform.newest') },
  { value: 'oldest', label: t('searchform.oldest') },
]);

const maxResultsOptions = [
  { value: '10', label: '10' },
  { value: '20', label: '20' },
  { value: '40', label: '40' },
];

const currentPlaceholder = computed(() => {
  const type = searchTypes.value.find((t) => t.value === searchType.value);
  return type ? type.placeholder : t('searchform.placeholder_default');
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
        {{ isLoading ? t('searchform.searching') : t('searchform.search') }}
      </Button>
    </div>

    <!-- Advanced Search Options (collapsible) -->
    <div class="text-center">
      <Button type="button" variant="link" size="sm" @click="showAdvanced = !showAdvanced">
        {{ showAdvanced ? t('searchform.hide_advanced') : t('searchform.show_advanced') }}
      </Button>
    </div>

    <!-- Advanced Search Fields -->
    <div v-if="showAdvanced" class="mt-4 p-4 bg-gray-50 rounded-lg">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">{{
            t('searchform.language')
          }}</label>
          <select
            v-model="filters.language"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option v-for="option in languageOptions" :key="option.value" :value="option.value">
              {{ option.label }}
            </option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">{{
            t('searchform.published_after')
          }}</label>
          <input
            v-model="filters.publishedAfter"
            type="number"
            min="1000"
            max="2024"
            :placeholder="t('searchform.year_placeholder_after')"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">{{
            t('searchform.published_before')
          }}</label>
          <input
            v-model="filters.publishedBefore"
            type="number"
            min="1000"
            max="2024"
            :placeholder="t('searchform.year_placeholder_before')"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">{{
            t('searchform.print_type')
          }}</label>
          <select
            v-model="filters.printType"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option v-for="option in printTypeOptions" :key="option.value" :value="option.value">
              {{ option.label }}
            </option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">{{
            t('searchform.order_by')
          }}</label>
          <select
            v-model="filters.orderBy"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option v-for="option in orderByOptions" :key="option.value" :value="option.value">
              {{ option.label }}
            </option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">{{
            t('searchform.max_results')
          }}</label>
          <select
            v-model="filters.maxResults"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option v-for="option in maxResultsOptions" :key="option.value" :value="option.value">
              {{ option.label }}
            </option>
          </select>
        </div>
      </div>

      <div class="mt-4 flex justify-center">
        <Button type="button" variant="neutral" size="sm" @click="clearFilters">
          {{ t('searchform.clear_filters') }}
        </Button>
      </div>
    </div>
  </form>
</template>
