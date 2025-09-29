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
        Schema::create('services', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // The name of the service (e.g., "Shirt")
        $table->decimal('price', 8, 2); // Price, allowing for up to 999,999.99
        $table->enum('price_type', ['per_item', 'per_kg'])->default('per_item');
        $table->boolean('is_active')->default(true);
        
        // This is the crucial line that links to the `service_types` table
        $table->foreignId('service_type_id')->constrained('service_types');
        
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
