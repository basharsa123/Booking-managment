<?php

namespace Database\Factories;

use App\Models\User;
use Faker\Core\Uuid;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id" => user::all()->random()->id, // to get random user id
            "title" => $this->faker->title(),
            "description" => $this->faker->paragraph(),
            "date" => $this->faker->date("Y-m-d H:i:s"),
            "image" => $this->faker->imageUrl(),
            "capacity" => $this->faker->numberBetween(1,249),
            "created_by" => $this->faker->name()
        ];
    }
}
