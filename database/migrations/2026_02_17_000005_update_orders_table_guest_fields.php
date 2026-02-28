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
            // Tambah kolom untuk guest checkout
            $table->string('guest_email')->nullable()->after('user_id');
            $table->string('guest_name')->nullable()->after('guest_email');
            $table->string('guest_phone')->nullable()->after('guest_name');
            $table->text('guest_address')->nullable()->after('guest_phone');
            $table->string('guest_city')->nullable()->after('guest_address');
            $table->string('guest_postal_code')->nullable()->after('guest_city');

            // Midtrans integration (notes & admin_notes already exist)
            $table->string('transaction_id')->nullable()->unique()->after('notes'); // dari Midtrans
            $table->string('payment_method')->nullable()->after('transaction_id');
            $table->text('payment_details')->nullable()->after('payment_method'); // JSON untuk store payment details

            // Indexes
            $table->index('guest_email');
            $table->index('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['guest_email']);
            $table->dropIndex(['transaction_id']);

            $table->dropColumn([
                'guest_email',
                'guest_name',
                'guest_phone',
                'guest_address',
                'guest_city',
                'guest_postal_code',
                'transaction_id',
                'payment_method',
                'payment_details',
            ]);
        });
    }
};
