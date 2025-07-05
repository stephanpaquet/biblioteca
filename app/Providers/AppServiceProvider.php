<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LoginViewResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\RegisterViewResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\GoogleBooksService::class);

        // Custom login response for Inertia
        $this->app->instance(LoginResponse::class, new class implements LoginResponse
        {
            public function toResponse($request)
            {
                if ($request->wantsJson()) {
                    return response()->json(['two_factor' => false]);
                }

                // For Inertia requests, return a redirect response
                return redirect()->intended(config('fortify.home'));
            }
        });

        // Custom register response for Inertia
        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse
        {
            public function toResponse($request)
            {
                if ($request->wantsJson()) {
                    return response()->json(['two_factor' => false]);
                }

                // For Inertia requests, return a redirect response
                return redirect(config('fortify.home'));
            }
        });

        // Custom login view response for Inertia
        $this->app->instance(LoginViewResponse::class, new class implements LoginViewResponse
        {
            public function toResponse($request)
            {
                // Return the Inertia response directly - Laravel will handle it properly
                return Inertia::render('Login')->toResponse($request);
            }
        });

        // Custom register view response for Inertia
        $this->app->instance(RegisterViewResponse::class, new class implements RegisterViewResponse
        {
            public function toResponse($request)
            {
                // Return the Inertia response directly - Laravel will handle it properly
                return Inertia::render('Register')->toResponse($request);
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
