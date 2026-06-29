<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@kranbstour.com')],
            [
                'name' => 'Admin',
                'password' => env('ADMIN_PASSWORD', 'password'),
                'role' => User::ROLE_ADMIN,
                'phone' => config('site.phone_display'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Demo Customer',
                'password' => 'password',
                'role' => User::ROLE_CUSTOMER,
                'phone' => '+91-9876543210',
            ]
        );

        $this->call(TourMasterSeeder::class);
        $this->call(PaymentGatewaySeeder::class);
    }
}
