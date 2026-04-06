<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $me = \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        $users = \App\Models\User::factory(10)->create();

        $users->push($me);

        $users->each(function ($user) {
            \App\Models\Task::factory(fake()->numberBetween(5, 15))
                ->create([
                    'user_id' => $user->id,
                ]);
        });
    }
}
