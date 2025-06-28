<template>
  <div v-if="books && books.items && books.items.length > 0">
    <h2 v-if="title" class="text-2xl font-bold mb-6">{{ title }}</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <div v-for="book in books.items" :key="book.id" class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow">
        <div class="w-full h-48 mb-4 rounded overflow-hidden bg-gray-100 flex items-center justify-center">
          <img 
            v-if="book.volumeInfo.imageLinks?.thumbnail"
            :src="book.volumeInfo.imageLinks.thumbnail.replace('http://', 'https://')" 
            :alt="book.volumeInfo.title"
            class="w-full h-full object-cover"
            @error="$event.target.style.display='none'; $event.target.nextElementSibling.style.display='flex'"
          />
          <div 
            v-else
            class="flex flex-col items-center justify-center text-gray-400 p-4 text-center"
          >
            <svg class="w-12 h-12 mb-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z"/>
            </svg>
            <span class="text-xs font-medium">{{ truncateTitle(book.volumeInfo.title) }}</span>
            <span v-if="book.volumeInfo.authors" class="text-xs mt-1 opacity-75">
              {{ book.volumeInfo.authors[0] }}
            </span>
          </div>
        </div>
        
        <h3 class="font-semibold text-lg mb-2 line-clamp-2">
          {{ book.volumeInfo.title }}
        </h3>
        
        <p v-if="book.volumeInfo.authors" class="text-gray-600 mb-2">
          {{ book.volumeInfo.authors.join(', ') }}
        </p>
        
        <p v-if="book.volumeInfo.description" class="text-gray-700 text-sm mb-4 line-clamp-3">
          {{ book.volumeInfo.description }}
        </p>
        
        <div class="flex items-center justify-between mt-auto">
          <AddToLibraryButton 
            :book="book" 
            :user-books="userBooks"
          />
          
          <div class="flex space-x-2">
            <a 
              :href="`/books/${book.id}`"
              class="text-blue-600 hover:text-blue-800 text-sm font-medium"
            >
              Details
            </a>
            
            <a 
              v-if="book.volumeInfo.previewLink"
              :href="book.volumeInfo.previewLink"
              target="_blank"
              class="text-blue-600 hover:text-blue-800 text-sm font-medium"
            >
              Preview
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div v-else-if="showNoResults" class="text-center py-12">
    <div class="text-gray-500 text-lg">No books found</div>
    <p class="text-gray-400 mt-2">Try adjusting your search terms</p>
  </div>
</template>

<script setup>
import AddToLibraryButton from './AddToLibraryButton.vue';
import BookPlaceholder from './BookPlaceholder.vue';

defineProps({
  books: {
    type: Object,
    default: null
  },
  userBooks: {
    type: Array,
    default: () => []
  },
  title: {
    type: String,
    default: ''
  },
  showNoResults: {
    type: Boolean,
    default: false
  }
});

function truncateTitle(title) {
  return title.length > 25 ? title.substring(0, 25) + '...' : title;
}

function handleImageError(event) {
  event.target.style.display = 'none';
  if (event.target.nextElementSibling) {
    event.target.nextElementSibling.style.display = 'flex';
  }
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
