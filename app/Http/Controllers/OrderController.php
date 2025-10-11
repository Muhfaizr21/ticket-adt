<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Event;
use App\Models\TicketType;
use App\Models\Promotion;
use App\Models\OrderPayment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // 🧾 List semua order user
    public function index()
    {
        $orders = Order::with(['event', 'ticketType', 'payment'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    // 🛒 Form buat order baru
    public function create($event_id)
    {
        $event = Event::with('ticketTypes')->findOrFail($event_id);

        // Ambil semua metode pembayaran dari DB
        $paymentMethods = PaymentMethod::all();

        return view('orders.create', compact('event', 'paymentMethods'));
    }

    // 💾 Simpan order baru + payment info
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'promo_code' => 'nullable|string',
        ]);

        $ticket = TicketType::findOrFail($request->ticket_type_id);
        $finalPrice = $ticket->price;

        // 🔹 Cek promo
        if ($request->promo_code) {
            $promo = Promotion::where('code', $request->promo_code)
                ->where('is_active', true)
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->first();

            if ($promo) {
                if ($promo->persen_diskon) {
                    $finalPrice -= ($ticket->price * $promo->persen_diskon / 100);
                } elseif ($promo->value) {
                    $finalPrice -= $promo->value;
                }
            } else {
                return back()->with('error', 'Kode promo tidak valid atau kadaluarsa.');
            }
        }

        $finalPrice = max($finalPrice, 0);

        $barcode = (string) Str::uuid();

        $order = Order::create([
            'user_id' => Auth::id(),
            'event_id' => $request->event_id,
            'ticket_type_id' => $ticket->id,
            'quantity' => $request->quantity,
            'total_price' => $finalPrice * $request->quantity,
            'barcode_code' => $barcode,
            'status' => 'pending',
            'refund_status' => 'none',
        ]);

        OrderPayment::create([
            'order_id' => $order->id,
            'bank_name' => $request->payment_method,
            'account_name' => null,
            'proof_image' => null,
            'status' => 'pending',
        ]);

        $ticket->decrement('available_tickets', $request->quantity);

        return redirect()->route('orders.upload', $order->id)
            ->with('success', 'Order berhasil dibuat! Silakan upload bukti pembayaran.');
    }

    // 📤 Form upload bukti pembayaran
    public function uploadForm($id)
    {
        $order = Order::with('payment')->where('user_id', Auth::id())->findOrFail($id);
        return view('orders.upload', compact('order'));
    }

    // 💳 Upload bukti pembayaran
    public function uploadPayment(Request $request, $id)
    {
        $order = Order::with('payment')->where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'account_name' => 'required|string|max:100',
            'proof_image' => 'required|image|max:2048',
        ]);

        if ($order->payment->proof_image) {
            Storage::disk('public')->delete($order->payment->proof_image);
        }

        $path = $request->file('proof_image')->store('payments', 'public');

        $order->payment->update([
            'account_name' => $request->account_name,
            'proof_image' => $path,
            'status' => 'pending',
        ]);

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu verifikasi admin.');
    }

    // 🔍 Detail order user
    public function show($id)
    {
        $order = Order::with(['event', 'ticketType', 'payment'])->findOrFail($id);

        if ($order->user_id != Auth::id()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }
}
