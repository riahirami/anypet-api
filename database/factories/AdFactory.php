<?php

namespace Database\Factories;

use App\Models\Ad;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ad>
 */
class AdFactory extends Factory
{
    protected $model = Ad::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->text($maxNbChars = 12),
            'description' => $this->faker->paragraph($nbSentences = 4),
            'state' => $this->faker->randomElement([1, 2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24]),
            'status' => $this->faker->randomElement([0, 1, 2]),
            'city' => $this->faker->city(),
            'street' => $this->faker->streetAddress(),
            'postal_code' => $this->faker->postcode(),
            'category_id' => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8]),
            'user_id' => $this->faker->randomElement([1, 2]),
        ];
    }
}
