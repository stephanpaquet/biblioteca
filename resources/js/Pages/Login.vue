<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { usePage } from '@inertiajs/inertia-vue3';
import Layout from '../Layouts/Layout.vue';
import { useToast } from '../composables/useToast';

const page = usePage();
const toast = useToast();
const email = ref('');
const password = ref('');
const processing = ref(false);

// Get errors from Inertia's error bag
const errors = ref(page.props.errors || {});

function login() {
  processing.value = true;

  // Use Inertia.js post method which automatically handles CSRF tokens
  Inertia.post(route('login'), {
    email: email.value,
    password: password.value,
  }, {
    onFinish: () => {
      processing.value = false;
    },
    onError: (errors) => {
      if (errors.email) {
        toast.loginError();
      }
    },
    onSuccess: () => {
      toast.loginSuccess(page.props.auth?.user?.name || 'User');
    }
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

        <!-- General error messages -->
        <div v-if="$page.props.errors?.email" class="text-red-600 mb-2 text-sm">
          {{ $page.props.errors.email }}
        </div>

        <button
          type="submit"
          :disabled="processing"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full disabled:opacity-50"
        >
          {{ processing ? 'Logging in...' : 'Login' }}
        </button>
      </form>
      <div class="mt-4 text-sm text-center">
        <a href="/register" class="text-blue-600 hover:underline">Register</a> |
        <a href="/password/reset" class="text-blue-600 hover:underline">Forgot Password?</a>
      </div>
    </div>
  </Layout>
</template>
