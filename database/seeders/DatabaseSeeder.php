<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MovieSeeder::class,
            ShowtimeSeeder::class,
            DateSeeder::class,
            SeatSeeder::class,
        ]);

        // Create admin user if they don't exist
        $adminUser = User::where('username', 'admin')->first();

        if (!$adminUser) {
            User::create([
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'name' => 'Admin User',
                'age' => 30,
                'role' => 'admin',
            ]);
        }
    }
}
