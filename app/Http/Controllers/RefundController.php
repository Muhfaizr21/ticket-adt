<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Notification; // ✅ Tambahan
use Illuminate\Support\Facades\Auth;

class RefundController extends Controller
{
    public function create(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($order->status !== 'paid') {
            return redirect()->route('orders.index')->with('error', 'Order belum dibayar, tidak bisa diajukan refund.');
        }

        if ($order->refund_status !== 'none') {
            return redirect()->route('orders.index')->with('error', 'Refund sudah diajukan atau disetujui.');
        }

        return view('refunds.refund_request', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'reason' => 'required|string|max:1000',
        ]);

        $order = Order::findOrFail($request->order_id);

        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($order->status !== 'paid') {
            return redirect()->route('orders.index')->with('error', 'Order belum dibayar, tidak bisa diajukan refund.');
        }

        if ($order->refund_status !== 'none') {
            return redirect()->route('orders.index')->with('error', 'Refund sudah diajukan atau disetujui.');
        }

        $order->update([
            'refund_status' => 'requested',
            'refund_reason' => $request->reason,
        ]);

        // ✅ Notifikasi admin permintaan refund
        Notification::create([
            'title' => 'Permintaan Refund Baru',
            'message' => 'User ' . Auth::user()->name . ' mengajukan refund untuk pesanan #' . $order->id . '.',
            'type' => 'refund',
            'is_read' => false,
        ]);

        return redirect()->route('orders.index')->with('success', 'Pengajuan refund berhasil dikirim.');
    }
}
