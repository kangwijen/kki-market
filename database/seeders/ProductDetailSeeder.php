<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_details')->insert([
            [
                'product_id' => 1,
                'price' => 100.00,
                'stock' => 50,
                'sold' => 5,
                'discount' => 0,
                'description' => 'Description for Product A',
                'url' => 'https://example.com/product-a',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 2,
                'price' => 150.00,
                'stock' => 30,
                'sold' => 10,
                'discount' => 0,
                'description' => 'Description for Product B',
                'url' => 'https://example.com/product-b',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 3,
                'price' => 200.00,
                'stock' => 20,
                'sold' => 15,
                'discount' => 0,
                'description' => 'Description for Product C',
                'url' => 'https://example.com/product-c',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
