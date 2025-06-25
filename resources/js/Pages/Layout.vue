<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { ref, onMounted } from 'vue';

const user = ref(null);

onMounted(async () => {
  try {
    const res = await fetch('/api/user', {
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
      },
    });
    if (res.ok) {
      user.value = await res.json();
    }
  } catch (e) {
    user.value = null;
  }
});

function logout() {
  fetch('/api/logout', {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Authorization': `Bearer ${localStorage.getItem('token')}`,
    },
  }).then(() => {
    localStorage.removeItem('token');
    window.location.reload();
  });
}
</script>

<template>
  <nav class="bg-gray-800 p-4 mb-8">
    <div class="container mx-auto flex justify-between items-center">
      <Link href="/" class="text-white font-bold text-xl hover:underline" :class="{ 'underline': $page.url === '/' }">
        Biblioteca
      </Link>
      <div class="space-x-4 flex items-center">
        <Link href="/" class="text-gray-300 hover:text-white" :class="{ 'font-bold underline': $page.url === '/' }" :aria-current="$page.url === '/' ? 'page' : null">Home</Link>
        <Link href="/dashboard" class="text-gray-300 hover:text-white" :class="{ 'font-bold underline': $page.url === '/dashboard' }" :aria-current="$page.url === '/contact' ? 'page' : null">Dashboard</Link>
        <template v-if="user">
          <span class="text-gray-300">Hi, {{ user.name }}</span>
          <button @click="logout" class="text-gray-300 hover:text-white underline ml-2">Logout</button>
        </template>
        <template v-else>
          <Link href="/login" class="text-gray-300 hover:text-white">Login</Link>
          <Link href="/register" class="text-gray-300 hover:text-white">Register</Link>
        </template>
      </div>
    </div>
  </nav>
  <slot />
  <footer class="bg-gray-100 text-gray-500 text-xs py-4 mt-12 border-t">
    <div class="container mx-auto flex flex-col md:flex-row justify-between items-center gap-2 px-4">
      <span>
        Biblioteca &copy; {{ new Date().getFullYear() }}
      </span>
    </div>
  </footer>
</template>
