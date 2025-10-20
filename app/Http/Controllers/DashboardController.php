<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Promotion;
use App\Models\TicketType;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * 🏠 Halaman dashboard menampilkan semua event.
     */
    public function index()
    {
        $events = Event::with(['ticketTypes', 'ticketTypes.promotions'])
            ->orderBy('date', 'asc')
            ->get();

        $festivalData = $events->map(function ($event) use ($events) {

            $tickets = $event->ticketTypes->map(function ($ticket) {
                $basePrice = $ticket->price ?? 0;
                $finalPrice = $basePrice;

                $promotion = $ticket->promotions
                    ->where('is_active', true)
                    ->filter(fn($promo) => $promo->start_date <= now() && $promo->end_date >= now())
                    ->first();

                if ($promotion) {
                    if (!empty($promotion->persen_diskon)) {
                        $finalPrice = max($basePrice - ($basePrice * $promotion->persen_diskon / 100), 0);
                    } elseif (!empty($promotion->value)) {
                        $finalPrice = max($basePrice - $promotion->value, 0);
                    }
                }

                return [
                    'id' => $ticket->id,
                    'type' => $ticket->name,
                    'original_price' => $basePrice,
                    'final_price' => $finalPrice,
                    'has_promo' => (bool) $promotion,
                    'promo_name' => $promotion->name ?? null,
                    'promo_end' => $promotion ? Carbon::parse($promotion->end_date)->format('d M Y') : null,
                    'available' => $ticket->available_tickets > 0 ? 'Tersedia' : 'Habis',
                ];
            });

            $similar_events = $events->filter(fn($sim) => $sim->location === $event->location && $sim->id !== $event->id)
                ->take(5)
                ->map(fn($sim) => [
                    'id' => $sim->id,
                    'title' => $sim->name,
                    'poster' => $sim->poster,
                    'location' => $sim->location,
                    'price' => optional($sim->ticketTypes->first())->price,
                ]);

            return [
                'id' => $event->id,
                'title' => $event->name,
                'description' => $event->description,
                'date' => $event->date ? $event->date->format('l, d F Y') : 'TBA',
                'time' => ($event->start_time && $event->end_time) ? $event->start_time . ' – ' . $event->end_time . ' WIB' : 'TBA',
                'location' => $event->location ?? 'Lokasi belum ditentukan',
                'poster' => $event->poster,
                'tickets' => $tickets,
                'similar_events' => $similar_events,
                'min_age' => $event->min_age ?? 17,
                'side_images' => $event->side_images ?? [],
                'rating' => $event->rating ?? 4.5,
            ];
        });

        $paymentMethods = PaymentMethod::all();

        return view('pages.dashboard', compact('festivalData', 'paymentMethods'));
    }

    /**
     * 🎟 Halaman detail event tunggal
     */
    public function show($id)
    {
        $event = Event::with(['ticketTypes', 'ticketTypes.promotions'])->findOrFail($id);

        // Format tickets
        $tickets = $event->ticketTypes->map(function ($ticket) {
            $basePrice = $ticket->price ?? 0;
            $finalPrice = $basePrice;

            $promotion = $ticket->promotions
                ->where('is_active', true)
                ->filter(function ($promo) {
                    $today = now();
                    return $promo->start_date <= $today && $promo->end_date >= $today;
                })
                ->first();

            if ($promotion) {
                if ($promotion->persen_diskon) {
                    $finalPrice = max($basePrice - ($basePrice * $promotion->persen_diskon / 100), 0);
                } elseif ($promotion->value) {
                    $finalPrice = max($basePrice - $promotion->value, 0);
                }
            }

            return [
                'id' => $ticket->id,
                'type' => $ticket->name,
                'original_price' => $basePrice,
                'final_price' => $finalPrice,
                'has_promo' => (bool) $promotion,
                'promo_name' => $promotion->name ?? null,
                'promo_end' => $promotion ? Carbon::parse($promotion->end_date)->format('d M Y') : null,
                'available' => $ticket->available_tickets > 0 ? 'Tersedia' : 'Habis',
            ];
        });

        // Similar events
        $similar_events = Event::where('location', $event->location)
            ->where('id', '!=', $event->id)
            ->take(5)
            ->get()
            ->map(function ($sim) {
                return [
                    'id' => $sim->id,
                    'title' => $sim->name,
                    'poster' => $sim->poster,
                    'location' => $sim->location,
                    'price' => optional($sim->ticketTypes->first())->price,
                ];
            });

        $event_data = [
            'id' => $event->id,
            'title' => $event->name,
            'description' => $event->description,
            'date' => $event->date ? $event->date->format('l, d F Y') : 'TBA',
            'time' => ($event->start_time && $event->end_time) ? $event->start_time . ' – ' . $event->end_time : 'TBA',
            'location' => $event->location ?? 'Lokasi belum ditentukan',
            'poster' => $event->poster,
            'tickets' => $tickets,
            'similar_events' => $similar_events,
            'min_age' => $event->min_age ?? 17,
            'rating' => $event->rating ?? 4.5,
        ];

        return view('pengguna.event-detail', ['event' => $event_data]);
    }


    /**
     * 🎫 Form pembelian tiket
     */
    public function buyTicket($event_id, $ticket_type_id)
    {
        $event = Event::findOrFail($event_id);
        $ticketType = TicketType::findOrFail($ticket_type_id);

        return view('orders.create', compact('event', 'ticketType'));
    }

    /**
     * 🧾 Simpan order tiket
     */
    public function storeTicketOrder(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $ticketType = TicketType::findOrFail($request->ticket_type_id);
        $totalPrice = $ticketType->price * $request->quantity;

        $order = Order::create([
            'user_id' => Auth::id(),
            'event_id' => $request->event_id,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'barcode' => Str::uuid(),
        ]);

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Tiket berhasil dipesan!');
    }

    /**
     * 🔍 Pencarian event
     */
    public function search(Request $request)
    {
        $query = Event::query();
        $search = $request->input('q');
        $filter = $request->input('filter');

        if ($search) {
            switch ($filter) {
                case 'location':
                    $query->where('location', 'like', "%{$search}%");
                    break;
                case 'date':
                    $query->whereDate('date', $search);
                    break;
                case 'name':
                    $query->where('name', 'like', "%{$search}%");
                    break;
                default:
                    $query->where(fn($q) => $q->where('name', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%"));
            }
        }

        $events = $query->orderBy('date', 'asc')->get();

        return view('pengguna.event-search', compact('events', 'search', 'filter'));
    }
}
