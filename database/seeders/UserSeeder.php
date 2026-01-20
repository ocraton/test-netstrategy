<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Mario Rossi', 'email' => 'mario@test.com'],
            ['name' => 'Luigi Verdi', 'email' => 'luigi@test.com'],
            ['name' => 'Anna Bianchi', 'email' => 'anna@test.com'],
        ];

        foreach ($users as $user) {
            User::factory()->create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => bcrypt('password'),
            ]);
        }
    }
}
