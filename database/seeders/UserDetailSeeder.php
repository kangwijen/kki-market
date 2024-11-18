<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("user_details")->insert([
            [
                'user_id' => 1,
                'balance' => 1000.00,
                'verified' => true,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                'user_id' => 2,
                'balance' => 1000.00,
                'verified' => true,
                "created_at" => now(),
                "updated_at" => now(),
            ],
        ]);
    }
}
