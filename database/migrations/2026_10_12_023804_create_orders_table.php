<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('tracking_number')->unique();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->string('status')->default('pending'); // pending, paid, shipped, completed, cancelled
            $table->string('payment_method'); // COD, GCash, Maya, Visa
            $table->string('payment_proof_image')->nullable();
            $table->text('shipping_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
