<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\GoogleBooksService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inertia::share([
            'locale' => function () {
                return app()->getLocale();
            },
            'supportedLocales' => function () {
                return config('app.supported_locales', ['en']);
            },
            'translations' => function () {
                return [
                    'layout' => __('layout')
                ];
            }
        ]);
    }
}
