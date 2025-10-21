<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            VenuesTableSeeder::class,
            EventsTableSeeder::class,
            TicketTypesTableSeeder::class,
            PromotionsTableSeeder::class,
            PaymentMethodsTableSeeder::class,
            NewsSeeder::class,
        ]);
    }
}
