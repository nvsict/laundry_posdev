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
        Schema::table('orders', function (Blueprint $table) {
            // Add all the new columns first
            $table->decimal('subtotal', 10, 2)->after('order_number');
            $table->decimal('service_charge_amount', 10, 2)->default(0)->after('subtotal');
            $table->decimal('tax_rate', 5, 2)->default(0)->after('service_charge_amount');
            $table->decimal('tax_amount', 10, 2)->default(0)->after('tax_rate');
            $table->decimal('grand_total', 10, 2)->after('tax_amount');

            // Now, safely drop the old column
            $table->dropColumn('total_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // This makes the migration reversible
            $table->dropColumn(['subtotal', 'service_charge_amount', 'tax_rate', 'tax_amount', 'grand_total']);
            $table->decimal('total_amount', 10, 2)->after('order_number');
        });
    }
};