<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => [
                'ar' => fake('ar_EG')->word(),
                'en' => fake('en_US')->word(),
            ],
            'purchase_price' => fake()->randomFloat(2, 100, 10000),
            'sale_price' => fake()->randomFloat(2, 100, 10000),
            'stock' => fake()->numberBetween(10, 100),
        ];
    }
}
