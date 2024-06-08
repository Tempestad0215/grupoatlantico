<?php

namespace Database\Factories;

use App\Models\Branches;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
            'name' => fake()->randomElement(['Toner 135A','Toner 105A','Cable HDMI','Cable DP 5 Pie', 'Mouse x350', 'Teclado Dell']),
            'description' => fake()->sentence(3),
            'branch_id' => Branches::factory(),
            'stock' => fake()->numberBetween(1,100),
        ];
    }
}
