<script setup>
import { ref } from 'vue';
import Layout from './Layout.vue';

const email = ref('');
const message = ref(null);
const error = ref(null);
const isLoading = ref(false);

function requestReset() {
  error.value = null;
  message.value = null;
  isLoading.value = true;
  
  fetch('/api/password/email', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify({ email: email.value })
  })
    .then(async res => {
      if (!res.ok) throw await res.json();
      return res.json();
    })
    .then(() => {
      message.value = 'If your email exists in our system, a password reset link has been sent.';
    })
    .catch(async err => {
      error.value = err?.message || 'Request failed.';
    })
    .finally(() => {
      isLoading.value = false;
    });
}

async function addToLibrary(book, status = 'want_to_read') {
  try {
    const response = await fetch('/api/library', {
      method: 'POST',
      headers: { 
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        google_book_id: book.id,
        title: book.volumeInfo.title,
        authors: book.volumeInfo.authors,
        description: book.volumeInfo.description,
        thumbnail: book.volumeInfo.imageLinks?.thumbnail,
        published_date: book.volumeInfo.publishedDate,
        page_count: book.volumeInfo.pageCount,
        language: book.volumeInfo.language,
        preview_link: book.volumeInfo.previewLink,
        status: status
      })
    });

    if (response.ok) {
      message.value = 'Book added to library!';
    } else {
      const errorData = await response.json();
      error.value = errorData.message || 'Failed to add book to library';
    }
  } catch (err) {
    error.value = 'Failed to add book to library';
  }
}
</script>

<template>
  <Layout>
    <div class="max-w-md mx-auto mt-12 p-6 bg-white rounded shadow">
      <h1 class="text-2xl font-bold mb-4">Reset Password</h1>
      <form @submit.prevent="requestReset">
        <div class="mb-4">
          <label class="block mb-1">Email</label>
          <input v-model="email" type="email" class="border rounded w-full p-2" required />
        </div>
        <div v-if="error" class="text-red-600 mb-2">{{ error }}</div>
        <div v-if="message" class="text-green-600 mb-2">{{ message }}</div>
        <button 
          type="submit" 
          :disabled="isLoading"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full disabled:opacity-50"
        >
          {{ isLoading ? 'Sending...' : 'Send Password Reset Link' }}
        </button>
      </form>
      <div class="mt-4 text-sm text-center">
        <a href="/login" class="text-blue-600 hover:underline">Back to Login</a>
      </div>
    </div>
  </Layout>
</template>
