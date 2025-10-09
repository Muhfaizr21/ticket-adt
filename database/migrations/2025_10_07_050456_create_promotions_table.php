<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();

            // Relasi ke ticket_types
            $table->foreignId('ticket_type_id')
                ->nullable()
                ->constrained('ticket_types')
                ->onDelete('cascade');

            // Relasi ke events (jika diperlukan langsung ke event)
            $table->foreignId('event_id')
                ->nullable()
                ->constrained('events')
                ->onDelete('cascade');

            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->decimal('persen_diskon', 10, 2)->nullable();
            $table->decimal('value', 10, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
