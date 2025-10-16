<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    // 📝 List semua metode pembayaran
    public function index()
    {
        $methods = PaymentMethod::orderBy('created_at', 'desc')->get();
        return view('admin.payment_methods.index', compact('methods'));
    }

    // 📝 Form tambah metode pembayaran
    public function create()
    {
        return view('admin.payment_methods.create');
    }

    // 📝 Simpan metode pembayaran baru
    public function store(Request $request)
    {
        $request->validate([
            'type'           => 'required|in:bank,qris',
            'name'           => 'required|string|max:100',
            'account_number' => 'required_if:type,bank|string|max:50|nullable',
            'account_name'   => 'required_if:type,bank|string|max:100|nullable',
            'qr_code_image'  => 'required_if:type,qris|image|mimes:jpg,jpeg,png|max:2048|nullable',
        ]);

        $data = $request->only(['type', 'name', 'account_number', 'account_name']);

        // Simpan QR code jika ada
        if ($request->hasFile('qr_code_image')) {
            $data['qr_code_image'] = $request->file('qr_code_image')->store('qris', 'public');
        }

        PaymentMethod::create($data);

        return redirect()->route('admin.payment_methods.index')
            ->with('success', '✅ Metode pembayaran berhasil ditambahkan!');
    }

    // 📝 Form edit metode pembayaran
    public function edit($id)
    {
        $method = PaymentMethod::findOrFail($id);
        return view('admin.payment_methods.edit', compact('method'));
    }

    // 📝 Update metode pembayaran
    public function update(Request $request, $id)
    {
        $method = PaymentMethod::findOrFail($id);

        $request->validate([
            'type'           => 'required|in:bank,qris',
            'name'           => 'required|string|max:100',
            'account_number' => 'required_if:type,bank|string|max:50|nullable',
            'account_name'   => 'required_if:type,bank|string|max:100|nullable',
            'qr_code_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['type', 'name', 'account_number', 'account_name']);

        // Ganti QR code jika ada
        if ($request->hasFile('qr_code_image')) {
            // Hapus file lama
            if ($method->qr_code_image && Storage::disk('public')->exists($method->qr_code_image)) {
                Storage::disk('public')->delete($method->qr_code_image);
            }
            $data['qr_code_image'] = $request->file('qr_code_image')->store('qris', 'public');
        }

        $method->update($data);

        return redirect()->route('admin.payment_methods.index')
            ->with('success', '✅ Metode pembayaran berhasil diperbarui!');
    }

    // 📝 Hapus metode pembayaran
    public function destroy($id)
    {
        $method = PaymentMethod::findOrFail($id);
        $method->delete(); // QR code otomatis terhapus lewat model

        return redirect()->route('admin.payment_methods.index')
            ->with('success', '🗑️ Metode pembayaran berhasil dihapus!');
    }
}
