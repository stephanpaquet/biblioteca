<template>
  <Layout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
              <h1 class="text-2xl font-bold text-gray-900">{{ t('libraries') }}</h1>
              <nav class="flex space-x-4">
                <Link
                  :href="route('admin.index')"
                  :class="[
                    'px-4 py-2 rounded-md text-sm font-medium transition-colors',
                    route().current('admin.index')
                      ? 'bg-blue-100 text-blue-700'
                      : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50',
                  ]"
                >
                  {{ t('users_roles') }}
                </Link>
                <Link
                  :href="route('admin.libraries')"
                  :class="[
                    'px-4 py-2 rounded-md text-sm font-medium transition-colors',
                    route().current('admin.libraries')
                      ? 'bg-blue-100 text-blue-700'
                      : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50',
                  ]"
                >
                  {{ t('libraries') }}
                </Link>
              </nav>
            </div>

            <!-- Users Libraries -->
            <div class="space-y-6">
              <div
                v-for="user in users.data"
                :key="user.id"
                class="bg-white border border-gray-200 rounded-lg p-6"
              >
                <div class="flex items-center justify-between mb-4">
                  <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ user.name }}</h3>
                    <p class="text-sm text-gray-600">{{ user.email }}</p>
                    <div class="mt-1">
                      <span
                        v-for="role in user.roles"
                        :key="role.id"
                        :class="[
                          'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mr-2',
                          getRoleColor(role.name),
                        ]"
                      >
                        {{ role.name }}
                      </span>
                    </div>
                  </div>
                  <div class="text-sm text-gray-500">
                    {{ user.books.length }}
                    {{
                      user.books.length === 1
                        ? translationsStore.translations?.texts?.bookgrid?.book || 'book'
                        : translationsStore.translations?.texts?.bookgrid?.books || 'books'
                    }}
                  </div>
                </div>

                <!-- Books Grid -->
                <div
                  v-if="user.books.length > 0"
                  class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"
                >
                  <div
                    v-for="book in user.books"
                    :key="book.id"
                    class="bg-gray-50 rounded-lg p-4 border border-gray-200"
                  >
                    <div class="flex items-start space-x-3">
                      <img
                        v-if="book.thumbnail"
                        :src="book.thumbnail"
                        :alt="book.title"
                        class="w-12 h-16 object-cover rounded shadow-sm"
                      />
                      <div
                        v-else
                        class="w-12 h-16 bg-gray-200 rounded shadow-sm flex items-center justify-center"
                      >
                        <span class="material-symbols-outlined text-gray-400 text-lg">
                          menu_book
                        </span>
                      </div>
                      <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 truncate">
                          {{ book.title }}
                        </h4>
                        <p class="text-xs text-gray-600 truncate">
                          {{ book.authors }}
                        </p>
                        <div class="mt-2 flex items-center space-x-2">
                          <span
                            :class="[
                              'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium',
                              getStatusColor(book.pivot.status),
                            ]"
                          >
                            <span
                              :class="[
                                'material-symbols-outlined text-xs mr-1',
                                getStatusIcon(book.pivot.status),
                              ]"
                            >
                              {{ getStatusIcon(book.pivot.status) }}
                            </span>
                            {{ formatStatus(book.pivot.status) }}
                          </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                          Added {{ formatDate(book.pivot.created_at) }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- No Books Message -->
                <div v-else class="text-center py-8 text-gray-500">
                  <span class="material-symbols-outlined text-4xl mb-2 block"> library_books </span>
                  <p class="text-sm">
                    {{
                      translationsStore.translations?.texts?.bookgrid?.no_books_user ||
                      "This user hasn't added any books yet."
                    }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-between items-center">
              <div class="text-sm text-gray-700">
                {{ translationsStore.translations?.texts?.navigation?.showing || 'Showing' }}
                {{ users.from }}
                {{ translationsStore.translations?.texts?.navigation?.to || 'to' }}
                {{ users.to }}
                {{ translationsStore.translations?.texts?.navigation?.of || 'of' }}
                {{ users.total }}
                {{ translationsStore.translations?.texts?.navigation?.users || 'users' }}
              </div>
              <div class="flex space-x-2">
                <Link
                  v-if="users.prev_page_url"
                  :href="users.prev_page_url"
                  class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                >
                  {{ translationsStore.translations?.texts?.navigation?.previous || 'Previous' }}
                </Link>
                <Link
                  v-if="users.next_page_url"
                  :href="users.next_page_url"
                  class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                >
                  {{ translationsStore.translations?.texts?.navigation?.next || 'Next' }}
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { onMounted } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import Layout from '@/Layouts/Layout.vue';
import { useTranslationsStore } from '@/stores/translations';

const props = defineProps({
  users: Object,
});

// Initialize translation store
const translationsStore = useTranslationsStore();

onMounted(() => {
  // Set translations from the global store (already loaded by middleware)
  translationsStore.setTranslations({
    texts: usePage().props.translations,
    currentLocale: usePage().props.currentLocale,
    supportedLocales: usePage().props.supportedLocales,
  });
});

// Helper function to get translations
function t(key) {
  const adminTranslations = translationsStore.translations?.texts?.admin;
  const bookgridTranslations = translationsStore.translations?.texts?.bookgrid;

  if (key.includes('.')) {
    const [namespace, k] = key.split('.');
    if (namespace === 'admin' && adminTranslations?.[k]) {
      return adminTranslations[k];
    } else if (namespace === 'bookgrid' && bookgridTranslations?.[k]) {
      return bookgridTranslations[k];
    }
  }

  return adminTranslations?.[key] || key;
}

const getRoleColor = (roleName) => {
  const colors = {
    admin: 'bg-red-100 text-red-800',
    librarian: 'bg-yellow-100 text-yellow-800',
    user: 'bg-green-100 text-green-800',
  };
  return colors[roleName] || 'bg-gray-100 text-gray-800';
};

const getStatusColor = (status) => {
  const colors = {
    want_to_read: 'bg-blue-100 text-blue-800',
    reading: 'bg-orange-100 text-orange-800',
    read: 'bg-green-100 text-green-800',
  };
  return colors[status] || 'bg-gray-100 text-gray-800';
};

const getStatusIcon = (status) => {
  const icons = {
    want_to_read: 'bookmark',
    reading: 'hourglass_empty',
    read: 'check_circle',
  };
  return icons[status] || 'help';
};

const formatStatus = (status) => {
  const bookgridTranslations = translationsStore.translations?.texts?.bookgrid;
  const statuses = bookgridTranslations?.statuses || {
    want_to_read: 'Want to Read',
    reading: 'Reading',
    read: 'Read',
  };
  return statuses[status] || status;
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString(usePage().props.currentLocale || 'en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
};
</script>
