<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ContactController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\Tickets\TicketTypeController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\VenueController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\AdminNotificationController;

// ======================
// 🏠 Public / Auth Routes
// ======================
Route::get('/welcome', fn() => view('welcome'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ======================
// 👤 Protected Routes (User)
// ======================
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('pengguna.dashboard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/shop', [ShopController::class, 'index'])->name('shop');
    Route::get('/help', [HelpController::class, 'index'])->name('help');
    Route::get('/news', [NewsController::class, 'index'])->name('news');
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
});
// ======================
// 🧭 Protected Routes (Admin)
// ======================
Route::middleware(['auth', 'isAdmin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Events
        Route::resource('events', AdminEventController::class);

        // Ticket Types (VIP & Reguler per event)
        Route::resource('ticket-types', TicketTypeController::class)
            ->except(['show', 'create', 'store', 'destroy']);
        Route::post('ticket-types/update-all', [TicketTypeController::class, 'updateAll'])
            ->name('ticket-types.update-all');

        // Orders
        Route::resource('orders', OrderController::class);

        // Customers
        Route::resource('customers', CustomerController::class)->only(['index', 'show']);

        // Promotions
        Route::resource('promotions', PromotionController::class);

        // Venues
        Route::resource('venues', VenueController::class);

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');

        // Help & Support
        Route::get('/support', [SupportController::class, 'index'])->name('support.index');

        // 🔔 Notifications
        Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [AdminNotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [AdminNotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    });
