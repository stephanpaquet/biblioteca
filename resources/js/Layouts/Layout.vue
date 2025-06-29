<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { computed, getCurrentInstance } from 'vue';
import { usePage } from '@inertiajs/inertia-vue3';
import LanguageSwitcher from '../Components/LanguageSwitcher.vue';
import { useAuthStore } from '../stores/auth';
import { useTranslationsStore } from '../stores/translations';

const page = usePage();

const authStore = useAuthStore();
const translationsStore = useTranslationsStore();

// Get route function from global properties or window
const route = getCurrentInstance()?.appContext.config.globalProperties.route || window.route;

const user = computed(() => authStore.user);
const translations = computed(() => translationsStore.translations);

function logout() {
  Inertia.post(route('logout'));
  authStore.setUser(null)
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
            <Link :href="route('home')" class="text-2xl font-bold text-blue-600">
              {{ page.props.translations?.layout?.brand || 'Biblioteca' }}
            </Link>
          </div>

          <!-- Navigation Links -->
          <div class="hidden md:flex items-center space-x-6">
            <Link :href="route('home')" class="text-gray-700 hover:text-blue-600 transition-colors" :class="{ 'font-bold underline': page.url === '/' }" :aria-current="page.url === '/' ? 'page' : null">
              {{ page.props.translations?.layout?.nav?.home || 'Home' }}
            </Link>
            <Link :href="route('library')" class="text-gray-700 hover:text-blue-600 transition-colors" :class="{ 'font-bold underline': page.url === '/library' }" :aria-current="page.url === '/library' ? 'page' : null">
              {{ page.props.translations?.layout?.nav?.library || 'My Library' }}
            </Link>
            <Link :href="route('dashboard')" class="text-gray-700 hover:text-blue-600 transition-colors" :class="{ 'font-bold underline': page.url === '/dashboard' }" :aria-current="page.url === '/dashboard' ? 'page' : null">
              {{ translations?.layout?.nav?.dashboard || 'Dashboard' }}
            </Link>
          </div>

          <!-- Right side: Language Switcher & Auth -->
          <div class="flex items-center space-x-4">
            <LanguageSwitcher
              v-if="page.props.locale"
              :locale="page.props.locale"
              :supported-locales="page.props.supportedLocales || ['en']"
            />

            <!-- Auth buttons -->
            <div class="flex items-center space-x-2">
              <template v-if="user">
                <span>
                  {{ (page.props.translations?.layout?.auth?.hi || 'Hi, :name').replace(':name', user.name) }}
                </span>
                <button @click="logout" class="cursor-pointer text-gray-400 hover:text-gray-800 underline ml-2">
                  {{ translations?.layout?.auth?.logout || 'Logout' }}
                </button>
              </template>
              <template v-else>
                <Link :href="route('login')" class="text-blue-600 hover:text-blue-800 px-3 py-1 rounded transition-colors">
                  {{ translations?.layout?.auth?.login || 'Login' }}
                </Link>
                <Link :href="route('register')" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded transition-colors">
                  {{ page.props.translations?.layout?.auth?.register || 'Register' }}
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
        <p>{{ page.props.translations?.layout?.footer?.copyright || '© 2024 Biblioteca. All rights reserved.' }}</p>
      </div>
    </footer>
  </div>
</template>
