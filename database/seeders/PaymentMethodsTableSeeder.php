<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $methods = [
            [
                'type' => 'bank',
                'name' => 'BCA',
                'account_number' => '1234567890',
                'account_name' => 'PT. Contoh',
                'qr_code_image' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type' => 'bank',
                'name' => 'Mandiri',
                'account_number' => '0987654321',
                'account_name' => 'PT. Contoh',
                'qr_code_image' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type' => 'qris',
                'name' => 'QRIS DANA',
                'account_number' => null,
                'account_name' => null,
                'qr_code_image' => 'payments/dana-qr.jpg', // letakkan file di storage/app/public/payments/
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type' => 'qris',
                'name' => 'QRIS OVO',
                'account_number' => null,
                'account_name' => null,
                'qr_code_image' => 'payments/qris_ovo.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('payment_methods')->insert($methods);
    }
}
