<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class RefundController extends Controller
{
    // Tampilkan form pengajuan refund
    public function create(Order $order)
    {
        // Pastikan user adalah pemilik order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Pastikan order sudah dibayar
        if ($order->status !== 'paid') {
            return redirect()->route('orders.index')->with('error', 'Order belum dibayar, tidak bisa diajukan refund.');
        }

        // Pastikan belum ada refund diajukan
        if ($order->refund_status !== 'none') {
            return redirect()->route('orders.index')->with('error', 'Refund sudah diajukan atau disetujui.');
        }

        return view('refunds.refund_request', compact('order'));
    }

    // Simpan pengajuan refund
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'reason' => 'required|string|max:1000',
        ]);

        $order = Order::findOrFail($request->order_id);

        // Pastikan user adalah pemilik order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Pastikan order sudah dibayar
        if ($order->status !== 'paid') {
            return redirect()->route('orders.index')->with('error', 'Order belum dibayar, tidak bisa diajukan refund.');
        }

        // Pastikan belum ada refund diajukan
        if ($order->refund_status !== 'none') {
            return redirect()->route('orders.index')->with('error', 'Refund sudah diajukan atau disetujui.');
        }

        // Update status refund dan simpan alasan
        $order->update([
            'refund_status' => 'requested',
            'refund_reason' => $request->reason,
        ]);

        return redirect()->route('orders.index')->with('success', 'Pengajuan refund berhasil dikirim.');
    }
}
