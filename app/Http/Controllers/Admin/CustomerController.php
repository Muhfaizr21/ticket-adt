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
        if (!in_array($status, ['approved', 'rejected', 'refunded'])) {
            return redirect()->back()->with('error', 'Status refund tidak valid.');
        }

        // Jika status masih 'requested' atau 'approved', bisa diupdate
        if ($status === 'approved' && $order->refund_status !== 'requested') {
            return redirect()->back()->with('error', 'Hanya refund yang baru diajukan bisa disetujui.');
        }

        if ($status === 'rejected' && $order->refund_status !== 'requested') {
            return redirect()->back()->with('error', 'Hanya refund yang baru diajukan bisa ditolak.');
        }

        if ($status === 'refunded' && $order->refund_status !== 'approved') {
            return redirect()->back()->with('error', 'Hanya refund yang sudah disetujui bisa ditandai refunded.');
        }

        $updateData = ['refund_status' => $status];

        if ($status === 'refunded') {
            $updateData['refunded_at'] = now();
        }

        $order->update($updateData);

        return redirect()->back()->with('success', 'Status refund berhasil diperbarui.');
    }
}
