<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Laravel\Fortify\Fortify;

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
        // Configure Fortify views for Inertia
        Fortify::loginView(function () {
            return Inertia::render('Login');
        });

        Fortify::registerView(function () {
            return Inertia::render('Register');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return Inertia::render('PasswordReset');
        });

        Fortify::resetPasswordView(function ($request) {
            return Inertia::render('ResetPassword', [
                'token' => $request->route('token'),
                'email' => $request->email,
            ]);
        });

        Inertia::share([
            'auth' => function () {
                return [
                    'user' => Auth::user(),
                ];
            },
            'csrf_token' => function () {
                return csrf_token();
            },
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
