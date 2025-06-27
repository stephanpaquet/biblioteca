import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useSearchStore = defineStore('search', () => {
    const query = ref('')
    const results = ref(null)
    const isLoading = ref(false)
    const error = ref(null)
    const searchHistory = ref([])

    function setQuery(searchQuery) {
        query.value = searchQuery
        if (searchQuery && !searchHistory.value.includes(searchQuery)) {
            searchHistory.value.unshift(searchQuery)
            // Keep only last 5 searches
            if (searchHistory.value.length > 5) {
                searchHistory.value = searchHistory.value.slice(0, 5)
            }
        }
    }

    function setResults(searchResults) {
        results.value = searchResults
    }

    function setLoading(loading) {
        isLoading.value = loading
    }

    function setError(errorMessage) {
        error.value = errorMessage
    }

    function clearResults() {
        results.value = null
        query.value = ''
        error.value = null
    }

    return {
        query,
        results,
        isLoading,
        error,
        searchHistory,
        setQuery,
        setResults,
        setLoading,
        setError,
        clearResults
    }
})
