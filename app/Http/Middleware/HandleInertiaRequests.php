<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = null;
        if (Auth::check()) {
            /** @var User $authUser */
            $authUser = Auth::user();
            $authUser->load('roles.permissions');

            $user = $authUser->toArray();
            $user['can_manage_users'] = $authUser->can('manage users');
        }

        // Get current locale
        $currentLocale = Session::get('locale') ?? config('app.locale', 'en');

        // Load all global translations
        $globalTranslations = $this->getGlobalTranslations($currentLocale);

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
            ],
            'csrf_token' => csrf_token(),
            'currentLocale' => $currentLocale,
            'supportedLocales' => config('app.supported_locales', ['en']),
            'translations' => $globalTranslations,
        ];
    }

    /**
     * Get all global translations for the given locale
     */
    private function getGlobalTranslations(string $locale): array
    {
        // Define all translation files to load globally
        $translationFiles = [
            'layout',
            'navigation',
            'home',
            'library',
            'bookgrid',
            'admin', // Added admin translations
            // Add more as needed
        ];

        $translations = [];

        foreach ($translationFiles as $file) {
            try {
                $translations[$file] = trans($file, [], $locale);
            } catch (\Exception $e) {
                // If translation file doesn't exist, provide empty array
                $translations[$file] = [];
            }
        }

        return $translations;
    }
}
