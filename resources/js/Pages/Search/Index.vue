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
});

</script>

<template>
    <Layout>
        <h1>Search result for {{ query }}</h1>
        <BookGrid
        :books="result"
        :user-books="userBooks"
        title="a title"
        :show-no-results="true"
        :translations="translations" />
    </Layout>
</template>
