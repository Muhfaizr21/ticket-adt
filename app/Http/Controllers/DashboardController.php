<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Promotion;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua event beserta ticket types
        $events = Event::with(['ticketTypes'])->orderBy('date', 'asc')->get();

        $festivalData = $events->map(function ($event) {
            $tickets = $event->ticketTypes->map(function ($ticket) {
                $basePrice = $ticket->price ?? 0;
                $finalPrice = $basePrice;

                // 🔍 Cek apakah ada promo aktif untuk tiket atau event
                $promotion = Promotion::where(function ($q) use ($ticket) {
                    $q->where('ticket_type_id', $ticket->id)
                        ->orWhere('event_id', $ticket->event_id);
                })
                    ->where('is_active', true)
                    ->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->first();

                // 💰 Hitung harga promo jika ada
                if ($promotion) {
                    if (!empty($promotion->persen_diskon)) {
                        $discount = $basePrice * ($promotion->persen_diskon / 100);
                        $finalPrice = max($basePrice - $discount, 0);
                    } elseif (!empty($promotion->value)) {
                        $finalPrice = max($basePrice - $promotion->value, 0);
                    }
                }

                return [
                    'id' => $ticket->id,
                    'type' => $ticket->name,
                    'original_price' => 'Rp ' . number_format($basePrice, 0, ',', '.'),
                    'final_price' => 'Rp ' . number_format($finalPrice, 0, ',', '.'),
                    'has_promo' => (bool) $promotion,
                    'promo_name' => $promotion->name ?? null,
                    'promo_end' => isset($promotion->end_date)
                        ? Carbon::parse($promotion->end_date)->format('d M Y')
                        : null,
                    'available' => $ticket->available_tickets > 0 ? 'Tersedia' : 'Habis',
                ];
            });

            return [
                'id' => $event->id,
                'title' => $event->name,
                'description' => $event->description,
                'date' => $event->date ? $event->date->format('l, d F Y') : 'TBA',
                'time' => ($event->start_time && $event->end_time)
                    ? $event->start_time . ' – ' . $event->end_time . ' WIB'
                    : 'TBA',
                // ✅ Ambil langsung dari kolom `location`
                'location' => $event->location ?? 'Lokasi belum ditentukan',
                'poster' => $event->poster,
                'tickets' => $tickets,
            ];
        });

        return view('pages.dashboard', ['festivalData' => $festivalData]);
    }
}
