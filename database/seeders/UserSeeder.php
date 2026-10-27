<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        User::updateOrCreate(
            ['email' => 'admin@parktrack.com'],
            [
                'name' => 'Admin Petugas',
                'password' => Hash::make('password123'),
                'role' => 'officer',
                'phone' => '081234567890',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'User Utama',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'phone' => '089876543210',
            ]
        );

        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'phone' => '08' . $faker->numerify('##########'),
                'password' => Hash::make('password123'),
                'role' => 'user',
            ]);
        }
    }
}
