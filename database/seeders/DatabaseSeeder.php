<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Roles
        $roles = [
            ['name' => 'Super Admin', 'description' => 'Full access to all system features, users, and settings.'],
            ['name' => 'Inventory Manager', 'description' => 'Manage products, stock, categories, and promotions.'],
            ['name' => 'Order Staff', 'description' => 'Process incoming orders, check payment screenshots, and update shipping status.'],
            ['name' => 'Customer Support', 'description' => 'Handle customer inquiries, reviews, and track order issues.'],
            ['name' => 'Finance Manager', 'description' => 'Access sales reports, revenue tracking, and financial analytics.'],
            ['name' => 'Customer', 'description' => 'Regular shopper who can browse, cart, and buy products.']
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']],
                ['description' => $roleData['description']]
            );
        }

        // 2. Get Super Admin Role
        $superAdminRole = Role::query()->where('name', 'Super Admin')->first();

        // 3. Create Super Admin User
        $user = User::firstOrCreate(
            ['email' => 'admin@schoolstationary.com'],
            [
                'role_id' => $superAdminRole->id,
                'password' => Hash::make('password123'),
                'status' => 'active'
            ]
        );

        // 4. Create Staff Profile for Super Admin
        Staff::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => 'System Admin',
                'phone' => '09123456789',
            ]
        );
    }
}
