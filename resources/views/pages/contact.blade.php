@extends('layouts.app')

@section('title', 'Contact Us')
@section('page-title', 'Contact Us')

@push('styles')
<style>
    /* ===========================
       GLOBAL STYLE
    =========================== */
    body {
        font-family: 'Poppins', sans-serif;
    }

    .contact-section {
        max-width: 1200px;
        margin: 0 auto;
        padding: 70px 20px;
    }

    /* ===========================
       HERO / HEADER
    =========================== */
    .contact-hero {
        text-align: center;
        margin-bottom: 60px;
    }

    .contact-title {
        font-size: 3rem;
        font-weight: 800;
        color: #1f2937;
        letter-spacing: -1px;
    }

    .contact-subtitle {
        font-size: 1.2rem;
        color: #6b7280;
        margin-top: 12px;
        max-width: 650px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }

    /* ===========================
       GRID LAYOUT
    =========================== */
    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
    }

    .contact-info,
    .contact-form {
        background: #ffffff;
        border-radius: 16px;
        padding: 35px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        transition: all 0.4s ease;
    }

    .contact-info:hover,
    .contact-form:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    /* ===========================
       CONTACT INFO LEFT
    =========================== */
    .info-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 25px;
    }

    .info-text {
        display: flex;
        align-items: center;
        gap: 14px;
        font-size: 1rem;
        color: #374151;
        margin-bottom: 15px;
    }

    .info-text i {
        font-size: 1.4rem;
        color: #2563eb;
    }

    .contact-divider {
        margin: 25px 0;
        height: 1px;
        background: #e5e7eb;
    }

    .social-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #f3f4f6;
        color: #1f2937;
        margin-right: 15px;
        font-size: 1.3rem;
        transition: 0.3s ease;
    }

    .social-link:hover {
        background: #2563eb;
        color: #ffffff;
        transform: translateY(-3px);
    }

    /* ===========================
       CONTACT FORM RIGHT
    =========================== */
    .form-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 25px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 0.95rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 13px 15px;
        border-radius: 10px;
        border: 1px solid #d1d5db;
        background-color: #f9fafb;
        font-size: 0.95rem;
        transition: all 0.3s;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        border-color: #2563eb;
        background-color: #fff;
        box-shadow: 0 0 6px rgba(37, 99, 235, 0.25);
        outline: none;
    }

    .form-group textarea {
        min-height: 140px;
        resize: vertical;
    }

    .contact-btn {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        color: #fff;
        padding: 14px 25px;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
        width: 100%;
    }

    .contact-btn:hover {
        background: linear-gradient(135deg, #1e40af, #2563eb);
        box-shadow: 0 8px 18px rgba(37, 99, 235, 0.4);
        transform: translateY(-2px);
    }

    /* ===========================
       RESPONSIVE
    =========================== */
    @media (max-width: 992px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<section class="contact-section">
    <!-- Hero Section -->
    <div class="contact-hero">
        <h1 class="contact-title">Contact Us</h1>
        <p class="contact-subtitle">
            Kami siap membantu Anda — hubungi kami untuk pertanyaan, dukungan, atau kerja sama.
            Tim kami akan merespons secepat mungkin.
        </p>
    </div>

    <!-- Grid Section -->
    <div class="contact-grid">
        <!-- Kolom Kiri -->
        <div class="contact-info">
            <h2 class="info-title">Informasi Kontak</h2>
            <p class="info-text"><i class="bi bi-geo-alt-fill"></i> Jl. Merdeka No. 45, Cirebon, Indonesia</p>
            <p class="info-text"><i class="bi bi-telephone-fill"></i> +62 812 3456 7890</p>
            <p class="info-text"><i class="bi bi-envelope-fill"></i> support@ticket-adt.com</p>
            <div class="contact-divider"></div>
            <div class="contact-social">
                <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                <a href="#" class="social-link"><i class="bi bi-twitter-x"></i></a>
                <a href="#" class="social-link"><i class="bi bi-linkedin"></i></a>
            </div>
        </div>

        <!-- Kolom Kanan -->
        <div class="contact-form">
            <h2 class="form-title">Kirim Pesan</h2>
            <form action="#" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" placeholder="Masukkan nama Anda" required>
                </div>
                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email aktif" required>
                </div>
                <div class="form-group">
                    <label for="subject">Subjek</label>
                    <input type="text" id="subject" name="subject" placeholder="Masukkan subjek pesan" required>
                </div>
                <div class="form-group">
                    <label for="message">Pesan Anda</label>
                    <textarea id="message" name="message" placeholder="Tulis pesan Anda di sini..." required></textarea>
                </div>
                <button type="submit" class="contact-btn">Kirim Pesan</button>
            </form>
        </div>
    </div>
</section>
@endsection
