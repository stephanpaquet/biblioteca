<script setup>
import Layout from '../Layouts/Layout.vue';
import AddToLibraryButton from '../Components/AddToLibraryButton.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { useTranslationsStore } from '../stores/translations';


const props = defineProps({
  supportedLocales: {
    type: Array,
    default: () => ['fr']
  },
  currentLocale: {
    type: String,
    default: 'fr'
  }
});
const translationsStore = useTranslationsStore();
const page = usePage();

const bookId = page.props.id;
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

function truncateTitle(title, maxLength = 20) {
  if (!title) return 'No Title';
  return title.length > maxLength ? title.substring(0, maxLength) + '...' : title;
}

onMounted(() => {
  translationsStore.setTranslations({
    texts: usePage().props.translations,
    currentLocale: props.currentLocale,
    supportedLocales: props.supportedLocales,
  });

  fetchBook();
});
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
          <div class="w-full h-48 mr-6 rounded overflow-hidden bg-gray-100 flex items-center justify-center">
            <img
              v-if="book.thumbnail"
              :src="book.thumbnail.replace('http://', 'https://')"
              :alt="book.title"
              class="w-full h-full object-cover"
              @error="$event.target.style.display='none'; $event.target.nextElementSibling.style.display='flex'"
            />
            <div
              v-else
              class="flex flex-col items-center justify-center text-gray-400 p-2 text-center w-full h-full"
            >
              <svg class="w-8 h-8 mb-1" fill="currentColor" viewBox="0 0 24 24">
                <path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z"/>
              </svg>
              <span class="text-xs font-medium text-center leading-tight">{{ truncateTitle(book.title) }}</span>
            </div>
          </div>
          <div>
            <h1 class="text-3xl font-bold mb-2">{{ book.title }}</h1>
            <div class="text-gray-700 mb-2">By {{ book.authors?.join(', ') }}</div>
            <div class="text-gray-500 text-sm mb-2">Published: {{ book.publishedDate }}</div>

            <!-- Add to Library Button -->
            <div class="mb-4">
              <AddToLibraryButton :book="{ id: book.id, volumeInfo: book }" />
            </div>

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
