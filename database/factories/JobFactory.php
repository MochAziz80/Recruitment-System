<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraph(),
            'location' => $this->faker->city(),
            'requirements' => $this->faker->randomElements(['PHP', 'Laravel', 'MySQL'], 3),
            'is_active' => true,
            'posted_at' => $this->faker->date(),
        ];
    }
}
