<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('expenses', function (Blueprint $table) {
        // First, drop the existing foreign key constraint
        $table->dropForeign(['expense_category_id']);

        // Now, add the foreign key back with cascade on delete
        $table->foreign('expense_category_id')
              ->references('id')
              ->on('expense_categories')
              ->cascadeOnDelete();
    });
}

public function down(): void
{
    Schema::table('expenses', function (Blueprint $table) {
        // This makes the migration reversible
        $table->dropForeign(['expense_category_id']);
        
        $table->foreign('expense_category_id')
              ->references('id')
              ->on('expense_categories');
    });
}
};