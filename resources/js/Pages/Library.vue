<script setup>
import { onMounted } from 'vue';
import { ref } from 'vue';
import Layout from '../Layouts/Layout.vue';
import { useTranslationsStore } from '../stores/translations';
import { useAuthStore } from '../stores/auth';
import { useLibraryStore } from '../stores/library';

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
async function updateStatus(bookId, status) {
    try {
        await fetch(route('library.update-status', bookId), {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status })
        });

        // Update local state
        libraryStore.updateBookStatus(bookId, status);
    } catch (error) {
        console.error('Failed to update status:', error);
    }
}

async function removeFromLibrary(bookId) {
    if (confirm('Are you sure you want to remove this book from your library?')) {
        await libraryStore.removeFromLibrary(bookId);
    }
}
</script>

<template>
    <Layout>
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-8">My Library</h1>

            <div v-if="libraryStore.books.length === 0" class="text-center py-12">
                <p class="text-gray-600 mb-4">Your library is empty</p>
                <a href="/search" class="text-blue-600 hover:underline">Start searching for books</a>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <div v-for="book in libraryStore.books" :key="book.id" class="bg-white rounded-lg shadow-md p-4">
                    <img :src="book.thumbnail || '/img/placeholder-book.png'" :alt="book.title"
                        class="w-full h-48 object-cover rounded mb-4" />
                    <h3 class="font-semibold text-lg mb-2 line-clamp-2">{{ book.title }}</h3>
                    <p v-if="book.authors" class="text-gray-600 mb-2">
                        {{ Array.isArray(book.authors) ? book.authors.join(', ') : book.authors }}
                    </p>

                    <div class="flex items-center justify-between mt-4">
                        <select :value="book.pivot.status" @change="updateStatus(book.id, $event.target.value)"
                            class="text-sm border rounded px-2 py-1">
                            <option value="want_to_read">Want to Read</option>
                            <option value="reading">Reading</option>
                            <option value="read">Read</option>
                        </select>

                        <button @click="removeFromLibrary(book.id)" class="text-red-600 hover:text-red-800 text-sm">
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
