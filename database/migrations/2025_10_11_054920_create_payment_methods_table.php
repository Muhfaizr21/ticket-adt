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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // bank / qris
            $table->string('name'); // Nama bank atau QRIS
            $table->string('account_number')->nullable(); // nomor rekening jika bank
            $table->string('account_name')->nullable();   // nama pemilik jika bank
            $table->string('qr_code_image')->nullable();  // path QRIS jika qris
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
