<script setup>
import Layout from '../../Layouts/Layout.vue';
import BookGrid from '../../Components/BookGrid.vue';

defineProps({
    query: {
        type: String,
        required: true,
    },
    results: {
        type: Object,
        required: true,
    },
    userBooks: {
        type: Array,
        default: () => []
    },
});

</script>

<template>
    <Layout>
        <h1>Search result for {{ query }}</h1>
        <BookGrid :books="results" :user-books="userBooks" title="a title" :show-no-results="true" />
    </Layout>
</template>
