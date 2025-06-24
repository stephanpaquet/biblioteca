<script setup>
import Layout from './Layout.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/inertia-vue3';
import { route } from 'ziggy-js';

const page = usePage();
const bookId = page.props.value.id;
const apiUrl = route('api.books.show', { id: bookId });
const book = ref(null);
const loading = ref(true);
const error = ref(null);

const fetchBook = async () => {
  loading.value = true;
  error.value = null;
  try {
    const response = await fetch(apiUrl);
    if (!response.ok) throw new Error('API error');
    const data = await response.json();
    book.value = data.data || null;
  } catch (e) {
    error.value = 'An error occurred while fetching the book.';
  } finally {
    loading.value = false;
  }
};

const goBack = () => {
  window.history.back();
};

onMounted(fetchBook);
</script>

<template>
  <Layout>
    <Head title="Book Details" />
    <div class="container mx-auto p-8">
      <button @click="goBack" class="mb-4 text-blue-600 hover:underline">&larr; Back to search</button>
      <div v-if="loading" class="text-center">Loading...</div>
      <div v-else-if="error" class="text-red-600 text-center">{{ error }}</div>
      <div v-else-if="book" class="max-w-2xl mx-auto bg-white rounded shadow p-6">
        <div class="flex mb-4">
          <img v-if="book.thumbnail" :src="book.thumbnail" alt="Cover" class="w-32 h-48 object-cover rounded mr-6" />
          <div>
            <h1 class="text-3xl font-bold mb-2">{{ book.title }}</h1>
            <div class="text-gray-700 mb-2">By {{ book.authors?.join(', ') }}</div>
            <div class="text-gray-500 text-sm mb-2">Published: {{ book.publishedDate }}</div>
            <a v-if="book.previewLink" :href="book.previewLink" target="_blank" class="text-blue-600 hover:underline text-sm">Preview on Google Books</a>
          </div>
        </div>
        <div class="text-gray-800 mb-4" v-html="book.description"></div>
        <div v-if="book.categories?.length" class="mb-2">
          <span class="font-semibold">Categories:</span> {{ book.categories.join(', ') }}
        </div>
        <div v-if="book.pageCount" class="mb-2">
          <span class="font-semibold">Pages:</span> {{ book.pageCount }}
        </div>
        <div v-if="book.language" class="mb-2">
          <span class="font-semibold">Language:</span> {{ book.language.toUpperCase() }}
        </div>
        <div v-if="book.publisher" class="mb-2">
          <span class="font-semibold">Publisher:</span> {{ book.publisher }}
        </div>
        <div v-if="book.isbn" class="mb-2">
          <span class="font-semibold">ISBN:</span> {{ book.isbn }}
        </div>
      </div>
      <div v-else class="text-center text-gray-500">Book not found.</div>
    </div>
  </Layout>
</template>
