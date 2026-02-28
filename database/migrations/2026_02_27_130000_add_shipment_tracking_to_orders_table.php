<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_tracking_number')->nullable()->after('shipping_method');
            $table->string('shipping_tracking_url')->nullable()->after('shipping_tracking_number');
            $table->timestamp('packed_at')->nullable()->after('payment_details');
            $table->timestamp('shipped_at')->nullable()->after('packed_at');
            $table->timestamp('tracking_notified_at')->nullable()->after('shipped_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_tracking_number',
                'shipping_tracking_url',
                'packed_at',
                'shipped_at',
                'tracking_notified_at',
            ]);
        });
    }
};
