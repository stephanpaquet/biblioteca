<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { ref, onMounted } from 'vue';
import LanguageSwitcher from '../Components/LanguageSwitcher.vue';

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
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
      <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16">
          <!-- Logo/Brand -->
          <div class="flex items-center">
            <a href="/" class="text-2xl font-bold text-blue-600">
              {{ $page.props.translations?.layout?.brand || 'Biblioteca' }}
            </a>
          </div>

          <!-- Navigation Links -->
          <div class="hidden md:flex items-center space-x-6">
            <Link href="/" class="text-gray-700 hover:text-blue-600 transition-colors" :class="{ 'font-bold underline': $page.url === '/' }" :aria-current="$page.url === '/' ? 'page' : null">
              {{ $page.props.translations?.layout?.nav?.home || 'Home' }}
            </Link>
            <Link href="/library" class="text-gray-700 hover:text-blue-600 transition-colors" :class="{ 'font-bold underline': $page.url === '/library' }" :aria-current="$page.url === '/library' ? 'page' : null">
              {{ $page.props.translations?.layout?.nav?.library || 'My Library' }}
            </Link>
            <Link href="/dashboard" class="text-gray-700 hover:text-blue-600 transition-colors" :class="{ 'font-bold underline': $page.url === '/dashboard' }" :aria-current="$page.url === '/dashboard' ? 'page' : null">
              {{ $page.props.translations?.layout?.nav?.dashboard || 'Dashboard' }}
            </Link>
          </div>

          <!-- Right side: Language Switcher & Auth -->
          <div class="flex items-center space-x-4">
            <LanguageSwitcher 
              v-if="$page.props.locale"
              :locale="$page.props.locale" 
              :supported-locales="$page.props.supportedLocales || ['en']" 
            />
            
            <!-- Auth buttons -->
            <div class="flex items-center space-x-2">
              <template v-if="user">
                <span class="text-gray-300">
                  {{ ($page.props.translations?.layout?.auth?.hi || 'Hi, :name').replace(':name', user.name) }}
                </span>
                <button @click="logout" class="text-gray-300 hover:text-white underline ml-2">
                  {{ $page.props.translations?.layout?.auth?.logout || 'Logout' }}
                </button>
              </template>
              <template v-else>
                <Link href="/login" class="text-blue-600 hover:text-blue-800 px-3 py-1 rounded transition-colors">
                  {{ $page.props.translations?.layout?.auth?.login || 'Login' }}
                </Link>
                <Link href="/register" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded transition-colors">
                  {{ $page.props.translations?.layout?.auth?.register || 'Register' }}
                </Link>
              </template>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main>
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
      <div class="container mx-auto px-4 text-center">
        <p>{{ $page.props.translations?.layout?.footer?.copyright || '© 2024 Biblioteca. All rights reserved.' }}</p>
      </div>
    </footer>
  </div>
</template>
