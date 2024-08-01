<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\User;
use App\Models\Vehicle;
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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Alex Mantovani',
            'email' => 'a@a.a',
            'password' => Hash::make('12345678'),
        ]);

        User::factory()->create([
            'name' => 'Elena Carrà',
            'email' => 'e@e.e',
            'password' => Hash::make('password'),
        ]);

        Vehicle::factory()->count(10)->create();
        Driver::factory()->count(100)->create();

    }
}
