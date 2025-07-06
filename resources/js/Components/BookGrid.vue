<script setup>
import { Inertia } from '@inertiajs/inertia';
import AddToLibraryButton from './AddToLibraryButton.vue';
import BookPlaceholder from './BookPlaceholder.vue';
import SearchByAuthor from './SearchByAuthor.vue';
import SearchBySubject from './SearchBySubject.vue';

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
    },
    pagination: {
        type: Object,
        default: () => ({
            currentPage: 1
        })
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

function searchByISBN(isbn) {
  Inertia.visit(route('search.index'), {
    method: 'get',
    data: {
      q: isbn,
      type: 'isbn'
    }
  });
}

function searchByPublisher(publisher) {
  Inertia.visit(route('search.index'), {
    method: 'get',
    data: {
      q: publisher,
      type: 'publisher'
    }
  });
}
</script>

<template>
    <div v-if="books && books.items && books.items.length > 0">
        <div v-if="title" class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold">{{ title }}</h2>
            <div v-if="pagination.totalPages > 1" class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                Page {{ pagination.currentPage }} of {{ pagination.totalPages }}
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <div v-for="book in books.items" :key="book.id"
                class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow">
                <div class="w-full h-48 mb-4 rounded overflow-hidden bg-gray-100 flex items-center justify-center">
                    <img v-if="book.volumeInfo.imageLinks?.thumbnail"
                        :src="book.volumeInfo.imageLinks.thumbnail.replace('http://', 'https://')"
                        :alt="book.volumeInfo.title" class="w-full h-full object-cover"
                        @error="$event.target.style.display = 'none'; $event.target.nextElementSibling.style.display = 'flex'" />
                    <div v-else class="flex flex-col items-center justify-center text-gray-400 p-4 text-center">
                        <svg class="w-12 h-12 mb-2" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z" />
                        </svg>
                        <span class="text-xs font-medium">{{ truncateTitle(book.volumeInfo.title) }}*** </span>
                    </div>
                </div>

                <h3 class="font-semibold text-lg mb-2 line-clamp-2">
                    {{ book.volumeInfo.title }}
                </h3>

                <searchByAuthor v-if="book.volumeInfo.authors && book.volumeInfo.authors.length > 0"
                    :authors="book.volumeInfo.authors" />


                <div v-if="book.volumeInfo.industryIdentifiers && book.volumeInfo.industryIdentifiers.length > 0"
                    class="text-gray-500 text-sm mb-2">
                    <template v-for="(identifier, index) in book.volumeInfo.industryIdentifiers" :key="index">
                        <span class="inline-flex items-center">
                            <span class="text-gray-400 mr-1">{{ identifier.type }}:</span>
                            <button @click="searchByISBN(identifier.identifier)"
                                class="text-blue-500 hover:text-blue-700 hover:underline transition-colors cursor-pointer font-mono text-xs">
                                {{ identifier.identifier }}
                            </button>
                        </span>
                        <span v-if="index < book.volumeInfo.industryIdentifiers.length - 1"
                            class="text-gray-400 mx-2">•</span>
                    </template>
                </div>

                <div v-if="book.volumeInfo.publisher" class="text-gray-500 text-sm mb-2">
                    <span class="text-gray-400">Publisher:</span>
                    <button
                        @click="searchByPublisher(book.volumeInfo.publisher)"
                        class="ml-1 text-blue-500 hover:text-blue-700 hover:underline transition-colors cursor-pointer"
                    >
                        {{ book.volumeInfo.publisher }}
                    </button>
                    <span v-if="book.volumeInfo.publishedDate" class="text-gray-400 ml-2">({{ book.volumeInfo.publishedDate }})</span>
                </div>

                <SearchBySubject
                    v-if="book.volumeInfo.categories && book.volumeInfo.categories.length > 0"
                    :categories="book.volumeInfo.categories"
                    :current-page="pagination.currentPage"
                />

                <p v-if="book.volumeInfo.description" class="text-gray-700 text-sm mb-4 line-clamp-3">
                    {{ book.volumeInfo.description }}
                </p>

                <div class="flex items-center justify-between mt-auto">
                    <AddToLibraryButton :book="book" :user-books="userBooks" />

                    <div class="flex space-x-2">
                        <a :href="`/books/${book.id}`" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Details
                        </a>

                        <a v-if="book.volumeInfo.previewLink" :href="book.volumeInfo.previewLink" target="_blank"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
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
