<template>
  <div v-if="books && books.items && books.items.length > 0">
    <h2 v-if="title" class="text-2xl font-bold mb-6">{{ title }}</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <div v-for="book in books.items" :key="book.id" class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow">
        <img 
          :src="book.volumeInfo.imageLinks?.thumbnail || '/placeholder-book.png'" 
          :alt="book.volumeInfo.title"
          class="w-full h-48 object-cover rounded mb-4"
        />
        
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
