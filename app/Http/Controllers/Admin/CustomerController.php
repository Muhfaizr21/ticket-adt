<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class CustomerController extends Controller
{
    // Tampilkan daftar refund
    public function index()
    {
        $refunds = Order::with(['user', 'event', 'ticketType'])
            ->where('refund_status', 'requested')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin.customers.refunds', compact('refunds'));
    }

    // Update status refund (dipanggil dari tombol Setuju/Tolak)
    public function updateRefundStatus(Order $order, $status)
    {
        if (!in_array($status, ['approved', 'rejected'])) {
            return redirect()->back()->with('error', 'Status refund tidak valid.');
        }

        $order->update([
            'refund_status' => $status,
            'refunded_at' => $status === 'approved' ? now() : null,
        ]);

        return redirect()->back()->with('success', 'Status refund berhasil diperbarui.');
    }
}
