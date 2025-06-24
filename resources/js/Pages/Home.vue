<script setup>
import Layout from './Layout.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { ref, watch, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Paginator from '../Components/Paginator.vue';
import { route } from 'ziggy-js';

const query = ref('');
const results = ref([]);
const loading = ref(false);
const error = ref(null);
const page = ref(1);
const totalItems = ref(0);
const itemsPerPage = 12; // Google Books API max is 40, but let's use 12 for UI

const searchBooks = async () => {
  loading.value = true;
  error.value = null;
  results.value = [];
  try {
    const startIndex = (page.value - 1) * itemsPerPage;
    const response = await fetch(`/api/books/search?q=${encodeURIComponent(query.value)}&maxResults=${itemsPerPage}&startIndex=${startIndex}`);
    if (!response.ok) throw new Error('API error');
    const data = await response.json();
    results.value = data.data || [];
    totalItems.value = data.total || 0;
  } catch (e) {
    error.value = 'An error occurred while searching.';
  } finally {
    loading.value = false;
  }
};

// Update the query string param 'q' when the input changes
watch(query, (newVal) => {
  const url = new URL(window.location.href);
  if (newVal) {
    url.searchParams.set('q', newVal);
  } else {
    url.searchParams.delete('q');
  }
  window.history.replaceState({}, '', url);
});

// On component mount, initialize the query from the 'q' query string parameter and call searchBooks if present
onMounted(() => {
  const url = new URL(window.location.href);
  const qParam = url.searchParams.get('q');
  if (qParam) {
    query.value = qParam;
    searchBooks();
  }
});

// Watch for page changes to trigger search
watch(page, () => {
  if (query.value) searchBooks();
});

const gotoBookDetails = (id) => {
  Inertia.visit(route('book-detail', { id }));
};

const authorSearchQuery = author => `inauthor:"${author}"`;
const authorSearchHref = author => `?q=${encodeURIComponent(authorSearchQuery(author))}&page=1`;

</script>

<template>
  <Layout>
    <Head
      title="Biblioteca | Modern Digital Library & Book Management"
      meta="[
        { name: 'description', content: 'Welcome to Biblioteca, your modern digital library and book management platform. Discover, manage, and connect with books easily online.' },
        { property: 'og:title', content: 'Biblioteca | Modern Digital Library & Book Management' },
        { property: 'og:description', content: 'Welcome to Biblioteca, your modern digital library and book management platform. Discover, manage, and connect with books easily online.' },
        { property: 'og:type', content: 'website' },
        { property: 'og:locale', content: 'en_US' }
      ]"
    />
    <div class="container mx-auto pb-8">
      <Paginator
        v-model="page"
        :total="totalItems"
        :per-page="itemsPerPage"
        :loading="loading"
      />
      <form @submit.prevent="searchBooks" class="flex flex-row items-center justify-center gap-2 mb-8 w-full max-w-md mx-auto">
        <input v-model="query" type="text" placeholder="Search for a book..." class="border rounded p-2 flex-1 min-w-0" />
        <button type="submit"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:bg-blue-300 disabled:cursor-not-allowed"
          :disabled="loading || !query"
        >
          <span v-if="loading">Searching...</span>
          <span v-else>Search</span>
        </button>
      </form>
      <div v-if="error" class="text-red-600 text-center mb-4">{{ error }}</div>
      <div>
        <div v-if="results.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="book in results"
            :key="book.id"
            class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col transition-transform hover:scale-105 hover:shadow-2xl border border-gray-100 cursor-pointer group"
            @click="gotoBookDetails(book.id)"
          >
            <div class="flex items-start p-4 gap-4 flex-shrink-0">
              <img v-if="book.thumbnail" :src="book.thumbnail" alt="Cover" class="w-20 h-32 object-cover rounded-md shadow" />
              <div class="flex-1">
                <h2 class="text-xl font-bold mb-1">{{ book.title }}</h2>
                <div class="text-gray-600 text-sm mb-1">
                  <template v-if="book.authors?.length">
                    <span v-for="(author, idx) in book.authors" :key="author">
                      <a
                        :href="authorSearchHref(author)"
                        class="text-blue-700 hover:underline"
                        @click.prevent="query.value = authorSearchQuery(author); page.value = 1; searchBooks();"
                      >{{ author }}</a><span v-if="idx < book.authors.length - 1">, </span>
                    </span>
                  </template>
                </div>
                <div class="text-gray-400 text-xs mb-2">Published: {{ book.publishedDate }}</div>
              </div>
            </div>
            <div class="flex flex-col flex-1 px-4 pb-4">
              <div class="text-gray-700 text-sm mb-3 line-clamp-8">{{ book.description || 'No description.'}}</div>
              <div class="flex gap-2 mt-auto justify-between items-end">
                <a
                  v-if="book.previewLink"
                  :href="book.previewLink"
                  target="_blank"
                  class="text-blue-600 hover:underline text-xs font-medium z-10 group-hover:underline"
                  @click.stop
                >Preview on Google Books</a>
                <button
                  class="text-white bg-green-600 hover:bg-green-700 text-xs font-semibold px-3 py-1 rounded transition-colors z-10 cursor-pointer"
                  @click.stop="gotoBookDetails(book.id)"
                >Details</button>
              </div>
            </div>
          </div>
        </div>
        <div v-else-if="!loading && query" class="text-center text-gray-500">No results found.</div>
      </div>
      <Paginator
        v-model="page"
        :total="totalItems"
        :per-page="itemsPerPage"
        :loading="loading"
      />
    </div>
  </Layout>
</template>
