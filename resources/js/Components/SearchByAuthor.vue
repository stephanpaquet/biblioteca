<script setup>
import { Inertia } from '@inertiajs/inertia';
import AddToLibraryButton from './AddToLibraryButton.vue';
import BookPlaceholder from './BookPlaceholder.vue';

defineProps({
    authors: {
        type: Array,
        default: () => []
    },
});

function searchByAuthor(author) {
    Inertia.visit(route('search.index'), {
        method: 'get',
        data: {
            q: author,
            type: 'author'
        }
    });
}

</script>

<template>
    <div class="text-gray-600 mb-2">
        <template v-for="(author, index) in authors" :key="index">
            <button @click="searchByAuthor(author)"
                class="text-blue-600 hover:text-blue-800 hover:underline transition-colors cursor-pointer">
                {{ author }}
            </button>
            <span v-if="index < authors.length - 1" class="text-gray-600">, </span>
        </template>
    </div>
</template>
