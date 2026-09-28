<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])
                ->default('pending');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_cost', 10, 2);
            $table->decimal('total', 10, 2);
            $table->json('shipping_address');
            $table->json('billing_address');
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
        });

        DB::statement('ALTER TABLE orders ADD CONSTRAINT orders_subtotal_check CHECK (subtotal >= 0)');
        DB::statement('ALTER TABLE orders ADD CONSTRAINT orders_shipping_cost_check CHECK (shipping_cost >= 0)');
        DB::statement('ALTER TABLE orders ADD CONSTRAINT orders_total_check CHECK (total >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
