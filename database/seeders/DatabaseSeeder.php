<?php

namespace Database\Seeders;

use App\Models\Member;
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
            Team::factory()->create([
                'leader_id' => $user->id,
                'competition_id' => fake()->numberBetween(1, 10),
            ]);
        }

        $members = User::factory(300)->create();
        $memberIndex = 0;
        for ($i=1; $i <= 100; $i++) {
            for ($j=0; $j < 3; $j++) {
                Member::factory()->create([
                    'team_id' => $i,
                    'user_id' => $members[$memberIndex]->id,
                ]);
                $memberIndex++;
            }
        }
    }
}
