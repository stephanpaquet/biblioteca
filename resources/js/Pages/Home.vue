<script setup>
import { onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Layout from '../Layouts/Layout.vue';
import SearchForm from '../Components/SearchForm.vue';
import BookGrid from '../Components/BookGrid.vue';
import { useLibraryStore } from '../stores/library';
import { useSearchStore } from '../stores/search';
import { useAuthStore } from '../stores/auth';
import { useTranslationsStore } from '../stores/translations';

const props = defineProps({
  books: {
    type: Object,
    default: null
  },
  query: {
    type: String,
    default: ''
  },
  userBooks: {
    type: Array,
    default: () => []
  },
  featured: {
    type: Object,
    default: null
  },
  auth: {
    type: Object,
    default: null
  },
  // Remove translations prop since it's now global
  supportedLocales: {
    type: Array,
    default: () => ['fr']
  },
  currentLocale: {
    type: String,
    default: 'fr'
  }
});

const libraryStore = useLibraryStore();
const searchStore = useSearchStore();
const authStore = useAuthStore();
const translationsStore = useTranslationsStore();

onMounted(() => {
  // Initialize stores with props data
  libraryStore.setBooks(props.userBooks);

  translationsStore.setTranslations({
    texts: usePage().props.translations,
    currentLocale: props.currentLocale,
    supportedLocales: props.supportedLocales,
  });

  authStore.setUser(props.auth.user || null);
  if (props.query) {
    searchStore.setQuery(props.query);
    searchStore.setResults(props.books);
  }
});

function t(key) {
  const translations = translationsStore.translations?.texts?.home;
  return translations?.[key] || key;
}
</script>

<template>
  <Layout>
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
      <div class="container mx-auto px-4 text-center">
        <h1 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4">
          {{ t('title') }}
        </h1>
        <p class="text-lg text-gray-600 mb-6 max-w-2xl mx-auto">
          {{ t('subtitle') }}
        </p>

        <SearchForm
          :initial-query="query"
        />
      </div>
    </div>

    <!-- Search Results Section -->
    <div v-if="query" class="container mx-auto px-4 py-12">
      <BookGrid
        :books="books"
        :user-books="userBooks"
        :title="t('search_results').replace(':query', query)"
        :show-no-results="true"
      />
    </div>

    <!-- Featured Books Section -->
    <div v-else class="container mx-auto px-4 py-12">
      <BookGrid
        :books="featured"
        :user-books="userBooks"
        :title="t('featured_books')"
      />
    </div>
  </Layout>
</template>


