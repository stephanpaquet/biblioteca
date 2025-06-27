<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class VerifyUserSeeder extends Seeder
{
    public function run()
    {
        // Verify all existing users
        User::whereNull('email_verified_at')->update([
            'email_verified_at' => now()
        ]);
        
        echo "All users have been verified!\n";
    }
}
