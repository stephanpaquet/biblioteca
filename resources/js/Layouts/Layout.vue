<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import { computed, getCurrentInstance, ref } from 'vue';
import LanguageSwitcher from '../Components/LanguageSwitcher.vue';
import { useAuthStore } from '../stores/auth';
import { useTranslationsStore } from '../stores/translations';
import { useLibraryStore } from '../stores/library';

const page = usePage();

const authStore = useAuthStore();
const translationsStore = useTranslationsStore();
const libraryStore = useLibraryStore();

// Mobile menu state
const isMobileMenuOpen = ref(false);

// Get route function from global properties or window
const route = getCurrentInstance()?.appContext.config.globalProperties.route || window.route;

const user = computed(() => authStore.user);
const translations = computed(() => translationsStore.translations.texts);

function logout() {
  router.post(route('logout'));
  authStore.setUser(null);
}

function toggleMobileMenu() {
  isMobileMenuOpen.value = !isMobileMenuOpen.value;
}

function closeMobileMenu() {
  isMobileMenuOpen.value = false;
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
            <Link :href="route('home')" class="text-xl sm:text-2xl font-bold text-blue-600">
              {{ translations?.layout?.brand || 'Biblioteca' }}
            </Link>
          </div>

          <!-- Desktop Navigation Links -->
          <div class="hidden md:flex items-center space-x-6">
            <Link
              :href="route('home')"
              class="text-gray-700 hover:text-blue-600 transition-colors"
              :class="{ 'font-bold underline': page.url === '/' }"
              :aria-current="page.url === '/' ? 'page' : null"
            >
              {{ translations?.layout?.nav?.home || 'Home' }}
            </Link>
            <Link
              :href="route('library')"
              class="text-gray-700 hover:text-blue-600 transition-colors"
              :class="{ 'font-bold underline': page.url === '/library' }"
              :aria-current="page.url === '/library' ? 'page' : null"
            >
              {{ translations?.layout?.nav?.library || 'My Library' }}
              <span v-if="user" class="ml-1">({{ libraryStore.bookCount }})</span>
            </Link>
            <Link
              :href="route('dashboard')"
              class="text-gray-700 hover:text-blue-600 transition-colors"
              :class="{ 'font-bold underline': page.url === '/dashboard' }"
              :aria-current="page.url === '/dashboard' ? 'page' : null"
            >
              {{ translations?.layout?.nav?.dashboard || 'Dashboard' }}
            </Link>
          </div>

          <!-- Right side: Language Switcher & Auth (Desktop) -->
          <div class="hidden md:flex items-center space-x-4">
            <LanguageSwitcher />

            <!-- Auth buttons -->
            <div class="flex items-center space-x-2">
              <template v-if="user">
                <span class="text-sm lg:text-base">
                  {{ (translations?.layout?.auth?.hi || 'Hi, :name').replace(':name', user.name) }}
                </span>
                <button
                  class="cursor-pointer text-gray-400 hover:text-gray-800 underline ml-2 text-sm lg:text-base"
                  @click="logout"
                >
                  {{ translations?.layout?.auth?.logout || 'Logout' }}
                </button>
              </template>
              <template v-else>
                <Link
                  :href="route('login')"
                  class="text-blue-600 hover:text-blue-800 px-3 py-1 rounded transition-colors text-sm lg:text-base"
                >
                  {{ translations?.layout?.auth?.login || 'Login' }}
                </Link>
                <Link
                  :href="route('register')"
                  class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded transition-colors text-sm lg:text-base"
                >
                  {{ translations?.layout?.auth?.register || 'Register' }}
                </Link>
              </template>
            </div>
          </div>

          <!-- Mobile menu button -->
          <div class="md:hidden flex items-center space-x-2">
            <LanguageSwitcher />
            <button
              @click="toggleMobileMenu"
              class="text-gray-700 hover:text-blue-600 focus:outline-none focus:text-blue-600 transition-colors"
              :aria-expanded="isMobileMenuOpen"
              aria-label="Toggle navigation menu"
            >
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path v-if="!isMobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div v-show="isMobileMenuOpen" class="md:hidden border-t bg-white">
          <div class="px-2 pt-2 pb-3 space-y-1">
            <Link
              :href="route('home')"
              @click="closeMobileMenu"
              class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded transition-colors"
              :class="{ 'font-bold text-blue-600 bg-blue-50': page.url === '/' }"
            >
              {{ translations?.layout?.nav?.home || 'Home' }}
            </Link>
            <Link
              :href="route('library')"
              @click="closeMobileMenu"
              class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded transition-colors"
              :class="{ 'font-bold text-blue-600 bg-blue-50': page.url === '/library' }"
            >
              {{ translations?.layout?.nav?.library || 'My Library' }}
              <span v-if="user" class="ml-1">({{ libraryStore.bookCount }})</span>
            </Link>
            <Link
              :href="route('dashboard')"
              @click="closeMobileMenu"
              class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded transition-colors"
              :class="{ 'font-bold text-blue-600 bg-blue-50': page.url === '/dashboard' }"
            >
              {{ translations?.layout?.nav?.dashboard || 'Dashboard' }}
            </Link>

            <!-- Mobile Auth Section -->
            <div class="border-t pt-2 mt-2">
              <template v-if="user">
                <div class="px-3 py-2 text-gray-600 text-sm">
                  {{ (translations?.layout?.auth?.hi || 'Hi, :name').replace(':name', user.name) }}
                </div>
                <button
                  @click="logout"
                  class="block w-full text-left px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded transition-colors"
                >
                  {{ translations?.layout?.auth?.logout || 'Logout' }}
                </button>
              </template>
              <template v-else>
                <Link
                  :href="route('login')"
                  @click="closeMobileMenu"
                  class="block px-3 py-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded transition-colors"
                >
                  {{ translations?.layout?.auth?.login || 'Login' }}
                </Link>
                <Link
                  :href="route('register')"
                  @click="closeMobileMenu"
                  class="block px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors mt-1"
                >
                  {{ translations?.layout?.auth?.register || 'Register' }}
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
        <p>
          {{
            translations?.layout?.footer?.copyright || '© 2024 Biblioteca. All rights reserved.'
          }}
        </p>
      </div>
    </footer>
  </div>
</template>
