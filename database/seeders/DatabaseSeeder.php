<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = bcrypt('password');
        User::create([
            'email' => 'user@email.com',
            'username' => 'user',
            'password' => $password,
            'name' => 'User',
            'role' => 'user',
        ]);
        User::create([
            'email' => 'admin@email.com',
            'username' => 'admin',
            'password' => $password,
            'name' => 'Admin',
            'role' => 'admin',
        ]);
    }
}
