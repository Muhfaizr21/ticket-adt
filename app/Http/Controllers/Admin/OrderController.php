<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;


class OrderController extends Controller
{
    /**
     * Tampilkan semua data pembelian tiket
     */
    public function index()
    {
        $orders = Order::with(['user', 'event'])->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Hapus data pesanan
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil dihapus!');
    }

    /**
     * Detail pesanan
     */
    public function show($id)
    {
        $order = Order::with(['user', 'event'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }
}

