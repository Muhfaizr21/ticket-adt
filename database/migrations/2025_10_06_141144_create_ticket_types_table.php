<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();

            // Relasi ke events
            $table->foreignId('event_id')
                ->constrained('events')
                ->onDelete('cascade');

            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('total_tickets')->default(0);
            $table->integer('available_tickets')->default(0);
            $table->enum('discount_type', ['percent', 'nominal'])->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->date('discount_start')->nullable();
            $table->date('discount_end')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_types');
    }
};
