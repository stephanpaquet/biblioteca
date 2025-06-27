<template>
  <div class="relative">
    <select 
      v-model="currentLocale" 
      @change="changeLocale"
      class="appearance-none bg-white border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
    >
      <option v-for="locale in supportedLocales" :key="locale" :value="locale">
        {{ getLanguageName(locale) }}
      </option>
    </select>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
  locale: {
    type: String,
    required: true
  },
  supportedLocales: {
    type: Array,
    required: true
  }
});

const currentLocale = ref(props.locale);

const languageNames = {
  en: 'English',
  fr: 'Français',
  es: 'Español',
  de: 'Deutsch'
};

function getLanguageName(locale) {
  return languageNames[locale] || locale.toUpperCase();
}

function changeLocale() {
  Inertia.get(window.location.pathname, { locale: currentLocale.value }, {
    preserveState: true,
    replace: true
  });
}
</script>
