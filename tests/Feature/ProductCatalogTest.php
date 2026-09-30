<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ProductCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_lists_only_active_products_with_relations(): void
    {
        $category = Category::factory()->create();

        $active = Product::factory()
            ->for($category)
            ->create(['status' => 'active']);

        ProductImage::factory()->for($active)->create(['is_primary' => true]);
        ProductVariant::factory()->for($active)->create();

        Product::factory()->for($category)->create(['status' => 'draft']);
        Product::factory()->for($category)->create(['status' => 'archived']);

        $response = $this->get('/products');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Products/Index')
            ->has('products.data', 1)
            ->where('products.data.0.id', $active->id)
        );
    }

    public function test_show_returns_product_with_category_images_and_variants(): void
    {
        $category = Category::factory()->create();

        $product = Product::factory()
            ->for($category)
            ->create(['status' => 'active']);

        ProductImage::factory()->for($product)->count(2)->create();
        ProductVariant::factory()->for($product)->count(2)->create();

        $response = $this->get("/products/{$product->slug}");

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Products/Show')
            ->where('product.id', $product->id)
            ->has('product.category')
            ->has('product.images', 2)
            ->has('product.variants', 2)
        );
    }

    public function test_show_returns_404_for_non_active_product(): void
    {
        $category = Category::factory()->create();

        $product = Product::factory()
            ->for($category)
            ->create(['status' => 'draft']);

        $this->get("/products/{$product->slug}")->assertNotFound();
    }
}
