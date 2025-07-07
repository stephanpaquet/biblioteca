
<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { useTranslationsStore } from '../stores/translations';

const translationsStore = useTranslationsStore();

const currentLocale = ref(translationsStore.translations.locale);

const languageNames = {
  en: 'English',
  fr: 'Français',
  es: 'Español',
  de: 'Deutsch'
};

function getLanguageName(locale) {
  return languageNames[locale] || locale.toUpperCase();
}

function changeLocale(event) {
  Inertia.get(window.location.pathname, { locale: translationsStore.translations.currentLocale }, {
    preserveState: true,
    replace: true
  });
}
</script>

<template>
  <div class="relative">
    <select
      v-model="translationsStore.translations.currentLocale"
      @change="changeLocale"
      class="appearance-none bg-white border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
    >
      <option v-for="locale in translationsStore.translations.supportedLocales" :key="locale" :value="locale">
        {{ getLanguageName(locale) }}
      </option>
    </select>
  </div>
</template>
