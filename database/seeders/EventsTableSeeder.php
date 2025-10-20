<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventsTableSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua venue yang ada
        $venues = DB::table('venues')->pluck('id')->toArray();

        if (empty($venues)) {
            $this->command->info('Tidak ada venue di database, seed venues dulu!');
            return;
        }

        $events = [
            [
                'name' => 'Jakarta Jazz Festival 2025',
                'description' => 'Festival jazz tahunan menghadirkan musisi jazz lokal dan internasional.',
                'date' => '2025-11-10 00:00:00',
                'start_time' => '18:00:00',
                'end_time' => '23:00:00',
                'location' => 'Jakarta Convention Center',
                'available_tickets' => 2000,
                'poster' => 'posters/jakarta-jazz.jpg',
                'venue_id' => $venues[array_rand($venues)],
            ],
            [
                'name' => 'Bali Music Fiesta',
                'description' => 'Konser musik tropis dan pop di tepi pantai Bali.',
                'date' => '2025-12-05 00:00:00',
                'start_time' => '16:00:00',
                'end_time' => '22:00:00',
                'location' => 'Bali Nusa Dua Convention Center',
                'available_tickets' => 1500,
                'poster' => 'posters/bali.jpg',
                'venue_id' => $venues[array_rand($venues)],
            ],
            [
                'name' => 'Yogyakarta Indie Concert',
                'description' => 'Konser musik indie menampilkan band-band lokal Yogyakarta.',
                'date' => '2025-10-25 00:00:00',
                'start_time' => '19:00:00',
                'end_time' => '23:30:00',
                'location' => 'Yogyakarta Art Center',
                'available_tickets' => 500,
                'poster' => 'posters/jogja.jpg',
                'venue_id' => $venues[array_rand($venues)],
            ],
            [
                'name' => 'Surabaya Rock Night',
                'description' => 'Konser rock terbesar di Surabaya dengan band papan atas.',
                'date' => '2025-11-20 00:00:00',
                'start_time' => '20:00:00',
                'end_time' => '01:00:00',
                'location' => 'Surabaya Exhibition Hall',
                'available_tickets' => 800,
                'poster' => 'posters/surabaya.jpg',
                'venue_id' => $venues[array_rand($venues)],
            ],
            [
                'name' => 'Bandung Symphony Evening',
                'description' => 'Pertunjukan orkestra klasik di malam hari di Bandung.',
                'date' => '2025-12-15 00:00:00',
                'start_time' => '18:30:00',
                'end_time' => '21:30:00',
                'location' => 'Bandung Convention Hall',
                'available_tickets' => 400,
                'poster' => 'posters/bandung.png',
                'venue_id' => $venues[array_rand($venues)],
            ],
            [
                'name' => 'Medan Cultural Music Festival',
                'description' => 'Festival musik budaya menampilkan alat musik tradisional dan modern.',
                'date' => '2026-01-10 00:00:00',
                'start_time' => '17:00:00',
                'end_time' => '22:00:00',
                'location' => 'Medan Expo Center',
                'available_tickets' => 600,
                'poster' => 'posters/medan.jpg',
                'venue_id' => $venues[array_rand($venues)],
            ],
            [
                'name' => 'Makassar Beach Concert',
                'description' => 'Konser musik pop dan reggae di pantai Makassar.',
                'date' => '2026-02-05 00:00:00',
                'start_time' => '16:30:00',
                'end_time' => '21:30:00',
                'location' => 'Makassar International Hall',
                'available_tickets' => 700,
                'poster' => 'posters/pee.jpg',
                'venue_id' => $venues[array_rand($venues)],
            ],
            [
                'name' => 'Semarang Indie Night',
                'description' => 'Konser musik indie menghadirkan band lokal Semarang.',
                'date' => '2026-03-12 00:00:00',
                'start_time' => '19:00:00',
                'end_time' => '23:00:00',
                'location' => 'Semarang Convention Center',
                'available_tickets' => 350,
                'poster' => 'posters/semarang.jpeg',
                'venue_id' => $venues[array_rand($venues)],
            ],
            [
                'name' => 'Palembang Jazz & Blues Festival',
                'description' => 'Festival jazz & blues menampilkan musisi nasional.',
                'date' => '2026-04-20 00:00:00',
                'start_time' => '18:00:00',
                'end_time' => '23:00:00',
                'location' => 'Palembang Sport & Expo',
                'available_tickets' => 500,
                'poster' => 'posters/hivi.jpg',
                'venue_id' => $venues[array_rand($venues)],
            ],
        ];

        // Tambahkan timestamp
        foreach ($events as &$event) {
            $event['created_at'] = Carbon::now();
            $event['updated_at'] = Carbon::now();
        }

        DB::table('events')->insert($events);

        $this->command->info(count($events) . ' local events berhasil di-seed!');
    }
}
