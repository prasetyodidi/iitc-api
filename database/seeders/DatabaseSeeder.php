<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
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
        $users = User::factory(100)->create();

        foreach ($users as $user) {
            $isJoin = fake()->boolean;
            if ($isJoin) {
                Team::factory()->create([
                    'leader_id' => $user->id,
                    'competition_id' => fake()->numberBetween(1, 10),
                ]);
            }
        }
    }
}
