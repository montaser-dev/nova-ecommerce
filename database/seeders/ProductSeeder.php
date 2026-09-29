<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Seed demo products, each with 2-3 variants and 2-3 images.
     *
     * Products are matched on slug, variants on sku, and images on the
     * (product_id, image) pair, so running this seeder again updates
     * existing rows instead of creating duplicates. Requires
     * CategorySeeder to have run first.
     */
    public function run(): void
    {
        $categories = Category::pluck('id', 'slug');

        $products = [
            [
                'category' => 'electronics',
                'name' => 'Wireless Bluetooth Headphones',
                'description' => 'Over-ear headphones with active noise cancellation and 30-hour battery life.',
                'price' => 79.99,
                'compare_price' => 99.99,
                'variants' => [
                    ['color' => 'Black', 'stock' => 42],
                    ['color' => 'White', 'stock' => 27],
                    ['color' => 'Navy', 'stock' => 15],
                ],
            ],
            [
                'category' => 'electronics',
                'name' => 'Portable Power Bank 20000mAh',
                'description' => 'Fast-charging power bank with dual USB-C ports and LED battery display.',
                'price' => 34.50,
                'compare_price' => 44.99,
                'variants' => [
                    ['color' => 'Black', 'stock' => 60],
                    ['color' => 'Gray', 'stock' => 38],
                ],
            ],
            [
                'category' => 'clothing-apparel',
                'name' => 'Classic Cotton T-Shirt',
                'description' => 'Soft, breathable 100% cotton t-shirt with a relaxed everyday fit.',
                'price' => 19.99,
                'compare_price' => null,
                'variants' => [
                    ['size' => 'S', 'color' => 'White', 'stock' => 50],
                    ['size' => 'M', 'color' => 'White', 'stock' => 65],
                    ['size' => 'L', 'color' => 'Black', 'stock' => 40],
                ],
            ],
            [
                'category' => 'clothing-apparel',
                'name' => 'Slim Fit Denim Jacket',
                'description' => 'Mid-weight denim jacket with a tailored slim fit and button closure.',
                'price' => 64.00,
                'compare_price' => 79.00,
                'variants' => [
                    ['size' => 'M', 'color' => 'Blue', 'stock' => 22],
                    ['size' => 'L', 'color' => 'Blue', 'stock' => 18],
                ],
            ],
            [
                'category' => 'home-kitchen',
                'name' => 'Stainless Steel Cookware Set',
                'description' => '10-piece cookware set with tri-ply construction and tempered glass lids.',
                'price' => 149.99,
                'compare_price' => 189.99,
                'variants' => [
                    ['color' => 'Silver', 'stock' => 12],
                ],
            ],
            [
                'category' => 'home-kitchen',
                'name' => 'Electric Kettle 1.7L',
                'description' => 'Rapid-boil electric kettle with auto shut-off and a washable filter.',
                'price' => 29.99,
                'compare_price' => null,
                'variants' => [
                    ['color' => 'Black', 'stock' => 35],
                    ['color' => 'White', 'stock' => 20],
                ],
            ],
            [
                'category' => 'sports-outdoors',
                'name' => 'Yoga Mat with Carry Strap',
                'description' => 'Non-slip 6mm yoga mat made from eco-friendly TPE material.',
                'price' => 24.99,
                'compare_price' => 29.99,
                'variants' => [
                    ['color' => 'Purple', 'stock' => 45],
                    ['color' => 'Teal', 'stock' => 33],
                ],
            ],
            [
                'category' => 'sports-outdoors',
                'name' => 'Insulated Water Bottle 32oz',
                'description' => 'Vacuum-insulated stainless steel bottle that keeps drinks cold for 24 hours.',
                'price' => 22.50,
                'compare_price' => null,
                'variants' => [
                    ['color' => 'Matte Black', 'stock' => 55],
                    ['color' => 'Ocean Blue', 'stock' => 29],
                    ['color' => 'Sunset Orange', 'stock' => 17],
                ],
            ],
            [
                'category' => 'books-stationery',
                'name' => 'Hardcover Dot Grid Notebook',
                'description' => 'A5 hardcover notebook with 192 dot grid pages and a ribbon bookmark.',
                'price' => 14.99,
                'compare_price' => null,
                'variants' => [
                    ['color' => 'Black', 'stock' => 70],
                    ['color' => 'Terracotta', 'stock' => 44],
                ],
            ],
            [
                'category' => 'books-stationery',
                'name' => 'Premium Gel Pen Set (12-Pack)',
                'description' => 'Smooth-writing gel pens in assorted colors with quick-dry ink.',
                'price' => 12.99,
                'compare_price' => 16.99,
                'variants' => [
                    ['color' => 'Assorted', 'stock' => 80],
                ],
            ],
        ];

        foreach ($products as $index => $data) {
            $slug = Str::slug($data['name']);
            $skuPrefix = substr(preg_replace('/[^A-Z0-9]/', '', strtoupper(Str::slug($data['name']))), 0, 10);

            $product = Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $categories[$data['category']],
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'price' => $data['price'],
                    'compare_price' => $data['compare_price'],
                    'status' => 'active',
                ]
            );

            foreach ($data['variants'] as $variantIndex => $variant) {
                $sku = sprintf(
                    '%s-%03d',
                    $skuPrefix,
                    $variantIndex + 1
                );

                ProductVariant::updateOrCreate(
                    ['sku' => $sku],
                    [
                        'product_id' => $product->id,
                        'size' => $variant['size'] ?? null,
                        'color' => $variant['color'] ?? null,
                        'price' => $data['price'],
                        'stock' => $variant['stock'],
                    ]
                );
            }

            $imageCount = ($index % 2 === 0) ? 3 : 2;

            for ($i = 1; $i <= $imageCount; $i++) {
                $imagePath = "products/{$slug}-{$i}.jpg";

                ProductImage::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'image' => $imagePath,
                    ],
                    [
                        'is_primary' => $i === 1,
                    ]
                );
            }
        }
    }
}
