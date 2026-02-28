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
        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('payment_method'); // midtrans, xendit, manual_transfer, dll
            $table->string('payment_channel')->nullable(); // BCA, Mandiri, GoPay, dll
            $table->enum('status', ['pending', 'success', 'failed', 'expired'])->default('pending');
            $table->decimal('amount', 10, 2);

            // Payment Gateway Response
            $table->string('transaction_id')->unique()->nullable(); // ID dari payment gateway
            $table->string('external_id')->nullable(); // ID eksternal dari gateway
            $table->text('payment_url')->nullable(); // URL pembayaran dari gateway
            $table->json('payment_response')->nullable(); // Response lengkap dari gateway

            // Payment Proof (untuk manual transfer)
            $table->string('payment_proof')->nullable();

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
