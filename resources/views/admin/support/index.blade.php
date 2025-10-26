@extends('admin.layouts.app')

@section('content')
<div class="container py-5">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">📘 Help & Support - TiketinADT</h2>
        <span class="badge bg-primary fs-6 px-3 py-2">Admin Panel</span>
    </div>

    <p class="text-muted mb-5">
        Halaman ini berisi panduan lengkap bagi Admin TiketinADT dalam mengelola sistem tiketing berbasis web untuk pengelolaan acara dan pemesanan tiket.
    </p>

    {{-- Accordion --}}
    <div class="accordion" id="supportAccordion">
        @php
            $topics = [
                [
                    'title' => '🔑 Login & Dashboard Admin',
                    'description' => 'Panduan untuk masuk dan mengenal tampilan utama sistem.',
                    'content' => 'Admin dapat login menggunakan akun terdaftar. Setelah login, admin diarahkan ke dashboard utama yang menampilkan ringkasan jumlah event, pesanan, user, dan laporan penjualan tiket secara real-time.'
                ],
                [
                    'title' => '🎟️ Kelola Event',
                    'description' => 'Menambahkan, mengedit, dan menghapus event.',
                    'content' => 'Masuk ke menu Event → Klik "Tambah Event" untuk membuat event baru. Admin dapat mengisi informasi seperti nama event, tanggal, lokasi, kapasitas, dan poster. Event yang sudah ada dapat diedit atau dihapus melalui tombol Aksi di tabel event.'
                ],
                [
                    'title' => '💳 Kelola Order Tiket',
                    'description' => 'Melihat dan memverifikasi pesanan tiket pengguna.',
                    'content' => 'Buka menu "Order Tiket" untuk melihat daftar pemesanan. Klik tombol "Detail" untuk memverifikasi bukti pembayaran pengguna. Setelah diverifikasi, status order berubah menjadi "Sukses" dan tiket dapat diakses pengguna.'
                ],
                [
                    'title' => '🔁 Refund Tiket',
                    'description' => 'Mengelola pengajuan refund dari pengguna.',
                    'content' => 'Admin dapat melihat daftar pengajuan refund di menu "Customers". Status refund mencakup: Requested (diajukan), Approved (disetujui), dan Refunded (dana telah dikembalikan). Admin bisa menyetujui atau menolak refund berdasarkan alasan yang diajukan user.'
                ],
                [
                    'title' => '📱 Check-in Event',
                    'description' => 'Verifikasi tiket saat acara berlangsung.',
                    'content' => 'Pada menu "Check-in", admin dapat memindai atau memverifikasi QR Code tiket pengguna. Jika valid, status tiket berubah dari "Pending" menjadi "Checked-in" secara otomatis.'
                ],
                [
                    'title' => '🏟️ Kelola Venue',
                    'description' => 'Menambah, mengedit, dan menghapus lokasi acara.',
                    'content' => 'Admin dapat membuka menu "Venues" untuk menambahkan lokasi baru dengan kapasitas, alamat, dan keterangan tambahan. Venue juga dapat diperbarui atau dihapus sesuai kebutuhan.'
                ],
                [
                    'title' => '🎫 Ticket Types',
                    'description' => 'Menentukan jenis dan harga tiket pada setiap event.',
                    'content' => 'Pada menu "Ticket Types", admin dapat menambahkan tipe tiket seperti VIP, Regular, atau Presale. Setiap tipe memiliki harga dan stok tersendiri yang terhubung dengan event terkait.'
                ],
                [
                    'title' => '💥 Promotions',
                    'description' => 'Mengatur promo tiket event.',
                    'content' => 'Admin dapat menambahkan promo pada tipe tiket tertentu dengan menentukan periode promo dan potongan harga. Promo yang aktif akan muncul otomatis pada halaman user selama masa berlaku.'
                ],
                [
                    'title' => '📊 Report & Laporan Penjualan',
                    'description' => 'Melihat data laporan penjualan tiket.',
                    'content' => 'Admin dapat memfilter laporan berdasarkan tanggal, event, atau status pembayaran. Hasil laporan menampilkan total tiket terjual dan pendapatan, berguna untuk evaluasi penjualan.'
                ],
                [
                    'title' => '💬 Aduan Pengguna',
                    'description' => 'Meninjau pesan dan keluhan dari pengguna.',
                    'content' => 'Pada menu "Aduan Pengguna", admin dapat melihat pesan atau kendala yang dikirim oleh user dan memberikan tindak lanjut atau balasan sesuai permasalahan yang dihadapi.'
                ],
                [
                    'title' => '💰 Metode Pembayaran',
                    'description' => 'Menambahkan dan mengelola metode pembayaran.',
                    'content' => 'Admin harus menambahkan metode pembayaran seperti transfer bank, e-wallet, atau QRIS agar bisa digunakan pengguna saat checkout. Dapat diubah kapan saja di menu "Payment Methods".'
                ],
                [
                    'title' => '📰 Manage News',
                    'description' => 'Mengelola berita dan informasi event.',
                    'content' => 'Menu "Manage News" memungkinkan admin menambahkan atau mengedit berita terkait event yang telah berlangsung atau yang akan datang. Informasi ini tampil di halaman News untuk pengguna.'
                ],
                [
                    'title' => '⚙️ Settings & Profil Admin',
                    'description' => 'Mengatur profil dan preferensi admin.',
                    'content' => 'Admin dapat mengubah foto profil, memperbarui password, serta melakukan pengaturan sistem seperti notifikasi dan informasi kontak di halaman Settings.'
                ],
                [
                    'title' => '🔔 Notifikasi Sistem',
                    'description' => 'Memantau aktivitas terbaru pengguna.',
                    'content' => 'Admin akan menerima notifikasi otomatis untuk setiap order baru, pengajuan refund, atau laporan user. Notifikasi dapat ditandai sebagai sudah dibaca setelah ditinjau.'
                ],
            ];
        @endphp

        @foreach ($topics as $index => $topic)
            <div class="accordion-item mb-3 shadow-sm rounded">
                <h2 class="accordion-header" id="heading{{ $index }}">
                    <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $index }}"
                            aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                            aria-controls="collapse{{ $index }}">
                        <strong>{{ $topic['title'] }}</strong>
                    </button>
                </h2>
                <div id="collapse{{ $index }}"
                     class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }} animate-collapse"
                     aria-labelledby="heading{{ $index }}"
                     data-bs-parent="#supportAccordion">
                    <div class="accordion-body">
                        <p class="fw-semibold text-secondary">{{ $topic['description'] }}</p>
                        <hr>
                        <p class="mb-0">{{ $topic['content'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Contact --}}
    <div class="mt-5 text-center">
        <h5 class="fw-bold">Masih butuh bantuan?</h5>
        <p class="text-muted">Hubungi tim teknis TiketinADT melalui email berikut:</p>
        <a href="mailto:support@tiketinadt.local" class="btn btn-primary px-4 py-2">
            📧 support@tiketinadt.local
        </a>
        <div class="mt-3 text-muted small">Kami siap membantu Anda kapan saja 🚀</div>
    </div>
</div>
@endsection
