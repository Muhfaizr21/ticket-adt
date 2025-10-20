<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class VenuesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $venues = [
            [
                'name' => 'Jakarta Convention Center',
                'city' => 'Jakarta',
                'description' => 'Pusat konferensi dan konser terbesar di Jakarta.'
            ],
            [
                'name' => 'Bali Nusa Dua Convention Center',
                'city' => 'Bali',
                'description' => 'Venue mewah di tepi pantai untuk konser dan event internasional.'
            ],
            [
                'name' => 'Museum Nasional Indonesia',
                'city' => 'Jakarta',
                'description' => 'Venue budaya dengan kapasitas sedang, cocok untuk pertunjukan musik klasik.'
            ],
            [
                'name' => 'Surabaya Exhibition Hall',
                'city' => 'Surabaya',
                'description' => 'Hall besar di Surabaya untuk konser dan pameran.'
            ],
            [
                'name' => 'Bandung Convention Hall',
                'city' => 'Bandung',
                'description' => 'Venue indoor untuk konser musik dan seminar.'
            ],
            [
                'name' => 'Medan Expo Center',
                'city' => 'Medan',
                'description' => 'Pusat event di Medan, sering dipakai konser dan festival lokal.'
            ],
            [
                'name' => 'Makassar International Hall',
                'city' => 'Makassar',
                'description' => 'Hall modern untuk pertunjukan musik dan acara internasional.'
            ],
            [
                'name' => 'Yogyakarta Art Center',
                'city' => 'Yogyakarta',
                'description' => 'Venue seni dan budaya, cocok untuk konser indie dan pertunjukan lokal.'
            ],
            [
                'name' => 'Semarang Convention Center',
                'city' => 'Semarang',
                'description' => 'Venue besar di Semarang untuk konser dan pameran.'
            ],
            [
                'name' => 'Palembang Sport & Expo',
                'city' => 'Palembang',
                'description' => 'Hall multifungsi untuk konser, pameran, dan acara olahraga.'
            ],
        ];

        $data = [];

        foreach ($venues as $venue) {
            // Cek apakah venue sudah ada berdasarkan nama dan kota
            $exists = DB::table('venues')
                ->where('name', $venue['name'])
                ->where('city', $venue['city'])
                ->exists();

            if (!$exists) {
                $data[] = [
                    'name' => $venue['name'],
                    'address' => $faker->streetAddress,
                    'city' => $venue['city'],
                    'capacity' => $faker->numberBetween(100, 5000),
                    'description' => $venue['description'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        if (!empty($data)) {
            DB::table('venues')->insert($data);
            $this->command->info(count($data) . ' venues berhasil di-seed!');
        } else {
            $this->command->info('Semua venues sudah ada, tidak ada yang di-seed.');
        }
    }
}
