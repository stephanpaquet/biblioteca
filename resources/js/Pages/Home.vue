<script setup>
import Layout from './Layout.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

const query = ref('');
const results = ref([]);
const loading = ref(false);
const error = ref(null);

const searchBooks = async () => {
  loading.value = true;
  error.value = null;
  results.value = [];
  try {
    const response = await fetch(`/api/books/search?q=${encodeURIComponent(query.value)}`);
    if (!response.ok) throw new Error('API error');
    const data = await response.json();
    results.value = data.data || [];
  } catch (e) {
    error.value = 'An error occurred while searching.';
  } finally {
    loading.value = false;
  }
};
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
    <div class="container mx-auto p-8">
      <h1 class="bg-green-100 p-4 text-4xl font-bold mb-4 text-center">Bonjour</h1>
      <p class="text-lg text-center mb-8">This page is rendered using Inertia.js and Vue 3.</p>
      <form @submit.prevent="searchBooks" class="flex flex-col items-center mb-8">
        <input v-model="query" type="text" placeholder="Search for a book..." class="border rounded p-2 w-full max-w-md mb-2" />
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" :disabled="loading">
          <span v-if="loading">Searching...</span>
          <span v-else>Search</span>
        </button>
      </form>
      <div v-if="error" class="text-red-600 text-center mb-4">{{ error }}</div>
      <div v-if="results.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="book in results" :key="book.id" class="border rounded p-4 bg-white shadow">
          <div class="flex items-center mb-2">
            <img v-if="book.thumbnail" :src="book.thumbnail" alt="Cover" class="w-16 h-24 object-cover mr-4 rounded" />
            <div>
              <h2 class="text-xl font-semibold">{{ book.title }}</h2>
              <div class="text-gray-600 text-sm">{{ book.authors?.join(', ') }}</div>
            </div>
          </div>
          <div class="text-gray-700 text-sm mb-2">{{ book.description }}</div>
          <a v-if="book.previewLink" :href="book.previewLink" target="_blank" class="text-blue-600 hover:underline text-sm">Preview</a>
        </div>
      </div>
      <div v-else-if="!loading && query" class="text-center text-gray-500">No results found.</div>
    </div>
  </Layout>
</template>
