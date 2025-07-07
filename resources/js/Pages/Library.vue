<script setup>
import { onMounted, computed } from 'vue';
import { ref } from 'vue';
import Layout from '../Layouts/Layout.vue';
import { useTranslationsStore } from '../stores/translations';
import { useAuthStore } from '../stores/auth';
import { useLibraryStore } from '../stores/library';
import BookGrid from '../Components/BookGrid.vue';
import Button from '../Components/Button.vue';

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

// Local search/filter state
const searchQuery = ref('');
const selectedCategory = ref('');
const selectedStatus = ref('');
const sortBy = ref('title'); // title, author, dateAdded
const sortOrder = ref('asc'); // asc, desc

const availableCategories = computed(() => {
    const categories = new Set();
    libraryStore.books.forEach(book => {
        if (book.categories && Array.isArray(book.categories)) {
            book.categories.forEach(cat => categories.add(cat));
        }
    });
    return Array.from(categories).sort();
});

// Filter and sort books based on search criteria
const filteredBooks = computed(() => {
    let filtered = [...libraryStore.books];

    // Apply text search
    if (searchQuery.value.trim()) {
        const query = searchQuery.value.toLowerCase().trim();
        filtered = filtered.filter(book => {
            return (
                book.title?.toLowerCase().includes(query) ||
                (Array.isArray(book.authors)
                    ? book.authors.some(author => author.toLowerCase().includes(query))
                    : book.authors?.toLowerCase().includes(query)) ||
                book.description?.toLowerCase().includes(query) ||
                book.publisher?.toLowerCase().includes(query) ||
                book.isbn?.toLowerCase().includes(query)
            );
        });
    }

    // Apply category filter
    if (selectedCategory.value) {
        filtered = filtered.filter(book =>
            book.categories && book.categories.includes(selectedCategory.value)
        );
    }

    // Apply status filter
    if (selectedStatus.value) {
        filtered = filtered.filter(book =>
            book.pivot?.status === selectedStatus.value
        );
    }

    // Apply sorting
    filtered.sort((a, b) => {
        let aValue, bValue;

        switch (sortBy.value) {
            case 'author':
                aValue = Array.isArray(a.authors) ? a.authors[0] : a.authors || '';
                bValue = Array.isArray(b.authors) ? b.authors[0] : b.authors || '';
                break;
            case 'dateAdded':
                aValue = new Date(a.pivot?.created_at || a.created_at || 0);
                bValue = new Date(b.pivot?.created_at || b.created_at || 0);
                break;
            case 'title':
            default:
                aValue = a.title || '';
                bValue = b.title || '';
                break;
        }

        if (sortBy.value === 'dateAdded') {
            return sortOrder.value === 'asc' ? aValue - bValue : bValue - aValue;
        } else {
            const comparison = aValue.localeCompare(bValue);
            return sortOrder.value === 'asc' ? comparison : -comparison;
        }
    });

    return filtered;
});

// Transform filtered books to match Google Books API format for BookGrid
const formattedBooks = computed(() => {
    if (!filteredBooks.value || filteredBooks.value.length === 0) {
        return { items: [], totalItems: 0 };
    }

    const items = filteredBooks.value.map(book => ({
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

function clearFilters() {
    searchQuery.value = '';
    selectedCategory.value = '';
    selectedStatus.value = '';
    sortBy.value = 'title';
    sortOrder.value = 'asc';
}

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
                    <Button
                        v-if="libraryStore.books.length > 0"
                        @click="syncAllBooks"
                        :disabled="libraryStore.isLoading"
                        :loading="libraryStore.isLoading"
                        variant="primary"
                        left-icon="sync"
                    >
                        {{ libraryStore.isLoading ? 'Syncing...' : 'Sync All Books' }}
                    </Button>
                </div>
            </div>

            <!-- Search and Filter Controls -->
            <div v-if="libraryStore.books.length > 0" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex flex-col space-y-4">
                    <!-- Search Bar -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search your library by title, author, publisher, or ISBN..."
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                        />
                    </div>

                    <!-- Filters and Sort -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Category Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select
                                v-model="selectedCategory"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                            >
                                <option value="">All Categories</option>
                                <option v-for="category in availableCategories" :key="category" :value="category">
                                    {{ category }}
                                </option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select
                                v-model="selectedStatus"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                            >
                                <option value="">All Statuses</option>
                                <option value="to_read">To Read</option>
                                <option value="reading">Reading</option>
                                <option value="read">Read</option>
                                <option value="favorite">Favorite</option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sort by</label>
                            <select
                                v-model="sortBy"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                            >
                                <option value="title">Title</option>
                                <option value="author">Author</option>
                                <option value="dateAdded">Date Added</option>
                            </select>
                        </div>

                        <!-- Sort Order -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                            <select
                                v-model="sortOrder"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                            >
                                <option value="asc">Ascending</option>
                                <option value="desc">Descending</option>
                            </select>
                        </div>
                    </div>

                    <!-- Clear Filters Button -->
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            Showing {{ filteredBooks.length }} of {{ libraryStore.books.length }} books
                        </div>
                        <Button
                            v-if="searchQuery || selectedCategory || selectedStatus || sortBy !== 'title' || sortOrder !== 'asc'"
                            @click="clearFilters"
                            variant="outline"
                            size="sm"
                            left-icon="x"
                        >
                            Clear Filters
                        </Button>
                    </div>
                </div>
            </div>

            <div v-if="libraryStore.books.length === 0" class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Your library is empty</h3>
                    <p class="text-gray-600 mb-6">Start building your personal library by adding books from search results.</p>
                    <Button
                        href="/search"
                        variant="primary"
                        left-icon="search"
                    >
                        🔍 Start searching for books
                    </Button>
                </div>
            </div>

            <!-- No filtered results message -->
            <div v-if="libraryStore.books.length > 0 && filteredBooks.length === 0" class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.291-1.007-5.824-2.562M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No books found</h3>
                    <p class="text-gray-600 mb-6">No books match your current search and filter criteria.</p>
                    <Button
                        @click="clearFilters"
                        variant="secondary"
                        left-icon="x"
                    >
                        Clear All Filters
                    </Button>
                </div>
            </div>

            <BookGrid
                v-else-if="libraryStore.books.length > 0"
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
