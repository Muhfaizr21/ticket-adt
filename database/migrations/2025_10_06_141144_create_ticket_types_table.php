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
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel events
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');

            $table->string('name'); // Contoh: VIP, Regular
            $table->text('description')->nullable();

            // Tambahan kolom tiket dan harga (opsional jika tiap event_type punya data sendiri)
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('total_tickets')->default(0);
            $table->integer('available_tickets')->default(0);

            // Kolom diskon opsional
            $table->enum('discount_type', ['percent', 'nominal'])->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->date('discount_start')->nullable();
            $table->date('discount_end')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_types');
    }
};
