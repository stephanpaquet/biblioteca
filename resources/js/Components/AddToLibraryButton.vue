<template>
  <div class="relative">
    <button
      v-if="!isInLibrary"
      @click="addToLibrary"
      :disabled="isLoading"
      class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-3 py-1 rounded text-sm font-medium transition-colors"
    >
      {{ isLoading ? 'Adding...' : 'Add to Library' }}
    </button>
    
    <div v-else class="flex items-center space-x-2">
      <span class="text-green-600 text-sm font-medium">✓ In Library</span>
      <select
        v-model="currentStatus"
        @change="updateStatus"
        class="text-xs border rounded px-2 py-1"
      >
        <option value="want_to_read">Want to Read</option>
        <option value="reading">Reading</option>
        <option value="read">Read</option>
      </select>
    </div>

    <div v-if="message" class="absolute top-full left-0 mt-1 text-xs text-green-600">
      {{ message }}
    </div>
    <div v-if="error" class="absolute top-full left-0 mt-1 text-xs text-red-600">
      {{ error }}
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
  book: {
    type: Object,
    required: true
  },
  userBooks: {
    type: Array,
    default: () => []
  }
});

const isLoading = ref(false);
const message = ref('');
const error = ref('');
const currentStatus = ref('want_to_read');

const isInLibrary = computed(() => {
  const bookInLibrary = props.userBooks.find(b => b.google_book_id === props.book.id);
  if (bookInLibrary) {
    currentStatus.value = bookInLibrary.pivot?.status || 'want_to_read';
    return true;
  }
  return false;
});

async function addToLibrary() {
  isLoading.value = true;
  error.value = '';
  message.value = '';

  try {
    const bookData = {
      google_book_id: props.book.id,
      title: props.book.volumeInfo.title,
      authors: props.book.volumeInfo.authors || [],
      description: props.book.volumeInfo.description || '',
      thumbnail: props.book.volumeInfo.imageLinks?.thumbnail || '',
      published_date: props.book.volumeInfo.publishedDate || '',
      page_count: props.book.volumeInfo.pageCount || 0,
      language: props.book.volumeInfo.language || 'en',
      preview_link: props.book.volumeInfo.previewLink || '',
      status: 'want_to_read'
    };

    const response = await fetch('/api/library', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(bookData)
    });

    const result = await response.json();

    if (response.ok) {
      message.value = 'Added to library!';
      // Refresh the page to update the library status
      setTimeout(() => {
        Inertia.reload({ only: ['userBooks'] });
      }, 1000);
    } else {
      error.value = result.message || 'Failed to add book';
    }
  } catch (err) {
    error.value = 'Failed to add book to library';
  } finally {
    isLoading.value = false;
    setTimeout(() => {
      message.value = '';
      error.value = '';
    }, 3000);
  }
}

async function updateStatus() {
  try {
    const bookInLibrary = props.userBooks.find(b => b.google_book_id === props.book.id);
    
    await fetch(`/api/library/${bookInLibrary.id}/status`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ status: currentStatus.value })
    });

    message.value = 'Status updated!';
    setTimeout(() => message.value = '', 2000);
  } catch (err) {
    error.value = 'Failed to update status';
    setTimeout(() => error.value = '', 3000);
  }
}
</script>
