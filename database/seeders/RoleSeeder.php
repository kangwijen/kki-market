<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'name' => 'Admin',
                'privilege_level' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'User',
                'privilege_level' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
