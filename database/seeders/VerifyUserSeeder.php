<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class VerifyUserSeeder extends Seeder
{
    public function run()
    {
        // Verify all existing users
        User::whereNull('email_verified_at')->update([
            'email_verified_at' => now(),
        ]);

        echo "All users have been verified!\n";
    }
}
