<script setup>
import { Inertia } from '@inertiajs/inertia';
import Layout from '../../Layouts/Layout.vue';
import SearchForm from '../../Components/SearchForm.vue';
import BookGrid from '../../Components/BookGrid.vue';
import Paginator from '../../Components/Paginator.vue';

const props = defineProps({
    query: {
        type: String,
        required: true,
    },
    searchType: {
        type: String,
        default: 'general'
    },
    filters: {
        type: Object,
        default: () => ({})
    },
    results: {
        type: Object,
        default: null
    },
    userBooks: {
        type: Array,
        default: () => []
    },
    pagination: {
        type: Object,
        default: () => ({
            currentPage: 1,
            totalPages: 1,
            totalItems: 0,
            perPage: 20,
            hasNextPage: false,
            hasPrevPage: false,
            startIndex: 0,
            endIndex: 0
        })
    },
    error: {
        type: String,
        default: null
    }
});

const searchTypeLabels = {
    general: 'General',
    isbn: 'ISBN',
    title: 'Title',
    author: 'Author',
    publisher: 'Publisher',
    subject: 'Subject',
    description: 'Description'
};
</script>

<template>
    <Layout>
        <div class="container mx-auto px-4 py-8">
            <!-- Search Form -->
            <div class="mb-8">
                <SearchForm
                    :initial-query="query"
                    :initial-type="searchType"
                />
            </div>

            <!-- Search Results Header -->
            <div v-if="query" class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    Search Results
                </h1>
                <div class="text-sm text-gray-600 space-y-1">
                    <p>
                        <span class="font-medium">Query:</span> "{{ query }}"
                        <span class="ml-2 text-blue-600">({{ searchTypeLabels[searchType] || searchType }})</span>
                    </p>
                    <div v-if="Object.keys(filters).length > 1" class="flex flex-wrap gap-2 mt-2">
                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded" v-if="filters.language">
                            Language: {{ filters.language.toUpperCase() }}
                        </span>
                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded" v-if="filters.publishedAfter">
                            After: {{ filters.publishedAfter }}
                        </span>
                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded" v-if="filters.publishedBefore">
                            Before: {{ filters.publishedBefore }}
                        </span>
                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded" v-if="filters.printType">
                            Type: {{ filters.printType }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <div v-if="error" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-red-800">{{ error }}</p>
                </div>
            </div>

            <!-- Search Results -->
            <div v-if="results && results.items && results.items.length > 0">
                <!-- Results Summary -->
                <div class="mb-4 text-sm text-gray-600">
                    Showing {{ pagination.startIndex + 1 }}-{{ pagination.endIndex }} of {{ pagination.totalItems.toLocaleString() }} results
                </div>

                <!-- Top Paginator -->
                <div class="mb-6">
                    <Paginator
                        :pagination="pagination"
                        :query="query"
                        :search-type="searchType"
                        :filters="filters"
                        :show-summary="false"
                    />
                </div>

                <BookGrid
                    :books="results"
                    :user-books="userBooks"
                    :title="`Search Results for '${query}'`"
                    :show-no-results="false"
                />

                <!-- Bottom Paginator -->
                <div class="mt-8">
                    <Paginator
                        :pagination="pagination"
                        :query="query"
                        :search-type="searchType"
                        :filters="filters"
                        :show-summary="true"
                    />
                </div>
            </div>

            <!-- No Results -->
            <div v-else-if="query && !error" class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No books found</h3>
                <p class="text-gray-600 mb-4">
                    Try adjusting your search terms or filters to find what you're looking for.
                </p>
                <div class="text-sm text-gray-500">
                    <p>Suggestions:</p>
                    <ul class="mt-2 space-y-1">
                        <li>• Check your spelling</li>
                        <li>• Try different search terms</li>
                        <li>• Use a different search type</li>
                        <li>• Remove some filters</li>
                    </ul>
                </div>
            </div>

            <!-- Welcome Message -->
            <div v-else-if="!query" class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-blue-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Search for Books</h3>
                <p class="text-gray-600">
                    Use the search form above to find books by title, author, ISBN, and more.
                </p>
            </div>
        </div>
    </Layout>
</template>
