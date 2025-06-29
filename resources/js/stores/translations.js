import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useTranslationsStore = defineStore('translations', () => {
    const translations = ref({})

    function setTranslations(translationsData) {
        translations.value = translationsData
    }

    return {
        setTranslations,
        translations,
    }
})
