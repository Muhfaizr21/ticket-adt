<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class TicketCheckInController extends Controller
{
    public function index()
    {
        // Ambil semua tiket yang sudah dibayar dan pembayaran verified
        $orders = Order::with('user', 'payment')
            ->where('status', 'paid')
            ->whereHas('payment', fn($q) => $q->where('status', 'verified'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.tickets.check_in', compact('orders'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'barcode_code' => 'required|string'
        ]);

        // Bersihkan barcode
        $barcode = strtolower(trim($request->barcode_code));
        $barcode = preg_replace('/[^a-f0-9-]/', '', $barcode); // hanya hex + dash

        try {
            $order = Order::with('user', 'payment')
                ->where('barcode_code', $barcode)
                ->where('status', 'paid')
                ->whereHas('payment', fn($q) => $q->where('status', 'verified'))
                ->first();

            if (!$order) {
                return response()->json(['status' => 'error', 'message' => 'Tiket tidak ditemukan atau belum dibayar!']);
            }

            if ($order->checked_in_at) {
                return response()->json(['status' => 'error', 'message' => 'Tiket sudah digunakan.']);
            }

            $order->checked_in_at = now();
            $order->checked_in_by = auth()->id();
            $order->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Tiket berhasil diverifikasi!',
                'order_id' => $order->id,
                'user_name' => $order->user->name,
                'barcode_code' => $order->barcode_code
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Server Error: ' . $e->getMessage()]);
        }
    }
}
