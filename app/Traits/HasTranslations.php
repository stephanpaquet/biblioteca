<?php

namespace App\Traits;

trait HasTranslations
{
    /**
     * Get translations for specific translation files
     */
    protected function getTranslations(array $files = [], string $locale = null): array
    {
        $locale = $locale ?? session('locale', config('app.locale', 'en'));
        $translations = [];

        foreach ($files as $file) {
            try {
                $translations[$file] = trans($file, [], $locale);
            } catch (\Exception $e) {
                $translations[$file] = [];
            }
        }

        return $translations;
    }

    /**
     * Get all global translations that are loaded in middleware
     */
    protected function getGlobalTranslations(string $locale = null): array
    {
        $locale = $locale ?? session('locale', config('app.locale', 'en'));

        // Define all translation files that should be globally available
        $globalFiles = [
            'layout',
            'navigation',
            'home',
            'library',
            'bookgrid',
        ];

        return $this->getTranslations($globalFiles, $locale);
    }

    /**
     * Get translations for a specific page with additional files
     */
    protected function getPageTranslations(string $page, array $additionalFiles = []): array
    {
        $files = array_merge([$page], $additionalFiles);
        return $this->getTranslations($files);
    }

    /**
     * Merge additional translations with global ones for controller responses
     * This is useful when you want to add page-specific translations
     */
    protected function mergeWithGlobalTranslations(array $additionalTranslations = []): array
    {
        $globalTranslations = $this->getGlobalTranslations();

        return array_merge($globalTranslations, $additionalTranslations);
    }
}
