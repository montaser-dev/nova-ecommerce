<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->unique();
            $table->enum('payment_method', ['cash_on_delivery', 'card']);
            $table->string('transaction_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->restrictOnDelete();
        });

        DB::statement('ALTER TABLE payments ADD CONSTRAINT payments_amount_check CHECK (amount >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
