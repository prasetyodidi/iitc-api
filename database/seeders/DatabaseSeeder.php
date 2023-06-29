<?php

namespace Database\Seeders;

use App\Models\CategoryCompetition;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'johnDoe@gmail.com',
            'password' => 'myPassword'
        ]);

        $this->call([
            CategorySeeder::class,
            CompetitionSeeder::class,
            CategoryCompetitionSeeder::class
        ]);
    }
}
