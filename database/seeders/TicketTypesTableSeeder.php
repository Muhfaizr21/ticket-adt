<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TicketTypesTableSeeder extends Seeder
{
    public function run()
    {
        $events = DB::table('events')->pluck('id')->toArray();

        if (empty($events)) {
            $this->command->info('Tidak ada event di database, seed events dulu!');
            return;
        }

        $ticketTypes = [];

        foreach ($events as $eventId) {
            $ticketTypes[] = [
                'event_id' => $eventId,
                'name' => 'Regular',
                'description' => 'Tiket masuk reguler untuk acara ini.',
                'price' => 150000,
                'total_tickets' => 500,
                'available_tickets' => 500,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            $ticketTypes[] = [
                'event_id' => $eventId,
                'name' => 'VIP',
                'description' => 'Tiket VIP dengan tempat duduk premium dan fasilitas eksklusif.',
                'price' => 350000,
                'total_tickets' => 200,
                'available_tickets' => 200,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            $ticketTypes[] = [
                'event_id' => $eventId,
                'name' => 'VVIP',
                'description' => 'Tiket VVIP dengan akses backstage dan area lounge.',
                'price' => 600000,
                'total_tickets' => 100,
                'available_tickets' => 100,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('ticket_types')->insert($ticketTypes);

        $this->command->info(count($ticketTypes) . ' ticket types berhasil di-seed!');
    }
}
