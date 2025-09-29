<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('purchases', function (Blueprint $table) {
        // Drop the old rule
        $table->dropForeign(['supplier_id']);

        // Add the new rule with cascade on delete
        $table->foreign('supplier_id')
              ->references('id')
              ->on('suppliers')
              ->cascadeOnDelete();
    });
}

public function down(): void
{
    Schema::table('purchases', function (Blueprint $table) {
        // This makes the migration reversible
        $table->dropForeign(['supplier_id']);

        $table->foreign('supplier_id')
              ->references('id')
              ->on('suppliers');
    });
}
};
