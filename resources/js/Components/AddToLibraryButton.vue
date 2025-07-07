<script setup>
import { ref, computed } from 'vue'
import { useLibraryStore } from '../stores/library'
import { useToast } from '../composables/useToast'
import Button from './Button.vue'
import Icon from './Icon.vue'

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
    <Button
      v-if="!isInLibrary"
      @click="addToLibrary"
      :disabled="libraryStore.isLoading"
      :loading="libraryStore.isLoading"
      variant="primary"
      size="sm"
      class="w-full"
      left-icon="add"
    >
      {{ libraryStore.isLoading ? 'Adding...' : 'Add to Library' }}
    </Button>

    <div v-else class="w-full">
      <div class="flex items-center justify-center space-x-2 mb-2">
        <span class="text-success-600 text-sm font-medium flex items-center">
          <Icon name="check_circle" class="w-4 h-4 mr-1" />
          In Library
        </span>
      </div>

      <select
        v-model="currentStatus"
        @change="updateStatus"
        class="w-full text-xs border border-gray-300 rounded-md px-2 py-1.5 bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent mb-2 transition-colors"
      >
        <option value="want_to_read">

          <Icon name="menu_book" class="w-12 h-12 mb-2" /> Want to Read</option>
        <option value="reading">📚 Reading</option>
        <option value="read">✅ Read</option>
      </select>

      <div class="flex space-x-1">
        <Button
          v-if="showSync"
          @click="syncBook"
          :disabled="libraryStore.isLoading"
          :loading="libraryStore.isLoading"
          variant="outline"
          size="sm"
          class="flex-1"
          left-icon="sync"
          title="Sync with Google Books"
        >
          {{ libraryStore.isLoading ? 'Syncing...' : 'Sync' }}
        </Button>

        <Button
          v-if="showRemove"
          @click="removeFromLibrary"
          variant="danger"
          size="sm"
          class="flex-1"
          left-icon="delete"
          title="Remove from Library"
        >
          Remove
        </Button>
      </div>
    </div>

    <div v-if="libraryStore.error" class="absolute top-full left-0 mt-1 text-xs text-danger-600 bg-danger-50 border border-danger-200 rounded px-2 py-1">
      {{ libraryStore.error }}
    </div>
  </div>
</template>
