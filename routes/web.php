<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\Tickets\TicketTypeController;
use App\Http\Controllers\Admin\VenueController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\AdminProfileController;

// Welcome
Route::get('/welcome', fn() => view('welcome'));

// Auth Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes - Pengguna
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

// Protected Routes - Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin'])->group(function () {

    // Dashboard
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Events
    Route::resource('events', AdminEventController::class);

    // Ticket Types
    Route::resource('ticket-types', TicketTypeController::class)
        ->except(['show', 'create', 'store', 'destroy']);
    Route::post('ticket-types/update-all', [TicketTypeController::class, 'updateAll'])->name('ticket-types.update-all');

    // Orders
    Route::resource('orders', OrderController::class);

    // Customers
    Route::resource('customers', CustomerController::class)->only(['index', 'show']);

    // Promotions
    Route::resource('promotions', PromotionController::class);

    // Venues
    Route::resource('venues', VenueController::class);

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');

    // Settings - Index (overview)
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('settings/edit', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::put('settings/theme', [SettingsController::class, 'updateTheme'])->name('settings.theme.update');

    // Profile Admin
    Route::get('profile', [AdminProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::put('profile/avatar', [AdminProfileController::class, 'updateAvatar'])->name('profile.avatar.update'); // ✅ Tambahkan ini
});
