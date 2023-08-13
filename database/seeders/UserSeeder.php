<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'phone' => '0790584171',
            'email' => 'gogilo2003@gmail.com',
            'is_admin' => 1,
        ]);

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'phone' => '0735388704',
        // ]);
    }
}
