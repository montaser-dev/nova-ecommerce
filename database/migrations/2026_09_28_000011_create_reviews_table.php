<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            $table->unique(['user_id', 'product_id']);
            $table->index('user_id');
            $table->index('product_id');

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        DB::statement('ALTER TABLE reviews ADD CONSTRAINT reviews_rating_check CHECK (rating BETWEEN 1 AND 5)');
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
