<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [        
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'deadline' => $this->faker->date,
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
           //'user_id' => $this->faker->randomElement([1, 2, 3]), // Adjust user IDs as needed
        ];
    }
}
