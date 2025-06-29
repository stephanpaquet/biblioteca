<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { usePage } from '@inertiajs/inertia-vue3';
import Layout from './Layout.vue';

const page = usePage();
const name = ref('');
const email = ref('');
const password = ref('');
const password_confirmation = ref('');
const processing = ref(false);

function register() {
  processing.value = true;

  // Use Inertia.js post method which automatically handles CSRF tokens
  Inertia.post(route('register'), {
    name: name.value,
    email: email.value,
    password: password.value,
    password_confirmation: password_confirmation.value,
  }, {
    onFinish: () => {
      processing.value = false;
    },
    onError: (errors) => {
      console.log('Registration errors:', errors);
    },
    onSuccess: () => {
      console.log('Registration successful');
    }
  });
}
</script>

<template>
  <Layout>
    <div class="max-w-md mx-auto mt-12 p-6 bg-white rounded shadow">
      <h1 class="text-2xl font-bold mb-4">Register</h1>
      <form @submit.prevent="register">
        <div class="mb-4">
          <label class="block mb-1">Name</label>
          <input
            v-model="name"
            type="text"
            class="border rounded w-full p-2"
            :class="{ 'border-red-500': $page.props.errors?.name }"
            required
          />
          <div v-if="$page.props.errors?.name" class="text-red-600 text-sm mt-1">
            {{ $page.props.errors.name }}
          </div>
        </div>
        <div class="mb-4">
          <label class="block mb-1">Email</label>
          <input
            v-model="email"
            type="email"
            class="border rounded w-full p-2"
            :class="{ 'border-red-500': $page.props.errors?.email }"
            required
          />
          <div v-if="$page.props.errors?.email" class="text-red-600 text-sm mt-1">
            {{ $page.props.errors.email }}
          </div>
        </div>
        <div class="mb-4">
          <label class="block mb-1">Password</label>
          <input
            v-model="password"
            type="password"
            class="border rounded w-full p-2"
            :class="{ 'border-red-500': $page.props.errors?.password }"
            required
          />
          <div v-if="$page.props.errors?.password" class="text-red-600 text-sm mt-1">
            {{ $page.props.errors.password }}
          </div>
        </div>
        <div class="mb-4">
          <label class="block mb-1">Confirm Password</label>
          <input
            v-model="password_confirmation"
            type="password"
            class="border rounded w-full p-2"
            required
          />
        </div>
        <button
          type="submit"
          :disabled="processing"
          class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 w-full disabled:opacity-50"
        >
          {{ processing ? 'Creating Account...' : 'Register' }}
        </button>
      </form>
      <div class="mt-4 text-sm text-center">
        Already have an account? <a href="/login" class="text-blue-600 hover:underline">Login</a>
      </div>
    </div>
  </Layout>
</template>
