<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contest>
 */
class ContestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $win = rand(0, 1) ? $this->faker->boolean() : null;
        $historyText = "This contest, forever captured in the annals of history, holds the names of its valiant participants and records the ultimate outcome. Yet, the intricate details of the battle — the precise maneuvers, strikes, and counterstrikes — have faded from memory, shrouded in the mists of time. What remains are the outcomes, celebrated in tales that echo through the ages, and the legacy of courage and strategy that shaped the destinies of those who fought.";

        if ($win !== null) {
            $history = $historyText;
        } else {
            $history = "";
        }

        return [
            'win' => $win,
            'history' => $history
        ];
    }
}
