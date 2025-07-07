<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;
use App\Models\User;

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

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
            ],
            'csrf_token' => csrf_token(),
            'currentLocale' => Session::get('locale') ?? config('app.locale', 'en'),
            'supportedLocales' => config('app.supported_locales', ['en']),
        ];
    }
}
