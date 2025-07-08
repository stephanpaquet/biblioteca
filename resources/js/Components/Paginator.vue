<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
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
      endIndex: 0,
    }),
  },
  query: {
    type: String,
    required: true,
  },
  searchType: {
    type: String,
    default: 'general',
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
  showSummary: {
    type: Boolean,
    default: true,
  },
});

function goToPage(page) {
  const filtersCopy = { ...props.filters };
  delete filtersCopy['page']; // Ensure 'page' is not in filters
  const searchParams = {
    q: props.query,
    type: props.searchType,
    page,
    ...filtersCopy,
  };

  // Remove empty filters
  Object.keys(searchParams).forEach((key) => {
    if (searchParams[key] === '' || searchParams[key] === null || searchParams[key] === undefined) {
      delete searchParams[key];
    }
  });

  router.get('/search', searchParams, {
    preserveState: false,
    preserveScroll: false,
  });
}

function nextPage() {
  if (props.pagination.hasNextPage) {
    goToPage(props.pagination.currentPage + 1);
  }
}

function prevPage() {
  if (props.pagination.hasPrevPage) {
    goToPage(props.pagination.currentPage - 1);
  }
}

function getVisiblePages() {
  const current = props.pagination.currentPage;
  const total = props.pagination.totalPages;
  const delta = 2; // Number of pages to show on each side of current page

  if (total <= 7) {
    // If total pages <= 7, show all pages
    return Array.from({ length: total }, (_, i) => i + 1);
  }

  const pages = [];

  // Always show first page
  pages.push(1);

  // Calculate range around current page
  const start = Math.max(2, current - delta);
  const end = Math.min(total - 1, current + delta);

  // Add ellipsis after first page if needed
  if (start > 2) {
    pages.push('...');
  }

  // Add pages around current page
  for (let i = start; i <= end; i++) {
    pages.push(i);
  }

  // Add ellipsis before last page if needed
  if (end < total - 1) {
    pages.push('...');
  }

  // Always show last page (if more than 1 page)
  if (total > 1) {
    pages.push(total);
  }

  return pages;
}
</script>

<template>
  <div v-if="pagination.totalPages > 1" class="flex items-center justify-between">
    <div class="flex-1 flex justify-between sm:hidden">
      <!-- Mobile pagination -->
      <button
        :disabled="!pagination.hasPrevPage"
        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
        @click="prevPage"
      >
        Previous
      </button>
      <button
        :disabled="!pagination.hasNextPage"
        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
        @click="nextPage"
      >
        Next
      </button>
    </div>

    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
      <div v-if="showSummary">
        <p class="text-sm text-gray-700">
          Showing <span class="font-medium">{{ pagination.startIndex + 1 }}</span> to
          <span class="font-medium">{{ pagination.endIndex }}</span> of
          <span class="font-medium">{{ pagination.totalItems.toLocaleString() }}</span> results
        </p>
      </div>
      <div>
        <nav
          class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
          aria-label="Pagination"
        >
          <!-- Previous button -->
          <button
            :disabled="!pagination.hasPrevPage"
            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            @click="prevPage"
          >
            <span class="sr-only">Previous</span>
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
              <path
                fill-rule="evenodd"
                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                clip-rule="evenodd"
              />
            </svg>
          </button>

          <!-- Page numbers -->
          <template v-for="page in getVisiblePages()" :key="page">
            <button
              v-if="page === '...'"
              disabled
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 cursor-default"
            >
              ...
            </button>
            <button
              v-else
              :class="[
                'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                pagination.currentPage === page
                  ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                  : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
              ]"
              @click="goToPage(page)"
            >
              {{ page }}
            </button>
          </template>

          <!-- Next button -->
          <button
            :disabled="!pagination.hasNextPage"
            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            @click="nextPage"
          >
            <span class="sr-only">Next</span>
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
              <path
                fill-rule="evenodd"
                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                clip-rule="evenodd"
              />
            </svg>
          </button>
        </nav>
      </div>
    </div>
  </div>
</template>
