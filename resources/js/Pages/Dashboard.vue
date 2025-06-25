<script setup>
import Layout from './Layout.vue';
import { ref } from 'vue';

const activeTab = ref('books'); // Tracks the active tab (books, users, analytics)

// Dummy data for demonstration
const books = ref([
  { id: 1, title: 'Book 1', author: 'Author 1', publishedDate: '2023-01-01' },
  { id: 2, title: 'Book 2', author: 'Author 2', publishedDate: '2022-12-01' },
]);

const users = ref([
  { id: 1, name: 'User 1', email: 'user1@example.com' },
  { id: 2, name: 'User 2', email: 'user2@example.com' },
]);

const analytics = ref({
  totalBooks: 100,
  totalUsers: 50,
  monthlyActiveUsers: 20,
});
</script>

<template>
  <Layout>
    <div class="container mx-auto py-8">
      <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>
      <div class="flex gap-4 mb-6">
        <button
          class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700"
          :class="{ 'bg-blue-700': activeTab === 'books' }"
          @click="activeTab = 'books'"
        >
          Manage Books
        </button>
        <button
          class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700"
          :class="{ 'bg-blue-700': activeTab === 'users' }"
          @click="activeTab = 'users'"
        >
          Manage Users
        </button>
        <button
          class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700"
          :class="{ 'bg-blue-700': activeTab === 'analytics' }"
          @click="activeTab = 'analytics'"
        >
          Analytics
        </button>
      </div>

      <div v-if="activeTab === 'books'">
        <h2 class="text-xl font-semibold mb-4">Books</h2>
        <ul>
          <li v-for="book in books" :key="book.id" class="border p-4 rounded mb-2">
            <h3 class="font-bold">{{ book.title }}</h3>
            <p>Author: {{ book.author }}</p>
            <p>Published: {{ book.publishedDate }}</p>
          </li>
        </ul>
      </div>

      <div v-if="activeTab === 'users'">
        <h2 class="text-xl font-semibold mb-4">Users</h2>
        <ul>
          <li v-for="user in users" :key="user.id" class="border p-4 rounded mb-2">
            <h3 class="font-bold">{{ user.name }}</h3>
            <p>Email: {{ user.email }}</p>
          </li>
        </ul>
      </div>

      <div v-if="activeTab === 'analytics'">
        <h2 class="text-xl font-semibold mb-4">Analytics</h2>
        <p>Total Books: {{ analytics.totalBooks }}</p>
        <p>Total Users: {{ analytics.totalUsers }}</p>
        <p>Monthly Active Users: {{ analytics.monthlyActiveUsers }}</p>
      </div>
    </div>
  </Layout>
</template>