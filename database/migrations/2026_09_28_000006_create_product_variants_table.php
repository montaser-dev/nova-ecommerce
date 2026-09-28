<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('sku')->unique();
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->timestamps();

            $table->index('product_id');
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        DB::statement('ALTER TABLE product_variants ADD CONSTRAINT product_variants_price_check CHECK (price >= 0)');
        DB::statement('ALTER TABLE product_variants ADD CONSTRAINT product_variants_stock_check CHECK (stock >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
