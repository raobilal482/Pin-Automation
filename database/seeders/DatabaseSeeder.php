<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PinterestAccount;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Test User Create Karein
        $user = User::create([
            'name' => 'Tekcroft Admin',
            'email' => 'admin@tekcroft.com', // Login email
            'password' => Hash::make('password'), // Login password
        ]);

        $this->command->info('Test User Created: admin@tekcroft.com');

        // 2. Pinterest Account Attach Karein
        PinterestAccount::create([
            'user_id' => $user->id,
            'nickname' => 'My Sandbox Account',
            'username' => 'sandbox_tester',
            'pinterest_user_id' => '999999',
            // Yahan wo lamba token dalen jo aapne copy kiya tha, ya dummy rakh len
            'access_token' => 'pina_YOUR_SANDBOX_TOKEN_HERE',
            'avatar_url' => 'https://ui-avatars.com/api/?name=Sandbox+Account&background=E60023&color=fff',
            'is_active' => true,
        ]);

        $this->command->info('Pinterest Account Linked Successfully!');
    }
}
