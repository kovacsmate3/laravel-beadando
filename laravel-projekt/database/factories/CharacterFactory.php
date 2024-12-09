<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Character>
 */
class CharacterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $defence = $this->faker->numberBetween(0, 3);
        $maxPoints = 20;
        $remainingPoints = $maxPoints - $defence;

        $strength = $this->faker->numberBetween(0, $remainingPoints);
        $remainingPoints -= $strength;

        $accuracy = $this->faker->numberBetween(0, $remainingPoints);
        $remainingPoints -= $accuracy;

        $magic = $remainingPoints;

        return [
            'name' => Str::ucfirst(implode(' ', fake()->words(3))),
            'enemy' => false,
            'defence' => $defence,
            'strength' => $strength,
            'accuracy' => $accuracy,
            'magic' => $magic
        ];
    }
}
