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
        Schema::create('customers', function (Blueprint $table) {
        $table->id(); // Customer ID
        $table->string('name');
        $table->string('phone')->unique(); // Phone number should be unique
        $table->string('email')->unique()->nullable(); // Email is optional
        $table->text('address')->nullable(); // Address is also optional
        $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
