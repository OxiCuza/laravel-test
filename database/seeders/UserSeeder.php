<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // TODO : CHANGE TO FACTORY
        DB::table('users')->insert([
            [
                'id' => Str::uuid(),
                'name' => 'user premium',
                'email' => 'premium@localhost.com',
                'password' => bcrypt('premium'),
                'credit' => 40,
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'user owner',
                'email' => 'owner@localhost.com',
                'password' => bcrypt('owner'),
                'credit' => null,
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'user regular',
                'email' => 'regular@localhost.com',
                'password' => bcrypt('regular'),
                'credit' => 20,
                'role_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
