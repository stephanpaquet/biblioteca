
<script setup>
import { ref, computed } from 'vue'
import { useLibraryStore } from '../stores/library'
import { useToast } from '../composables/useToast'

const props = defineProps({
  book: {
    type: Object,
    required: true
  },
  showRemove: {
    type: Boolean,
    default: false
  },
  showSync: {
    type: Boolean,
    default: false
  }
})

const libraryStore = useLibraryStore()
const toast = useToast()
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
    toast.bookAdded(bookData.title)
  }
}

async function updateStatus() {
  libraryStore.updateBookStatus(props.book.id, currentStatus.value)
  toast.success('Status updated!')
}

async function removeFromLibrary() {
  if (confirm('Are you sure you want to remove this book from your library?')) {
    await libraryStore.removeFromLibrary(props.book.id)
  }
}

async function syncBook() {
  if (confirm('This will update the book information with the latest data from Google Books. Continue?')) {
    await libraryStore.syncBookWithGoogleApi(props.book.id)
  }
}
</script>

<template>
  <div class="relative w-full">
    <button
      v-if="!isInLibrary"
      @click="addToLibrary"
      :disabled="libraryStore.isLoading"
      class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center justify-center space-x-2"
    >
      <span>{{ libraryStore.isLoading ? 'Adding...' : 'Add to Library' }}</span>
    </button>

    <div v-else class="w-full">
      <div class="flex items-center justify-center space-x-2 mb-2">
        <span class="text-green-600 text-sm font-medium flex items-center">
          <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
          </svg>
          In Library
        </span>
      </div>

      <select
        v-model="currentStatus"
        @change="updateStatus"
        class="w-full text-xs border border-gray-300 rounded-md px-2 py-1.5 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent mb-2"
      >
        <option value="want_to_read">📚 Want to Read</option>
        <option value="reading">📖 Reading</option>
        <option value="read">✅ Read</option>
      </select>

      <div class="flex space-x-1">
        <button
          v-if="showSync"
          @click="syncBook"
          :disabled="libraryStore.isLoading"
          class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-200 px-2 py-1 rounded text-xs font-medium disabled:opacity-50 transition-colors flex items-center justify-center"
          title="Sync with Google Books"
        >
          <span :class="libraryStore.isLoading ? 'animate-spin' : ''">⟳</span>
          <span class="ml-1">{{ libraryStore.isLoading ? 'Syncing...' : 'Sync' }}</span>
        </button>

        <button
          v-if="showRemove"
          @click="removeFromLibrary"
          class="flex-1 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-2 py-1 rounded text-xs font-medium transition-colors"
          title="Remove from Library"
        >
          🗑️ Remove
        </button>
      </div>
    </div>

    <div v-if="libraryStore.error" class="absolute top-full left-0 mt-1 text-xs text-red-600 bg-red-50 border border-red-200 rounded px-2 py-1">
      {{ libraryStore.error }}
    </div>
  </div>
</template>
