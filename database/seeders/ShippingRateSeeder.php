<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShippingRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ShippingRate::insert([
            ['region_name' => 'Yangon', 'base_fee' => 0, 'extra_fee_per_item' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['region_name' => 'Mandalay', 'base_fee' => 0, 'extra_fee_per_item' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['region_name' => 'Bago', 'base_fee' => 3000, 'extra_fee_per_item' => 500, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
