<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\TransactionHeader;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransactionDetail>
 */
class TransactionDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 5);
        $price = $this->faker->numberBetween(1000, 100000);

        return [
            'transaction_header_id' => TransactionHeader::factory(),
            'product_id' => Product::factory(),
            'quantity' => $quantity,
            'price' => $price,
            'total_price' => $quantity * $price,
        ];
    }
}
