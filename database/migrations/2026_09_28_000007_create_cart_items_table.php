<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_variant_id');
            $table->unsignedInteger('quantity');
            $table->timestamps();

            $table->index('user_id');
            $table->index('product_id');
            $table->index('product_variant_id');

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->cascadeOnDelete();
        });

        DB::statement('ALTER TABLE cart_items ADD CONSTRAINT cart_items_quantity_check CHECK (quantity > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
