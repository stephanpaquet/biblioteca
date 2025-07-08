<script setup>
import Layout from '../Layouts/Layout.vue';
import SearchForm from '../Components/SearchForm.vue';
import BookGrid from '../Components/BookGrid.vue';

defineProps({
  books: Object,
  query: String,
  userBooks: {
    type: Array,
    default: () => [],
  },
});
</script>

<template>
  <Layout>
    <div class="container mx-auto px-4 py-8">
      <div class="mb-8">
        <SearchForm :initial-query="query" />
      </div>

      <BookGrid
        :books="books"
        :user-books="userBooks"
        :title="`Search Results for '${query}'`"
        :show-no-results="true"
      />
    </div>
  </Layout>
</template>
