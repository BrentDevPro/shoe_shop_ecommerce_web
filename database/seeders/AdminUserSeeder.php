<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@shoeshop.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '09123456789',
            'address' => 'Tarlac City, Philippines',
        ]);
    }
}