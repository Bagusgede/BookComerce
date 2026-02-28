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
        Schema::disableForeignKeyConstraints();

        Schema::create('ebook_deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_item_id');
            $table->string('email');
            $table->string('download_token')->unique(); // Unique token untuk secure download link
            $table->timestamp('sent_at');
            $table->timestamp('expired_at'); // Link expire setelah N hari
            $table->integer('download_count')->default(0); // Track berapa kali didownload
            $table->timestamp('last_downloaded_at')->nullable();
            $table->timestamps();

            $table->index('download_token');
            $table->index('email');
            $table->index('expired_at');
        });

        Schema::enableForeignKeyConstraints();

        // Add foreign key constraint separately
        Schema::table('ebook_deliveries', function (Blueprint $table) {
            $table->foreign('order_item_id')
                ->references('id')
                ->on('order_items')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebook_deliveries');
    }
};
