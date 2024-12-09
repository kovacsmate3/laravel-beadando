<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Place>
 */
class PlaceFactory extends Factory
{
    private $uniqueWords = [];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $word = $this->uniqueWord();

        return [
            'name' => $word,
            'image' => $this->faker->imageUrl(640, 480)
        ];
    }

    /**
     * Generate a unique word.
     *
     * @return string
     */
    protected function uniqueWord(): string
    {
        $newWord = Str::ucfirst(fake()->word());
        while (in_array($newWord, $this->uniqueWords)) {
            $newWord = Str::ucfirst(fake()->word());
        }
        $this->uniqueWords[] = $newWord;
        return $newWord;
    }
}
