<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { useTranslationsStore } from '../stores/translations';

const translationsStore = useTranslationsStore();

const currentLocale = ref(translationsStore.translations.locale);

const languageNames = {
  en: 'English',
  fr: 'Français',
  es: 'Español',
  de: 'Deutsch',
};

function getLanguageName(locale) {
  return languageNames[locale] || locale.toUpperCase();
}

function changeLocale(event) {
  router.get(
    window.location.pathname,
    { locale: translationsStore.translations.currentLocale },
    {
      preserveState: true,
      replace: true,
      onSuccess: (response) => {
        translationsStore.setTranslations({
          currentLocale: response.props.currentLocale,
          supportedLocales: response.props.supportedLocales,
          texts: response.props.translations,
        });
      },
    }
  );
}
</script>

<template>
  <div class="relative">
    <select
      v-model="translationsStore.translations.currentLocale"
      class="appearance-none bg-white border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
      @change="changeLocale"
    >
      >
      <option
        v-for="locale in translationsStore.translations.supportedLocales"
        :key="locale"
        :value="locale"
      >
        {{ getLanguageName(locale) }}
      </option>
    </select>
  </div>
</template>
