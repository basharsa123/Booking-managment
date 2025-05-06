<?php

namespace Database\Factories;

use App\Models\event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $array = [true , false];
        return [
            "user_id" => User::all()->random()->id,
            "event_id" => event::all()->random()->id,
            "registered_at" => now()->format("Y-m-d H:i:s"),
            "status" => "Pending",
            "attendance" => array_rand($array,1) ,
            "notes" => $this->faker->paragraph(20) ,
        ];
    }
}
