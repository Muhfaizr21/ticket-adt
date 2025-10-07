<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HelpTicket;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    // 🟢 Menampilkan halaman bantuan utama
    public function index()
    {
        $faqs = [
            [
                'question' => 'Bagaimana cara menambah event baru?',
                'answer' => 'Masuk ke menu Event > Tambah Event, lalu isi semua data dan simpan.'
            ],
            [
                'question' => 'Bagaimana cara melihat laporan penjualan tiket?',
                'answer' => 'Pergi ke menu Reports, lalu pilih rentang tanggal untuk melihat data penjualan.'
            ],
            [
                'question' => 'Poster event tidak muncul?',
                'answer' => 'Pastikan file gambar berformat JPG/PNG dan tidak lebih dari 2MB.'
            ],
            [
                'question' => 'Bagaimana cara menghubungi tim support?',
                'answer' => 'Anda dapat mengirim email ke <b>support@ticketmaster.id</b> atau melalui formulir di bawah ini.'
            ],
        ];

        return view('admin.help.index', compact('faqs'));
    }

    // 🟢 Menyimpan tiket bantuan dari form
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        HelpTicket::create($validated);

        return back()->with('success', 'Tiket bantuan berhasil dikirim! Tim kami akan segera menghubungi Anda.');
    }

    // 🟢 Halaman admin untuk melihat tiket bantuan
    public function tickets()
    {
        $tickets = HelpTicket::latest()->paginate(10);
        return view('admin.help.tickets', compact('tickets'));
    }

    // 🟢 Menutup tiket
    public function close($id)
    {
        $ticket = HelpTicket::findOrFail($id);
        $ticket->update(['status' => 'closed']);
        return back()->with('success', 'Tiket telah ditandai sebagai selesai.');
    }
}
