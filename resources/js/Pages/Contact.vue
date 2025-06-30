<script setup>
import Layout from '../Layouts/Layout.vue';
import { Head } from '@inertiajs/inertia-vue3';
</script>

<template>
  <Layout>
    <Head
      title="Contact the Biblioteca Team | Get in Touch for Support or Questions"
      meta="[
        { name: 'description', content: 'Contact the Biblioteca team for support, questions, or feedback about our digital library and book management platform.' },
        { property: 'og:title', content: 'Contact the Biblioteca Team | Get in Touch for Support or Questions' },
        { property: 'og:description', content: 'Contact the Biblioteca team for support, questions, or feedback about our digital library and book management platform.' },
        { property: 'og:type', content: 'website' },
        { property: 'og:locale', content: 'en_US' }
      ]"
    />
    <div class="container mx-auto p-8">
      <h1 class="bg-blue-100 p-4 text-3xl font-bold mb-4 text-center">Contact Us</h1>
      <form class="max-w-md mx-auto bg-white p-6 rounded shadow">
        <div class="mb-4">
          <label class="block mb-1 font-semibold" for="name">Name</label>
          <input class="w-full border rounded px-3 py-2" id="name" type="text" placeholder="Your Name" />
        </div>
        <div class="mb-4">
          <label class="block mb-1 font-semibold" for="email">Email</label>
          <input class="w-full border rounded px-3 py-2" id="email" type="email" placeholder="Your Email" />
        </div>
        <div class="mb-4">
          <label class="block mb-1 font-semibold" for="message">Message</label>
          <textarea class="w-full border rounded px-3 py-2" id="message" rows="4" placeholder="Your Message"></textarea>
        </div>
        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" type="submit">Send</button>
      </form>
    </div>
  </Layout>
</template>
