<script setup>
import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
  <nav class="bg-gray-800 p-4 mb-8">
    <div class="container mx-auto flex justify-between items-center">
      <Link href="/" class="text-white font-bold text-xl hover:underline" :class="{ 'underline': $page.url === '/' }">
        Biblioteca
      </Link>
      <div class="space-x-4">
        <Link href="/" class="text-gray-300 hover:text-white" :class="{ 'font-bold underline': $page.url === '/' }" :aria-current="$page.url === '/' ? 'page' : null">Home</Link>
        <Link href="/contact" class="text-gray-300 hover:text-white" :class="{ 'font-bold underline': $page.url === '/contact' }" :aria-current="$page.url === '/contact' ? 'page' : null">Contact</Link>
      </div>
    </div>
  </nav>
  <slot />
</template>
