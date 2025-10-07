<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // List semua order
    public function index()
    {
        $orders = Order::with('user', 'event')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    // Tampilkan form buat order baru (opsional)
    public function create()
    {
        //
    }

    // Simpan order baru (biasanya dari frontend, bisa diabaikan untuk admin)
    public function store(Request $request)
    {
        //
    }

    // Tampilkan detail order
    public function show($id)
    {
        $order = Order::with('user', 'event')->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    // Tampilkan form edit order (opsional)
    public function edit($id)
    {
        //
    }

    // Update status / data order
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,paid',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Status pesanan berhasil diupdate!');
    }

    // Hapus order
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dihapus!');
    }
}
