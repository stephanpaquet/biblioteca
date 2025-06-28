<script setup>
import { onMounted } from 'vue';
import Layout from './Layout.vue';
import SearchForm from '../Components/SearchForm.vue';
import BookGrid from '../Components/BookGrid.vue';
import { useLibraryStore } from '../stores/library';
import { useSearchStore } from '../stores/search';

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
  translations: {
    type: Object,
    required: true
  }
});

const libraryStore = useLibraryStore();
const searchStore = useSearchStore();

onMounted(() => {
  // Initialize stores with props data
  libraryStore.setBooks(props.userBooks);
  
  if (props.query) {
    searchStore.setQuery(props.query);
    searchStore.setResults(props.books);
  }
});

function t(key) {
  return props.translations.home[key] || key;
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
          :translations="translations"
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
        :translations="translations"
      />
    </div>

    <!-- Featured Books Section -->
    <div v-else class="container mx-auto px-4 py-12">
      <BookGrid 
        :books="featured" 
        :user-books="userBooks"
        :title="t('featured_books')"
        :translations="translations"
      />
      
      <!-- Quick Actions -->
      <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="text-center p-6 bg-white rounded-lg shadow-md">
          <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-2">{{ t('quick_actions.search.title') }}</h3>
          <p class="text-gray-600">{{ t('quick_actions.search.description') }}</p>
        </div>
        
        <div class="text-center p-6 bg-white rounded-lg shadow-md">
          <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-2">{{ t('quick_actions.library.title') }}</h3>
          <p class="text-gray-600">{{ t('quick_actions.library.description') }}</p>
        </div>
        
        <div class="text-center p-6 bg-white rounded-lg shadow-md">
          <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-2">{{ t('quick_actions.progress.title') }}</h3>
          <p class="text-gray-600">{{ t('quick_actions.progress.description') }}</p>
        </div>
      </div>
       <p class="text-gray-600">{{ t('quick_actions.library.description') }}</p>
      
        <div class="text-center p-6 bg-white rounded-lg shadow-md">
          <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-2">{{ t('quick_actions.progress.title') }}</h3>
          <p class="text-gray-600">{{ t('quick_actions.progress.description') }}</p>
        </div>
    </div>
     
  </Layout>
</template>


