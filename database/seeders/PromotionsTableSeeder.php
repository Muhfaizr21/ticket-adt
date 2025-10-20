<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PromotionsTableSeeder extends Seeder
{
    public function run()
    {
        $events = DB::table('events')->pluck('id')->toArray();
        $ticketTypes = DB::table('ticket_types')->pluck('id')->toArray();

        if (empty($events) || empty($ticketTypes)) {
            $this->command->info('Seed events dan ticket_types dulu sebelum promotions!');
            return;
        }

        $promotions = [];
        $today = Carbon::now();
        $nextWeek = $today->copy()->addDays(7);

        // 1️⃣ PROMO GLOBAL (berlaku seminggu ke depan)
        $promotions[] = [
            'code' => 'EARLYBIRD10',
            'name' => 'Early Bird 10%',
            'persen_diskon' => 10.00,
            'value' => null,
            'start_date' => $today->toDateString(),
            'end_date' => $nextWeek->toDateString(),
            'event_id' => null,
            'ticket_type_id' => null,
            'is_active' => 1,
            'created_at' => $today,
            'updated_at' => $today,
        ];

        $promotions[] = [
            'code' => 'WEEKLY50K',
            'name' => 'Voucher Mingguan Rp50.000',
            'persen_diskon' => null,
            'value' => 50000,
            'start_date' => $today->toDateString(),
            'end_date' => $nextWeek->toDateString(),
            'event_id' => null,
            'ticket_type_id' => null,
            'is_active' => 1,
            'created_at' => $today,
            'updated_at' => $today,
        ];

        // 2️⃣ PROMO OTOMATIS UNTUK SETIAP EVENT
        foreach ($events as $eventId) {
            $isPercent = rand(0, 1) === 1;

            $code = strtoupper(substr(md5($eventId . time()), 0, 6));
            $discountValue = $isPercent ? rand(5, 20) : rand(20000, 100000);

            $promotions[] = [
                'code' => 'EVT' . $code,
                'name' => 'Promo Event ' . $eventId,
                'persen_diskon' => $isPercent ? $discountValue : null,
                'value' => $isPercent ? null : $discountValue,
                'start_date' => $today->toDateString(),
                'end_date' => $nextWeek->toDateString(),
                'event_id' => $eventId,
                'ticket_type_id' => null,
                'is_active' => 1,
                'created_at' => $today,
                'updated_at' => $today,
            ];
        }

        // 3️⃣ PROMO SPESIAL UNTUK BEBERAPA TIPE TIKET
        foreach ($ticketTypes as $typeId) {
            if (rand(0, 1)) { // hanya sebagian tipe tiket
                $promotions[] = [
                    'code' => 'TIX' . strtoupper(substr(md5($typeId . 'promo'), 0, 5)),
                    'name' => 'Promo Tiket Spesial ' . $typeId,
                    'persen_diskon' => rand(5, 15),
                    'value' => null,
                    'start_date' => $today->toDateString(),
                    'end_date' => $nextWeek->toDateString(),
                    'event_id' => null,
                    'ticket_type_id' => $typeId,
                    'is_active' => 1,
                    'created_at' => $today,
                    'updated_at' => $today,
                ];
            }
        }

        DB::table('promotions')->insert($promotions);

        $this->command->info(count($promotions) . ' promotions berhasil di-seed untuk minggu ini!');
    }
}
