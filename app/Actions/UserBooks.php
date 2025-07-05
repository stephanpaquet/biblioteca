<?php

namespace App\Actions;

class UserBooks
{
    public function get(): array
    {
        if (auth()->check()) {
            return auth()->books()->get()->toArray();
        }

        return [];
    }
}
