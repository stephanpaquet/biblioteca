<template>
  <Layout>
    <div class="container mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold mb-8">My Library</h1>
      
      <div v-if="books.length === 0" class="text-center py-12">
        <p class="text-gray-600 mb-4">Your library is empty</p>
        <a href="/search" class="text-blue-600 hover:underline">Start searching for books</a>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div v-for="book in books" :key="book.id" class="bg-white rounded-lg shadow-md p-4">
          <img 
            :src="book.thumbnail || '/placeholder-book.png'" 
            :alt="book.title"
            class="w-full h-48 object-cover rounded mb-4"
          />
          <h3 class="font-semibold text-lg mb-2 line-clamp-2">{{ book.title }}</h3>
          <p v-if="book.authors" class="text-gray-600 mb-2">
            {{ Array.isArray(book.authors) ? book.authors.join(', ') : book.authors }}
          </p>
          
          <div class="flex items-center justify-between mt-4">
            <select 
              :value="book.pivot.status" 
              @change="updateStatus(book.id, $event.target.value)"
              class="text-sm border rounded px-2 py-1"
            >
              <option value="want_to_read">Want to Read</option>
              <option value="reading">Reading</option>
              <option value="read">Read</option>
            </select>
            
            <button 
              @click="removeFromLibrary(book.id)"
              class="text-red-600 hover:text-red-800 text-sm"
            >
              Remove
            </button>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { ref } from 'vue';
import Layout from './Layout.vue';

const props = defineProps(['books']);

async function updateStatus(bookId, status) {
  try {
    await fetch(`/api/library/${bookId}/status`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ status })
    });
  } catch (error) {
    console.error('Failed to update status:', error);
  }
}

async function removeFromLibrary(bookId) {
  if (confirm('Are you sure you want to remove this book from your library?')) {
    try {
      await fetch(`/api/library/${bookId}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      });
      // Refresh page or remove from local state
      window.location.reload();
    } catch (error) {
      console.error('Failed to remove book:', error);
    }
  }
}
</script>
