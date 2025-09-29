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
        $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
        $table->string('order_number')->unique();
        $table->decimal('total_amount', 10, 2);
        $table->enum('status', ['pending', 'processing', 'ready', 'completed', 'cancelled'])->default('pending');
        $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid');
        $table->date('order_date');
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
