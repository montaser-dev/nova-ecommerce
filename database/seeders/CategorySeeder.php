<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Seed the demo product categories.
     *
     * Uses updateOrCreate keyed on slug, so running this seeder again
     * updates the same records instead of creating duplicates.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Everyday tech: audio, accessories, and smart gadgets.',
            ],
            [
                'name' => 'Clothing & Apparel',
                'description' => 'Casual and everyday wear for men and women.',
            ],
            [
                'name' => 'Home & Kitchen',
                'description' => 'Essentials for cooking, dining, and organizing your home.',
            ],
            [
                'name' => 'Sports & Outdoors',
                'description' => 'Gear and equipment for fitness and outdoor activities.',
            ],
            [
                'name' => 'Books & Stationery',
                'description' => 'Notebooks, journals, and everyday writing supplies.',
            ],
        ];

        foreach ($categories as $category) {
            $slug = Str::slug($category['name']);

            Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'image' => null,
                    'is_active' => true,
                ]
            );
        }
    }
}
