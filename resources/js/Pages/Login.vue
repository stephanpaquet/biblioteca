<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Layout from './Layout.vue';

const email = ref('');
const password = ref('');
const error = ref(null);

function login() {
  error.value = null;
  fetch('/api/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify({ email: email.value, password: password.value })
  })
    .then(async res => {
      if (!res.ok) throw await res.json();
      return res.json();
    })
    .then(data => {
      localStorage.setItem('token', data.token);
      window.location.href = '/';
    })
    .catch(async err => {
      error.value = err?.message || 'Login failed.';
    });
}
</script>

<template>
  <Layout>
    <div class="max-w-md mx-auto mt-12 p-6 bg-white rounded shadow">
      <h1 class="text-2xl font-bold mb-4">Login</h1>
      <form @submit.prevent="login">
        <div class="mb-4">
          <label class="block mb-1">Email</label>
          <input v-model="email" type="email" class="border rounded w-full p-2" required />
        </div>
        <div class="mb-4">
          <label class="block mb-1">Password</label>
          <input v-model="password" type="password" class="border rounded w-full p-2" required />
        </div>
        <div v-if="error" class="text-red-600 mb-2">{{ error }}</div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full">Login</button>
      </form>
      <div class="mt-4 text-sm text-center">
        <a href="/register" class="text-blue-600 hover:underline">Register</a> |
        <a href="/password/reset" class="text-blue-600 hover:underline">Forgot Password?</a>
      </div>
    </div>
  </Layout>
</template>
