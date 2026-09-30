<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'image' => 'products/'.fake()->uuid().'.jpg',
            'is_primary' => false,
        ];
    }
}
