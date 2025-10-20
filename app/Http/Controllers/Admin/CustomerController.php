<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class CustomerController extends Controller
{
    /**
     * Tampilkan daftar semua refund
     */
    public function index()
    {
        $refunds = Order::with(['user', 'event', 'ticketType'])
            ->whereIn('refund_status', ['requested', 'approved', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin.customers.refunds', compact('refunds'));
    }

    /**
     * Update status refund (dipanggil dari tombol Setuju/Tolak)
     */
    public function updateRefundStatus(Order $order, $status)
    {
        if (!in_array($status, ['approved', 'rejected'])) {
            return redirect()->back()->with('error', 'Status refund tidak valid.');
        }

        // Hanya boleh update jika status sekarang 'requested'
        if ($order->refund_status !== 'requested') {
            return redirect()->back()->with('error', 'Refund sudah diproses sebelumnya.');
        }

        $updateData = ['refund_status' => $status];
        if ($status === 'approved') {
            $updateData['refunded_at'] = now();
        }

        $order->update($updateData);

        return redirect()->back()->with('success', 'Status refund berhasil diperbarui.');
    }
}
