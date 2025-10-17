<?php

use Illuminate\Support\Facades\Route;

// =========================
// 🧍 USER CONTROLLERS
// =========================
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RefundController;

// =========================
// 🧑‍💼 ADMIN CONTROLLERS
// =========================
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
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
use App\Http\Controllers\Admin\TicketCheckInController;
use App\Http\Controllers\Admin\PaymentMethodController;

// =========================
// 🌟 PUBLIC ROUTES
// =========================
Route::get('/welcome', fn() => view('welcome'));

// =========================
// 🔐 AUTHENTICATION ROUTES
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

    // 🏠 Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('pengguna.dashboard');
    Route::get('/events/search', [DashboardController::class, 'search'])->name('events.search');
    Route::get('/events/{id}', [DashboardController::class, 'show'])->name('events.show');

    // 🎫 Beli tiket
    Route::get('/tickets/buy/{event_id}/{ticket_type_id}', [DashboardController::class, 'buyTicket'])->name('tickets.buy');

    // 👤 Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // 📢 Info & Bantuan
    Route::get('/help', [HelpController::class, 'index'])->name('help');
    Route::get('/news', [NewsController::class, 'index'])->name('news');
    Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

    // Contact
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

    // 🛍 Shop
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/shop/{id}', [ShopController::class, 'show'])->name('shop.show');

    // 🧾 Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/create/{event}', [OrderController::class, 'create'])->name('create');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::delete('/{order}', [OrderController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/upload', [OrderController::class, 'uploadForm'])->name('upload');
        Route::post('/{id}/upload', [OrderController::class, 'uploadPayment'])->name('upload.store');
        Route::get('/{id}/barcode/download', [OrderController::class, 'downloadBarcode'])->name('downloadBarcode');
    });

    // 💸 Refunds (User)
    Route::prefix('refunds')->name('refunds.')->group(function () {
        Route::get('/create/{order}', [RefundController::class, 'create'])->name('create');
        Route::post('/store', [RefundController::class, 'store'])->name('store');
    });

    // 🎟 Ticket Verification
    Route::post('/tickets/verify', [OrderController::class, 'verifyTicket'])->name('tickets.verify');

    // 🎫 Tickets
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/purchase/{id}', [TicketController::class, 'purchase'])->name('tickets.purchase');
});

// =========================
// 🧑‍💼 ADMIN ROUTES
// =========================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin'])->group(function () {

    // 🏠 Dashboard
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // 👤 Profile Admin
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [AdminProfileController::class, 'index'])->name('index');
        Route::put('/', [AdminProfileController::class, 'update'])->name('update');
        Route::put('/password', [AdminProfileController::class, 'updatePassword'])->name('password.update');
        Route::put('/avatar', [AdminProfileController::class, 'updateAvatar'])->name('avatar.update');
        Route::delete('/avatar', [AdminProfileController::class, 'destroyAvatar'])->name('avatar.destroy');
    });

    // ⚙️ Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::get('/edit', [SettingsController::class, 'edit'])->name('edit');
        Route::put('/', [SettingsController::class, 'update'])->name('update');
        Route::put('/theme', [SettingsController::class, 'updateTheme'])->name('theme.update');
    });

    // 💬 Support
    Route::get('support', [SupportController::class, 'index'])->name('support.index');

    // 🎫 Check-in tiket realtime
    Route::get('tickets/check-in', [TicketCheckInController::class, 'index'])->name('tickets.check-in');
    Route::post('tickets/check-in', [TicketCheckInController::class, 'verify'])->name('tickets.verify');

    // 🎉 Events
    Route::resource('events', AdminEventController::class);

    // 💸 Ticket Types
    Route::prefix('ticket-types')->name('ticket-types.')->group(function () {
        Route::get('/', [TicketTypeController::class, 'index'])->name('index');
        Route::get('/{event}/create', [TicketTypeController::class, 'create'])->name('create');
        Route::post('/{event}/store', [TicketTypeController::class, 'store'])->name('store');
        Route::get('/{event}/{ticket}/edit', [TicketTypeController::class, 'edit'])->name('edit');
        Route::put('/{event}/{ticket}/update', [TicketTypeController::class, 'update'])->name('update');
        Route::delete('/{event}/{ticket}/delete', [TicketTypeController::class, 'destroy'])->name('destroy');
    });

    // 🧾 Orders Admin
    Route::resource('orders', AdminOrderController::class);
    Route::post('orders/{id}/verify-payment', [AdminOrderController::class, 'verifyPayment'])->name('orders.verify-payment');

    // 👥 Customers / Refunds Admin
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
        Route::get('refunds', [CustomerController::class, 'index'])->name('refunds.index');
        Route::put('refunds/{order}/{status}', [CustomerController::class, 'updateRefundStatus'])->name('refunds.update');
    });

    // 🎁 Promotions
    Route::resource('promotions', PromotionController::class);

    // 🏟 Venues
    Route::resource('venues', VenueController::class);

    // 📊 Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');

    // 🏦 Payment Methods
    Route::prefix('payment-methods')->name('payment_methods.')->group(function () {
        Route::get('/', [PaymentMethodController::class, 'index'])->name('index');
        Route::get('/create', [PaymentMethodController::class, 'create'])->name('create');
        Route::post('/', [PaymentMethodController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PaymentMethodController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PaymentMethodController::class, 'update'])->name('update');
        Route::delete('/{id}', [PaymentMethodController::class, 'destroy'])->name('destroy');
    });

    // 🔔 Notifications (Admin)
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [AdminNotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all', [AdminNotificationController::class, 'markAllAsRead'])->name('markAll');
    });

    // 📰 News
    Route::resource('news', AdminNewsController::class);
});
