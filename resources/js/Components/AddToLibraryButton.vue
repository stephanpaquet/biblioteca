<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { useLibraryStore } from '../stores/library';
import { useToast } from '../composables/useToast';
import { useTranslationsStore } from '../stores/translations';
import Button from './Button.vue';
import Icon from './Icon.vue';

const props = defineProps({
  book: {
    type: Object,
    required: true,
  },
  showRemove: {
    type: Boolean,
    default: false,
  },
  showSync: {
    type: Boolean,
    default: false,
  },
});

const libraryStore = useLibraryStore();
const toast = useToast();
const { t } = useTranslationsStore();
const currentStatus = ref('want_to_read');
const showDropdown = ref(false);

// Initialize current status from the book's actual status
const initializeStatus = () => {
  const libraryBook = libraryStore.books.find((book) => book.google_book_id === props.book.id);
  if (libraryBook?.pivot?.status) {
    currentStatus.value = libraryBook.pivot.status;
  }
};

// Watch for changes in library books to update status
watch(() => libraryStore.books, initializeStatus, { immediate: true });

const statusOptions = computed(() => [
  {
    value: 'want_to_read',
    label: t('addtolibrary.want_to_read'),
    icon: 'bookmark_border',
    color: 'text-blue-600',
  },
  {
    value: 'reading',
    label: t('addtolibrary.reading'),
    icon: 'auto_stories',
    color: 'text-orange-600',
  },
  { value: 'read', label: t('addtolibrary.read'), icon: 'task_alt', color: 'text-green-600' },
]);

const currentStatusOption = computed(() => {
  return (
    statusOptions.value.find((option) => option.value === currentStatus.value) ||
    statusOptions.value[0]
  );
});

const isInLibrary = computed(() => {
  return libraryStore.isBookInLibrary(props.book.id);
});

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
    status: 'want_to_read',
  };

  const result = await libraryStore.addToLibrary(bookData);

  if (result.success) {
    toast.bookAdded(bookData.title);
  }
}

async function updateStatus() {
  const result = await libraryStore.updateBookStatus(props.book.id, currentStatus.value);

  if (result.success) {
    toast.success(t('addtolibrary.status_updated'));
  } else {
    toast.error(result.error || t('addtolibrary.status_update_failed'));
    // Revert the dropdown to the previous state if the API call failed
    // We'll need to track the previous status for this
  }
}

async function selectStatus(status) {
  const previousStatus = currentStatus.value;
  currentStatus.value = status;
  showDropdown.value = false;

  const result = await updateStatus();

  // If the update failed, revert to previous status
  if (!result.success) {
    currentStatus.value = previousStatus;
  }
}

// Close dropdown when clicking outside
function handleClickOutside(event) {
  if (!event.target.closest('.status-dropdown')) {
    showDropdown.value = false;
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});

async function removeFromLibrary() {
  if (window.confirm(t('addtolibrary.remove_confirmation'))) {
    await libraryStore.removeFromLibrary(props.book.id)
  }
}

async function syncBook() {
  if (window.confirm(t('addtolibrary.sync_confirmation'))) {
    await libraryStore.syncBookWithGoogleApi(props.book.id);
  }
}
</script>

<template>
  <div class="relative w-full">
    <Button
      v-if="!isInLibrary"
      :disabled="libraryStore.isLoading"
      :loading="libraryStore.isLoading"
      variant="primary"
      size="sm"
      class="w-full"
      left-icon="add"
      @click="addToLibrary"
    >
      {{ libraryStore.isLoading ? t('addtolibrary.adding') : t('addtolibrary.add_to_library') }}
    </Button>

    <div v-else class="w-full">
      <div class="flex items-center justify-center space-x-2 mb-2">
        <span class="text-success-600 text-sm font-medium flex items-center">
          <Icon name="check_circle" class="w-4 h-4 mr-1" />
          {{ t('addtolibrary.in_library') }}
        </span>
      </div>

      <!-- Custom Status Dropdown with Icons (Vuetify-style) -->
      <div class="relative mb-2 status-dropdown">
        <button
          class="w-full flex items-center justify-between px-3 py-2 text-xs border border-gray-300 rounded-md bg-white hover:border-primary-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
          @click="showDropdown = !showDropdown"
        >
          <div class="flex items-center space-x-2">
            <Icon
              :name="currentStatusOption.icon"
              :class="['w-4 h-4', currentStatusOption.color]"
            />
            <span class="text-gray-700">{{ currentStatusOption.label }}</span>
          </div>
          <Icon
            name="expand_more"
            class="w-4 h-4 text-gray-400 transition-transform duration-200"
            :class="{ 'rotate-180': showDropdown }"
          />
        </button>

        <!-- Dropdown Menu -->
        <div
          v-if="showDropdown"
          class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-md shadow-lg z-20 overflow-hidden"
        >
          <button
            v-for="option in statusOptions"
            :key="option.value"
            class="w-full px-3 py-2 text-left text-xs hover:bg-gray-50 flex items-center space-x-2 transition-colors border-b border-gray-100 last:border-b-0"
            :class="{
              'bg-primary-50 text-primary-700': currentStatus === option.value,
              'text-gray-700': currentStatus !== option.value,
            }"
            @click="selectStatus(option.value)"
          >
            <Icon :name="option.icon" :class="['w-4 h-4', option.color]" />
            <span>{{ option.label }}</span>
            <Icon
              v-if="currentStatus === option.value"
              name="check"
              class="w-4 h-4 text-primary-600 ml-auto"
            />
          </button>
        </div>
      </div>

      <div class="flex space-x-1">
        <Button
          v-if="showSync"
          :disabled="libraryStore.isLoading"
          :loading="libraryStore.isLoading"
          variant="outline"
          size="sm"
          class="flex-1"
          left-icon="sync"
          :title="t('addtolibrary.sync_tooltip')"
          @click="syncBook"
        >
          {{ libraryStore.isLoading ? t('addtolibrary.syncing') : t('addtolibrary.sync') }}
        </Button>

        <Button
          v-if="showRemove"
          variant="danger"
          size="sm"
          class="flex-1"
          left-icon="delete"
          :title="t('addtolibrary.remove_tooltip')"
          @click="removeFromLibrary"
        >
          {{ t('addtolibrary.remove') }}
        </Button>
      </div>
    </div>

    <div
      v-if="libraryStore.error"
      class="absolute top-full left-0 mt-1 text-xs text-danger-600 bg-danger-50 border border-danger-200 rounded px-2 py-1"
    >
      {{ libraryStore.error }}
    </div>
  </div>
</template>
