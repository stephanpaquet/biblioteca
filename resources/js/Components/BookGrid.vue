<script setup>
import { router } from '@inertiajs/vue3';
import AddToLibraryButton from './AddToLibraryButton.vue';
import BookPlaceholder from './BookPlaceholder.vue';
import SearchByAuthor from './SearchByAuthor.vue';
import SearchBySubject from './SearchBySubject.vue';
import Button from './Button.vue';
import Icon from './Icon.vue';
import { useTranslationsStore } from '../stores/translations';

const translationsStore = useTranslationsStore();

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
    },
    showRemove: {
        type: Boolean,
        default: false
    },
    showSync: {
        type: Boolean,
        default: false
    }
});

// Translation helper
function t(key, replacements = {}) {
    // Get from translations store with fallback path
    const path = `bookgrid.${key}`;
    let translation = translationsStore.translations?.texts?.bookgrid?.[key];

    // If translation not found, return key itself as fallback
    if (!translation) return key;

    // Handle replacements if any
    if (Object.keys(replacements).length > 0) {
        Object.entries(replacements).forEach(([key, value]) => {
            translation = translation.replace(`:${key}`, value);
        });
    }

    return translation;
}

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
    router.visit(route('search.index'), {
        method: 'get',
        data: {
            q: isbn,
            type: 'isbn'
        }
    });
}

function searchByPublisher(publisher) {
    router.visit(route('search.index'), {
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
                {{ t('pagination', { current: pagination.currentPage, total: pagination.totalPages }) }}
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <div v-for="book in books.items" :key="book.id"
                class="bg-white rounded-lg shadow-soft p-4 hover:shadow-book transition-all duration-200 border border-gray-100">
                <div class="w-full h-48 mb-4 rounded overflow-hidden bg-gray-50 flex items-center justify-center">
                    <img v-if="book.volumeInfo.imageLinks?.thumbnail"
                        :src="book.volumeInfo.imageLinks.thumbnail.replace('http://', 'https://')"
                        :alt="book.volumeInfo.title" class="w-full h-full object-contain"
                        @error="$event.target.style.display = 'none'; $event.target.nextElementSibling.style.display = 'flex'" />
                    <div v-else class="flex flex-col items-center justify-center text-gray-400 p-4 text-center">
                        <Icon name="menu_book" class="w-12 h-12 mb-2" />
                        <span class="text-xs font-medium">{{ truncateTitle(book.volumeInfo.title) }}</span>
                    </div>
                </div>

                <h3 class="font-semibold text-lg mb-2 line-clamp-2 text-gray-800">
                    {{ book.volumeInfo.title }}
                </h3>

                <searchByAuthor v-if="book.volumeInfo.authors && book.volumeInfo.authors.length > 0"
                    :authors="book.volumeInfo.authors" />

                <div v-if="book.volumeInfo.industryIdentifiers && book.volumeInfo.industryIdentifiers.length > 0"
                    class="text-gray-500 text-sm mb-2">
                    <template v-for="(identifier, index) in book.volumeInfo.industryIdentifiers" :key="index">
                        <span class="inline-flex items-center">
                            <span class="text-gray-400 mr-1">{{ identifier.type }}:</span>
                            <Button @click="searchByISBN(identifier.identifier)" variant="link" :shadow="false"
                                size="xs">
                                {{ identifier.identifier }}
                            </Button>
                        </span>
                        <span v-if="index < book.volumeInfo.industryIdentifiers.length - 1"
                            class="text-gray-400 mx-2">•</span>
                    </template>
                </div>

                <div v-if="book.volumeInfo.publisher" class="text-gray-500 text-sm mb-2">
                    <span class="text-gray-400">{{ t('publisher') }}:</span>
                    <Button @click="searchByPublisher(book.volumeInfo.publisher)" variant="link" :shadow="false"
                        size="xs">
                        {{ book.volumeInfo.publisher }}
                    </Button>
                    <span v-if="book.volumeInfo.publishedDate" class="text-gray-400 ml-2">({{
                        book.volumeInfo.publishedDate }})</span>
                </div>

                <SearchBySubject v-if="book.volumeInfo.categories && book.volumeInfo.categories.length > 0"
                    :categories="book.volumeInfo.categories" :current-page="pagination.currentPage" />

                <p v-if="book.volumeInfo.description" class="text-gray-600 text-sm mb-4 line-clamp-3"
                    v-html="book.volumeInfo.description">
                </p>

                <div class="mt-auto pt-4 border-t border-gray-200">
                    <div class="flex flex-col space-y-3">
                        <AddToLibraryButton :book="book" :user-books="userBooks" :show-remove="showRemove"
                            :show-sync="showSync" />

                        <div class="flex justify-center space-x-3">
                            <Button :href="`/books/${book.id}`" variant="primary" size="sm" left-icon="menu_book">
                                {{ t('details_button') }}
                            </Button>

                            <Button v-if="book.volumeInfo.previewLink" :href="book.volumeInfo.previewLink"
                                target="_blank" variant="success" size="sm" left-icon="visibility">
                                {{ t('preview_button') }}
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-else-if="showNoResults" class="text-center py-12">
        <div class="text-gray-500 text-lg">{{ t('no_results') }}</div>
        <p class="text-gray-400 mt-2">{{ t('adjust_search') }}</p>
    </div>

</template>
