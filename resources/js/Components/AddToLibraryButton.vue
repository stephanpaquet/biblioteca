
<script setup>
import { ref, computed } from 'vue'
import { useLibraryStore } from '../stores/library'

const props = defineProps({
  book: {
    type: Object,
    required: true
  }
})

const libraryStore = useLibraryStore()
const message = ref('')
const currentStatus = ref('want_to_read')

const isInLibrary = computed(() => {
  return libraryStore.isBookInLibrary(props.book.id)
})

async function addToLibrary() {
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
  }

  const result = await libraryStore.addToLibrary(bookData)

  if (result.success) {
    setTimeout(() => message.value = '', 3000)
  }
}

async function updateStatus() {
  libraryStore.updateBookStatus(props.book.id, currentStatus.value)
  message.value = 'Status updated!'
  setTimeout(() => message.value = '', 2000)
}
</script>

<template>
  <div class="relative">
    <button
      v-if="!isInLibrary"
      @click="addToLibrary"
      :disabled="libraryStore.isLoading"
      class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-3 py-1 rounded text-sm font-medium transition-colors"
    >
      {{ libraryStore.isLoading ? 'Adding...' : 'Add to Library' }}
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
    <div v-if="libraryStore.error" class="absolute top-full left-0 mt-1 text-xs text-red-600">
      {{ libraryStore.error }}
    </div>
  </div>
</template>
