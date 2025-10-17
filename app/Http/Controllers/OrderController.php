<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Event;
use App\Models\TicketType;
use App\Models\Promotion;
use App\Models\OrderPayment;
use App\Models\PaymentMethod;
use App\Models\Notification; // ✅ Tambahan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Milon\Barcode\DNS1D;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['event', 'ticketType', 'payment'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function create($event_id)
    {
        $event = Event::with('ticketTypes')->findOrFail($event_id);
        $paymentMethods = PaymentMethod::all();

        return view('orders.create', compact('event', 'paymentMethods'));
    }

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

        if ($request->promo_code) {
            $promo = Promotion::where('code', $request->promo_code)
                ->where('is_active', true)
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->first();

            if ($promo) {
                $finalPrice -= $promo->persen_diskon
                    ? ($ticket->price * $promo->persen_diskon / 100)
                    : $promo->value ?? 0;
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

        // ✅ Buat notifikasi admin
        Notification::create([
            'title' => 'Pesanan Baru',
            'message' => 'User ' . Auth::user()->name . ' telah membuat pesanan baru untuk event ' . $order->event->name . '.',
            'type' => 'order',
            'is_read' => false,
        ]);

        return redirect()->route('orders.upload', $order->id)
            ->with('success', 'Order berhasil dibuat! Silakan upload bukti pembayaran.');
    }

    public function uploadForm($id)
    {
        $order = Order::with('payment')->where('user_id', Auth::id())->findOrFail($id);
        return view('orders.upload', compact('order'));
    }

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

        // ✅ Notifikasi admin upload bukti pembayaran
        Notification::create([
            'title' => 'Menunggu Verifikasi Pembayaran',
            'message' => 'User ' . Auth::user()->name . ' telah mengupload bukti pembayaran untuk pesanan #' . $order->id . '.',
            'type' => 'payment',
            'is_read' => false,
        ]);

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu verifikasi admin.');
    }

    public function show($id)
    {
        $order = Order::with(['event', 'ticketType', 'payment'])->findOrFail($id);
        if ($order->user_id != Auth::id()) abort(403);
        return view('orders.show', compact('order'));
    }

    public function downloadBarcode($id)
    {
        $order = Order::findOrFail($id);

        if (!$order->payment || $order->payment->status !== 'verified') {
            return redirect()->back()->with('error', 'Barcode hanya bisa diunduh setelah pembayaran diverifikasi.');
        }

        $barcode = new DNS1D();
        $barcodeImage = $barcode->getBarcodePNG($order->barcode_code, 'C39+', 3, 100, [0, 0, 0], true);

        $fileName = 'barcode-' . $order->barcode_code . '.png';
        $fileData = base64_decode($barcodeImage);

        return Response::make($fileData, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ]);
    }

    public function verifyTicket(Request $request)
    {
        $request->validate([
            'barcode_code' => 'required|string',
        ]);

        $order = Order::where('barcode_code', $request->barcode_code)->first();
        if (!$order) return response()->json(['status' => 'error', 'message' => 'Tiket tidak ditemukan!']);
        if ($order->checked_in_at) {
            return response()->json(['status' => 'error', 'message' => 'Tiket sudah digunakan!']);
        }

        $order->update(['checked_in_at' => now()]);

        return response()->json(['status' => 'success', 'message' => 'Tiket berhasil check-in!', 'order_id' => $order->id]);
    }
}
