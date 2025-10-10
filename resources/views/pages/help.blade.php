@extends('layouts.app')

@section('title', 'Help Center')
@section('page-title', 'Help Center')

@section('content')
<style>
/* ========== STYLE HELP CENTER ========== */

/* Container utama */
.help-container {
    max-width: 1100px;
    margin: 0 auto;
    padding: 40px 20px;
    font-family: 'Poppins', sans-serif;
}

/* Header */
.help-header {
    text-align: center;
    margin-bottom: 40px;
}
.help-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2C3E50;
}
.help-subtitle {
    font-size: 1.1rem;
    color: #7f8c8d;
    margin-top: 10px;
}

/* Search bar */
.help-search {
    display: flex;
    justify-content: center;
    margin-bottom: 50px;
    gap: 10px;
    flex-wrap: wrap;
}
.help-search-input {
    padding: 12px 20px;
    width: 350px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
}
.help-search-input:focus {
    border-color: #2980b9;
    outline: none;
    box-shadow: 0 0 6px rgba(41, 128, 185, 0.3);
}
.help-search-btn {
    padding: 12px 20px;
    background-color: #2980b9;
    color: white;
    font-size: 1rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease;
}
.help-search-btn:hover {
    background-color: #1f6390;
}

/* Categories */
.help-categories {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin-bottom: 60px;
}
.help-card {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
}
.help-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}
.help-icon {
    font-size: 2.2rem;
    color: #2980b9;
    margin-bottom: 15px;
}
.help-card h3 {
    font-size: 1.3rem;
    margin-bottom: 10px;
    color: #2c3e50;
}
.help-card p {
    font-size: 0.95rem;
    color: #7f8c8d;
    margin-bottom: 15px;
}
.help-link {
    color: #2980b9;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s ease;
}
.help-link:hover {
    color: #1f6390;
    text-decoration: underline;
}

/* Contact section */
.help-contact {
    text-align: center;
    padding: 40px;
    background: linear-gradient(135deg, #2980b9, #3498db);
    border-radius: 16px;
    color: white;
}
.help-contact h2 {
    font-size: 1.8rem;
    margin-bottom: 10px;
}
.help-contact p {
    font-size: 1rem;
    margin-bottom: 20px;
}
.help-contact-btn {
    padding: 12px 30px;
    background: white;
    color: #2980b9;
    font-size: 1rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: background 0.3s ease, color 0.3s ease;
}
.help-contact-btn:hover {
    background: #1f6390;
    color: white;
}
</style>

<div class="help-container">
    {{-- Header --}}
    <div class="help-header">
        <h1 class="help-title">Help Center</h1>
        <p class="help-subtitle">Temukan jawaban atas pertanyaan Anda atau hubungi tim support kami.</p>
    </div>

    {{-- Categories --}}
    <div class="help-categories">
        <div class="help-card">
            <i class="bi bi-person-circle help-icon"></i>
            <h3>Akun & Profil</h3>
            <p>Panduan mengelola akun, mengganti password, dan keamanan profil.</p>
            <a href="#" class="help-link">Pelajari lebih lanjut →</a>
        </div>
        <div class="help-card">
            <i class="bi bi-ticket-detailed help-icon"></i>
            <h3>Tiket & Layanan</h3>
            <p>Cara membuat, memantau, dan menyelesaikan tiket layanan.</p>
            <a href="#" class="help-link">Pelajari lebih lanjut →</a>
        </div>
        <div class="help-card">
            <i class="bi bi-gear help-icon"></i>
            <h3>Pengaturan Sistem</h3>
            <p>Konfigurasi aplikasi, notifikasi, dan pengaturan umum lainnya.</p>
            <a href="#" class="help-link">Pelajari lebih lanjut →</a>
        </div>
    </div>

    {{-- Contact --}}
    <div class="help-contact">
        <h2>Masih butuh bantuan?</h2>
        <p>Hubungi tim support kami untuk mendapatkan solusi lebih lanjut.</p>
        <a href="{{ route('contact') }}" class="help-contact-btn">Hubungi Kami</a>
    </div>
</div>
@endsection
