<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_types')->insert([
            [
                'name' => 'Electronics',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Apparel',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Home Appliances',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
