<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserBooks
{
    public function get(array $params = []): array
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            return $user->books()->get()->toArray();
        }

        return [];
    }
}
