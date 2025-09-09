<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User; // Assuming your User model is in App\User

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if the admin user already exists
        $adminUser = User::where('email', 'gcadmin01@getcare.com')->first();

        if (is_null($adminUser)) {
            User::create([
                'email' => 'gcadmin01@getcare.com',
                'password' => Hash::make('gcadmin01'),
                'role' => 'ADMIN',
                'is_active' => true,
            ]);
        }
    }
}