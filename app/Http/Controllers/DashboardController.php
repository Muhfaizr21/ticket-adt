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
     * 🏠 Tampilkan halaman dashboard berisi event, tiket, dan metode pembayaran.
     */
    public function index()
    {
        $events = Event::with(['ticketTypes'])->orderBy('date', 'asc')->get();

        $festivalData = $events->map(function ($event) {
            $tickets = $event->ticketTypes->map(function ($ticket) {
                $basePrice = $ticket->price ?? 0;
                $finalPrice = $basePrice;

                $promotion = Promotion::where(function ($q) use ($ticket) {
                    $q->where('ticket_type_id', $ticket->id)
                        ->orWhere('event_id', $ticket->event_id);
                })
                    ->where('is_active', true)
                    ->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->first();

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
                    'original_price' => $basePrice,
                    'final_price' => $finalPrice,
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
                'location' => $event->location ?? 'Lokasi belum ditentukan',
                'poster' => $event->poster,
                'tickets' => $tickets,
            ];
        });

        $paymentMethods = PaymentMethod::all();

        return view('pages.dashboard', [
            'festivalData' => $festivalData,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    /**
     * 🎟 Tampilkan detail event
     */
    public function show($id)
    {
        $event = Event::with('ticketTypes')->findOrFail($id);
        return view('pengguna.event-detail', compact('event'));
    }

    /**
     * 🎫 Menampilkan form pembelian tiket
     */
    public function buyTicket($event_id, $ticket_type_id)
    {
        $event = Event::findOrFail($event_id);
        $ticketType = TicketType::findOrFail($ticket_type_id);

        return view('orders.create', compact('event', 'ticketType'));
    }

    /**
     * 🧾 Simpan order tiket ke database
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
     * 🔍 Fitur pencarian event (nama, tanggal, lokasi)
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
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('location', 'like', "%{$search}%");
                    });
            }
        }

        $events = $query->orderBy('date', 'asc')->get();

        return view('pengguna.event-search', compact('events', 'search', 'filter'));
    }
}
