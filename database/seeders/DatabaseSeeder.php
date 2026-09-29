<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['Test User', 'test@example.com'],
            ['Sara Ahmed', 'sara@example.com'],
            ['Omar Ali', 'omar@example.com'],
        ];

        foreach ($users as [$name, $email]) {
            // Password is hashed by the model's 'hashed' cast
            User::updateOrCreate(['email' => $email], ['name' => $name, 'password' => 'password']);
        }

        $this->call([
            RoleSeeder::class,
            PlatformSeeder::class,
            PostSeeder::class,
        ]);
    }
}