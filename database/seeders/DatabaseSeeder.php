<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        \App\Models\Dataset::factory(100)->create();
        \App\Models\User::factory()->create([
            'first_name' => 'Jaypee',
            'last_name' => 'Quintana',
            'address' => 'San Jose',
            'email' => 'test@example.com',
            'mobile_number' => "12345678987",
            'password' => Hash::make('password'),
            'role' => 1,
            "branch" => 'San Jose'
        ]);
    }
}
