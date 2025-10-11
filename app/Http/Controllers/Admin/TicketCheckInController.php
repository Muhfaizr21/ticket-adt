<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class TicketCheckInController extends Controller
{
    public function index()
    {
        // Semua tiket yang sudah dibayar dan pembayaran verified
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

        $order = Order::with('user', 'payment')
            ->where('barcode_code', $request->barcode_code)
            ->where('status', 'paid')
            ->whereHas('payment', fn($q) => $q->where('status', 'verified'))
            ->first();

        if (!$order) return response()->json(['status' => 'error', 'message' => 'Tiket tidak ditemukan atau belum dibayar!']);

        if ($order->checked_in_at) return response()->json(['status' => 'error', 'message' => 'Tiket sudah digunakan.']);

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
    }
}
