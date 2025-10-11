<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * 🧾 List semua order dengan pagination
     */
    public function index()
    {
        $orders = Order::with('user', 'event', 'payment', 'ticketType')
            ->orderBy('created_at', 'desc')
            ->paginate(10); // gunakan paginate untuk pagination

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * 🔍 Detail order + verifikasi form
     */
    public function show($id)
    {
        $order = Order::with(['user', 'event', 'ticketType', 'payment'])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }


    /**
     * 💳 List metode pembayaran admin
     */
    public function paymentMethods()
    {
        $methods = PaymentMethod::all();
        return view('admin.payment_methods.index', compact('methods'));
    }

    /**
     * 💾 Simpan metode pembayaran baru
     */
    public function paymentMethodsStore(Request $request)
    {
        $request->validate([
            'type' => 'required|in:bank,qris',
            'name' => 'required|string',
            'account_number' => 'nullable|string',
            'account_name' => 'nullable|string',
            'qr_code_image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['type', 'name', 'account_number', 'account_name']);

        if ($request->hasFile('qr_code_image')) {
            $data['qr_code_image'] = $request->file('qr_code_image')->store('payment_methods', 'public');
        }

        PaymentMethod::create($data);

        return redirect()->back()->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    /**
     * 🔹 Form verifikasi pembayaran order
     */
    public function verifyPaymentForm($id)
    {
        $order = Order::with('payment', 'event', 'user', 'ticketType')->findOrFail($id);
        return view('admin.orders.verify', compact('order'));
    }

    /**
     * ✅ Proses verifikasi pembayaran
     */
    public function verifyPayment(Request $request, $id)
    {
        $order = Order::with('payment', 'ticketType')->findOrFail($id);

        $request->validate([
            'status' => 'required|in:verified,rejected',
            'admin_note' => 'nullable|string'
        ]);

        $payment = $order->payment;
        if (!$payment) {
            return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $payment->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
        ]);

        // update status order
        if ($request->status === 'verified') {
            $order->update(['status' => 'paid']);
        } elseif ($request->status === 'rejected') {
            $order->update(['status' => 'cancelled']);
            // kembalikan tiket
            if ($order->ticketType) {
                $order->ticketType->increment('available_tickets', $order->quantity);
            }
        }

        return redirect()->route('admin.orders.index')->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    /**
     * 🗑️ Hapus order
     */
    public function destroy($id)
    {
        $order = Order::with('payment', 'ticketType')->findOrFail($id);

        // hapus bukti pembayaran jika ada
        if ($order->payment && $order->payment->proof_image) {
            Storage::disk('public')->delete($order->payment->proof_image);
        }

        // kembalikan tiket jika pending
        if ($order->status === 'pending' && $order->ticketType) {
            $order->ticketType->increment('available_tickets', $order->quantity);
        }

        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil dihapus.');
    }
}
