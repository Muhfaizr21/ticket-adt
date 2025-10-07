<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * Halaman utama Bantuan & Dokumentasi
     */
    public function index()
    {
        // Daftar topik bantuan (bisa juga ambil dari database nanti)
        $topics = [
            [
                'title' => 'Cara Membuat Event',
                'description' => 'Panduan langkah demi langkah untuk membuat event baru di sistem TicketMaster.',
                'content' => 'Masuk ke menu Event > Tambah Event, isi form sesuai data yang dibutuhkan, lalu simpan. Pastikan mengunggah poster dengan format .jpg atau .png.'
            ],
            [
                'title' => 'Cara Melihat Laporan Penjualan Tiket',
                'description' => 'Menampilkan data laporan penjualan dalam bentuk tabel dan grafik.',
                'content' => 'Masuk ke menu Reports. Di sana Anda dapat melihat jumlah tiket terjual, total pendapatan, dan filter berdasarkan tanggal.'
            ],
            [
                'title' => 'Manajemen Pengguna',
                'description' => 'Panduan mengelola akun admin dan user.',
                'content' => 'Akses menu Users untuk menambah, mengedit, atau menghapus akun pengguna. Pastikan hanya user terpercaya yang diberikan peran Admin.'
            ],
            [
                'title' => 'Mengatasi Error Upload Gambar',
                'description' => 'Langkah-langkah jika terjadi error saat upload file.',
                'content' => 'Pastikan ukuran file tidak melebihi 2MB dan format file sesuai (.jpg, .jpeg, .png). Jika masih error, coba hapus cache browser atau ganti perangkat.'
            ],
        ];

        return view('admin.support.index', compact('topics'));
    }
}
