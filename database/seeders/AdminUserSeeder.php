<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('islah@123'), // islah@123
            'is_admin' => true,
        ]);

        User::create([
            'name' => 'Omor Faruk',
            'email' => 'omor@faruk.me',
            'email_verified_at' => now(),
            'password' => bcrypt('me@12345'), // me@12345
            'is_admin' => true,
        ]);
    }
}
