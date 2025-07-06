<script setup>
import { onMounted, computed } from 'vue';
import { ref } from 'vue';
import Layout from '../Layouts/Layout.vue';
import { useTranslationsStore } from '../stores/translations';
import { useAuthStore } from '../stores/auth';
import { useLibraryStore } from '../stores/library';
import BookGrid from '../Components/BookGrid.vue';

const props = defineProps({
    books: {
        type: Array,
        required: true
    },
    auth: {
        type: Object,
        default: null
    },
    translations: {
        type: Object,
        required: true
    },
    supportedLocales: {
        type: Array,
        default: () => ['en']
    },
    currentLocale: {
        type: String,
        default: 'en'
    }
});

const authStore = useAuthStore();
const translationsStore = useTranslationsStore();
const libraryStore = useLibraryStore();

onMounted(() => {
    translationsStore.setTranslations({
        texts: props.translations,
        currentLocale: props.currentLocale,
        supportedLocales: props.supportedLocales,
    });

    authStore.setUser(props.auth.user || null);

    // Initialize library store with books from props
    libraryStore.setBooks(props.books);
});

// Transform library books to match Google Books API format for BookGrid
const formattedBooks = computed(() => {
    if (!libraryStore.books || libraryStore.books.length === 0) {
        return { items: [], totalItems: 0 };
    }

    const items = libraryStore.books.map(book => ({
        id: book.google_book_id,
        volumeInfo: {
            title: book.title,
            authors: Array.isArray(book.authors) ? book.authors : (book.authors ? [book.authors] : []),
            description: book.description,
            imageLinks: book.thumbnail ? { thumbnail: book.thumbnail } : null,
            publishedDate: book.published_date,
            pageCount: book.page_count,
            language: book.language,
            previewLink: book.preview_link,
            publisher: book.publisher || null,
            categories: book.categories || [],
            industryIdentifiers: book.isbn ? [{ type: 'ISBN', identifier: book.isbn }] : []
        }
    }));

    return {
        items,
        totalItems: items.length
    };
});

async function syncAllBooks() {
    if (confirm('This will update all your books with the latest data from Google Books. Continue?')) {
        const bookIds = libraryStore.books.map(book => book.google_book_id);

        for (const googleBookId of bookIds) {
            try {
                await libraryStore.syncBookWithGoogleApi(googleBookId);
                // Add a small delay to avoid overwhelming the API
                await new Promise(resolve => setTimeout(resolve, 100));
            } catch (error) {
                console.error('Failed to sync book:', googleBookId, error);
            }
        }
    }
}
</script>

<template>
    <Layout>
        <div class="container mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8 space-y-4 md:space-y-0">
                <h1 class="text-3xl font-bold text-gray-900">My Library</h1>
                <div class="flex items-center space-x-4">
                    <div v-if="libraryStore.books.length > 0" class="text-sm text-gray-600">
                        {{ libraryStore.books.length }} book{{ libraryStore.books.length !== 1 ? 's' : '' }}
                    </div>
                    <button
                        v-if="libraryStore.books.length > 0"
                        @click="syncAllBooks"
                        :disabled="libraryStore.isLoading"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 disabled:bg-primary-400 text-primary-600 font-medium rounded-md transition-colors space-x-2 shadow-sm"
                    >
                        <svg :class="libraryStore.isLoading ? 'animate-spin' : ''" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>{{ libraryStore.isLoading ? 'Syncing...' : 'Sync All Books' }}</span>
                    </button>
                </div>
            </div>

            <div v-if="libraryStore.books.length === 0" class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Your library is empty</h3>
                    <p class="text-gray-600 mb-6">Start building your personal library by adding books from search results.</p>
                    <a href="/search" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-md transition-colors shadow-sm">
                        🔍 Start searching for books
                    </a>
                </div>
            </div>

            <BookGrid
                v-else
                :books="formattedBooks"
                :user-books="libraryStore.books"
                title="My Library"
                :show-no-results="false"
                :show-remove="true"
                :show-sync="true"
            />
        </div>
    </Layout>
</template>
