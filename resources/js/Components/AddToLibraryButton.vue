
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
      <button
        v-if="showRemove"
        @click="removeFromLibrary"
        class="text-red-600 hover:text-red-800 text-xs font-medium"
      >
        Remove
      </button>
    </div>

    <div v-if="libraryStore.error" class="absolute top-full left-0 mt-1 text-xs text-red-600">
      {{ libraryStore.error }}
    </div>
  </div>
</template>
