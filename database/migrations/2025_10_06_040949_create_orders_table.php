<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // 🔹 Relasi utama
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');

            // 🔹 Informasi pesanan
            $table->integer('quantity')->unsigned();
            $table->decimal('total_price', 12, 2);

            // 🔹 Barcode unik tiap pesanan
            $table->uuid('barcode_code')->unique()->comment('Kode unik untuk barcode/QR tiap order');

            // 🔹 Status pesanan & refund
            $table->enum('status', ['pending', 'paid', 'cancelled'])
                ->default('pending')
                ->comment('Status pembayaran order');

            $table->enum('refund_status', ['none', 'requested', 'approved', 'rejected'])
                ->default('none')
                ->comment('Status refund tiket');

            $table->text('refund_reason')->nullable()->comment('Alasan refund jika diajukan');
            $table->timestamp('refunded_at')->nullable()->comment('Waktu refund disetujui');

            $table->timestamps();

            // 🔹 Index tambahan
            $table->index(['event_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
