<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('date'); // 🗓️ tanggal event
            $table->string('location'); // 📍 lokasi event
            $table->decimal('price', 10, 2); // harga default
            $table->integer('total_tickets');
            $table->integer('available_tickets');

            // Tiket VIP
            $table->integer('vip_tickets')->nullable();
            $table->decimal('vip_price', 10, 2)->nullable();

            // Tiket Reguler
            $table->integer('reguler_tickets')->nullable();
            $table->decimal('reguler_price', 10, 2)->nullable();

            $table->string('poster')->nullable(); // 🖼️ path poster
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
