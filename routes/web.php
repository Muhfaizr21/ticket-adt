<?php

use Illuminate\Support\Facades\Route;

// =========================
// 🧍 Auth & User Controllers
// =========================
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PurchaseController; // ✅ DITAMBAHKAN (hilang di versi kamu)


// =========================
// 🧑‍💻 Admin Controllers
// =========================
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\Tickets\TicketTypeController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\VenueController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\AdminNewsController;
use App\Http\Controllers\Admin\AdminNotificationController;

// =========================
// 🌟 Public Routes
// =========================
Route::get('/welcome', fn() => view('welcome'));

// =========================
// 🔐 Authentication Routes
// =========================
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =========================
// 👤 USER ROUTES (AUTH REQUIRED)
// =========================
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('pengguna.dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Halaman Informasi
    Route::get('/help', [HelpController::class, 'index'])->name('help');
    Route::get('/news', [NewsController::class, 'index'])->name('news');
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');

    // =========================
    // 🛍️ Shop Routes
    // =========================
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/shop/{id}', [ShopController::class, 'show'])->name('shop.show');
    Route::post('/shop/purchase', [ShopController::class, 'purchase'])->name('shop.purchase');

    // =========================
    // 🎟️ Ticket & Purchase
    // =========================
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/purchase/{id}', [TicketController::class, 'purchase'])->name('tickets.purchase');

    //news
    Route::middleware(['auth'])->group(function () {
    Route::get('/news', [NewsController::class, 'index'])->name('news');
    Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');
});
    // Halaman pembelian tiket
    //Route::get('/purchase/{id}', [PurchaseController::class, 'show'])->name('purchase.show');
}); // <-- Tutup middleware user

// =========================
// 🧑‍💼 ADMIN ROUTES
// =========================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin'])->group(function () {

    // Dashboard
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Profile Admin
    Route::get('profile', [AdminProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::put('profile/avatar', [AdminProfileController::class, 'updateAvatar'])->name('profile.avatar.update');

    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('settings/edit', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::put('settings/theme', [SettingsController::class, 'updateTheme'])->name('settings.theme.update');

    // Support / Help
    Route::get('support', [SupportController::class, 'index'])->name('support.index');

    // Events
    Route::resource('events', AdminEventController::class);

    // 🎟️ Ticket Types (Per Event)
    Route::prefix('ticket-types')->name('ticket-types.')->group(function () {
        // Daftar semua tipe tiket
        Route::get('/', [TicketTypeController::class, 'index'])->name('index');

        // Form tambah tipe tiket
        Route::get('/{event}/create', [TicketTypeController::class, 'create'])->name('create');

        // Simpan tipe tiket baru (berdasarkan event)
        Route::post('/{event}/store', [TicketTypeController::class, 'store'])->name('store');

        // Form edit tipe tiket tertentu
        Route::get('/{event}/{ticket}/edit', [TicketTypeController::class, 'edit'])->name('edit');

        // Update tipe tiket
        Route::put('/{event}/{ticket}/update', [TicketTypeController::class, 'update'])->name('update');

        // Hapus tipe tiket
        Route::delete('/{event}/{ticket}/delete', [TicketTypeController::class, 'destroy'])->name('destroy');
    });

    // Orders
    Route::resource('orders', OrderController::class);

    // Customers
    Route::resource('customers', CustomerController::class)->only(['index', 'show']);

    // Promotions
    Route::resource('promotions', PromotionController::class);
    Route::put('/admin/promotions/{id}', [PromotionController::class, 'update'])->name('admin.promotions.update');

    // Venues
    Route::resource('venues', VenueController::class);

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');

    // Notifications
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');

    // News Management
    Route::resource('news', AdminNewsController::class);
});
