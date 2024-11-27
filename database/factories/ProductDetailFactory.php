<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductDetail>
 */
class ProductDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'price' => $this->faker->numberBetween(1000, 100000),
            'stock' => $this->faker->numberBetween(1, 100),
            'sold' => 0,
            'discount' => $this->faker->numberBetween(0, 50),
            'description' => $this->faker->text(),
            'url' => $this->faker->url(),
        ];
    }
}
