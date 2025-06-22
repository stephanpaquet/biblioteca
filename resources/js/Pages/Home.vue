<script setup>
import Layout from './Layout.vue';
import { Head } from '@inertiajs/inertia-vue3';
</script>

<template>
  <Layout>
    <Head
      title="Biblioteca | Modern Digital Library & Book Management"
      meta="[
        { name: 'description', content: 'Welcome to Biblioteca, your modern digital library and book management platform. Discover, manage, and connect with books easily online.' },
        { property: 'og:title', content: 'Biblioteca | Modern Digital Library & Book Management' },
        { property: 'og:description', content: 'Welcome to Biblioteca, your modern digital library and book management platform. Discover, manage, and connect with books easily online.' },
        { property: 'og:type', content: 'website' },
        { property: 'og:locale', content: 'en_US' }
      ]"
    />
    <div class="container mx-auto p-8">
      <h1 class="bg-green-100 p-4 text-4xl font-bold mb-4 text-center">Bonjour</h1>
      <p class="text-lg text-center">This page is rendered using Inertia.js and Vue 3.</p>
    </div>
  </Layout>
</template>
