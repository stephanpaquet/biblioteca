import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useTranslationsStore = defineStore('translations', () => {
  const translations = ref({});

  function setTranslations(translationsData) {
    translations.value = translationsData;
  }

  // Translation helper
  function t(key, replacements = {}) {
    const [namespace, keyName] = key.split('.');
    let translation = translations.value.texts?.[namespace]?.[keyName];

    // If translation not found, return key itself as fallback
    if (!translation) {
      console.warn(`Translation not found for key: ${key}`);
      return key;
    }

    // Handle replacements if any
    if (Object.keys(replacements).length > 0) {
      Object.entries(replacements).forEach(([key, value]) => {
        translation = translation.replace(`:${key}`, value);
      });
    }

    return translation + '*';
  }

  return {
    setTranslations,
    translations,
    t,
  };
});
