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
</script>

<template>
    <Layout>
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-8">My Library</h1>

            <div v-if="libraryStore.books.length === 0" class="text-center py-12">
                <p class="text-gray-600 mb-4">Your library is empty</p>
                <a href="/search" class="text-blue-600 hover:underline">Start searching for books</a>
            </div>

            <BookGrid
                v-else
                :books="formattedBooks"
                :user-books="libraryStore.books"
                title="My Library"
                :show-no-results="false"
                :show-remove="true"
            />
        </div>
    </Layout>
</template>
