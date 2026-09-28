<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_variant_id');
            $table->string('product_name');
            $table->text('variant_details')->nullable();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();

            $table->index('order_id');
            $table->index('product_id');
            $table->index('product_variant_id');

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            // Restrict: products/variants referenced by historical orders cannot be hard-deleted.
            $table->foreign('product_id')->references('id')->on('products')->restrictOnDelete();
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->restrictOnDelete();
        });

        DB::statement('ALTER TABLE order_items ADD CONSTRAINT order_items_quantity_check CHECK (quantity > 0)');
        DB::statement('ALTER TABLE order_items ADD CONSTRAINT order_items_unit_price_check CHECK (unit_price >= 0)');
        DB::statement('ALTER TABLE order_items ADD CONSTRAINT order_items_total_check CHECK (total >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
