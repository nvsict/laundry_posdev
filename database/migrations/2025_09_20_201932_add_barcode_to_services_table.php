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
        Schema::table('services', function (Blueprint $table) {
        // Add a new 'barcode' column that can be empty and must be unique
        $table->string('barcode')->nullable()->unique()->after('price_type');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
        // This allows us to reverse the migration if needed
        $table->dropColumn('barcode');
    });
    }
};
