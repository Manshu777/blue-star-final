<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Photo;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Create or get the test user (avoids duplicate email)
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'username' => 'testuser',
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'active',
            ]
        );

        // ✅ Create 3 sample photographers (auto unique usernames/emails from factory)
        $photographers = User::factory()
            ->count(3)
            ->photographer()
            ->create();

        // ✅ Create 5 sample photos per photographer
        foreach ($photographers as $p) {
            Photo::factory(5)->create([
                'photographer_id' => $p->id,
                'price' => rand(1, 20) + (rand(0, 99) / 100),
                'is_sold' => false,
            ]);
        }
    }
}
