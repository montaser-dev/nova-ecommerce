<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('compare_price', 10, 2)->nullable();
            $table->enum('status', ['active', 'draft', 'archived'])->default('draft');
            $table->timestamps();

            $table->index('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->restrictOnDelete();
        });

        DB::statement('ALTER TABLE products ADD CONSTRAINT products_price_check CHECK (price >= 0)');
        DB::statement('ALTER TABLE products ADD CONSTRAINT products_compare_price_check CHECK (compare_price IS NULL OR compare_price >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
