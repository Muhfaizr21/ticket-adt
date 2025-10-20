<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class RefundController extends Controller
{
    /**
     * Menampilkan halaman request refund
     */
    public function create(Order $order)
    {
        // Pastikan user yang mengakses adalah pemilik order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('refunds.refund_request', compact('order'));
    }

    /**
     * Menyimpan request refund atau info bank
     */
    public function store(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        // Pastikan user yang mengakses adalah pemilik order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Pastikan order sudah dibayar
        if ($order->status !== 'paid') {
            return redirect()->route('orders.index')
                ->with('error', 'Order belum dibayar, tidak bisa diajukan refund.');
        }

        switch ($order->refund_status) {

            // Jika refund belum diajukan atau sebelumnya ditolak
            case 'none':
            case 'rejected':
                $request->validate([
                    'user_reason' => 'required|string|max:1000',
                ]);

                $order->update([
                    'refund_status' => 'requested',
                    'refund_reason' => $request->user_reason,
                    'refund_reason_admin' => null, // reset catatan admin jika sebelumnya rejected
                    'bank_info' => null,
                ]);

                Notification::create([
                    'title' => 'Permintaan Refund Baru',
                    'message' => 'User ' . Auth::user()->name . ' mengajukan refund untuk pesanan #' . $order->id . '.',
                    'type' => 'refund',
                    'is_read' => false,
                ]);

                return redirect()->route('orders.index')
                    ->with('success', 'Pengajuan refund berhasil dikirim.');

                // Jika refund sudah disetujui admin, user mengirim info biaya / bank
            case 'approved':
                $request->validate([
                    'bank_info' => 'required|string|max:255',
                ]);

                $order->update([
                    // refund_reason tetap sebagai alasan sebelumnya, tidak diubah
                    'bank_info' => $request->bank_info,
                ]);

                Notification::create([
                    'title' => 'Info Refund Diperbarui',
                    'message' => 'User ' . Auth::user()->name . ' mengirim informasi biaya/bank untuk refund pesanan #' . $order->id . '.',
                    'type' => 'refund',
                    'is_read' => false,
                ]);

                return redirect()->route('orders.index')
                    ->with('success', 'Informasi refund berhasil dikirim ke admin.');

                // Jika refund sedang diproses atau status lain
            default:
                return redirect()->route('orders.index')
                    ->with('error', 'Refund tidak dapat diproses.');
        }
    }
}
