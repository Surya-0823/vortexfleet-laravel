<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // User model-ah import pannu
use Illuminate\Support\Facades\Hash; // Hash-ah import pannu

class DevAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // admin@vortex.com user irukka-nu check pannu
        $adminUser = User::where('email', 'admin@vortex.com')->first();

        if (!$adminUser) {
            // Illainaa, pudhu user-ah create pannu
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@vortex.com',
                'password' => Hash::make('admin'), // 'admin' password-ah hash pannu
                'subscription_plan' => 'premium', // Etho oru default value
                'payment_status' => 'completed',  // Mukkiyam: Payment-ah complete pannu
                'status' => 'active',             // Mukkiyam: Account-ah active pannu
                'institution_name' => 'Vortex Dev',
            ]);
        }
    }
}