<script setup>
import { ref } from 'vue';

const name = ref('');
const email = ref('');
const password = ref('');
const error = ref(null);

function register() {
  error.value = null;
  fetch('/api/register', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify({ name: name.value, email: email.value, password: password.value })
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
      error.value = err?.message || 'Registration failed.';
    });
}
</script>

<template>
  <div class="max-w-md mx-auto mt-12 p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Register</h1>
    <form @submit.prevent="register">
      <div class="mb-4">
        <label class="block mb-1">Name</label>
        <input v-model="name" type="text" class="border rounded w-full p-2" required />
      </div>
      <div class="mb-4">
        <label class="block mb-1">Email</label>
        <input v-model="email" type="email" class="border rounded w-full p-2" required />
      </div>
      <div class="mb-4">
        <label class="block mb-1">Password</label>
        <input v-model="password" type="password" class="border rounded w-full p-2" required />
      </div>
      <div v-if="error" class="text-red-600 mb-2">{{ error }}</div>
      <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 w-full">Register</button>
    </form>
    <div class="mt-4 text-sm text-center">
      Already have an account? <a href="/login" class="text-blue-600 hover:underline">Login</a>
    </div>
  </div>
</template>
