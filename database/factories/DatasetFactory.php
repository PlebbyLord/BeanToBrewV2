<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dataset>
 */
class DatasetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sales_date' => fake()->date(),
            'coffee_type' => fake()->randomElement(['Excelsa', 'Robusta', 'Arabica', 'Liberica']),
            'coffee_form' => fake()->randomElement(['Green', 'Roasted', 'Ground']),
            'sales_kg' => fake()->randomFloat(2, 1, 100), // Assuming you want floats with 2 decimal places between 1 and 100
            'price_per_kilo' => fake()->randomFloat(2, 5, 50), // Assuming you want floats with 2 decimal places between 5 and 50
        ];
    }
}
