<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\staffAirportController;
use App\Http\Controllers\staffAirlineController;
use App\Http\Controllers\staffAirplaneController;
use App\Http\Controllers\staffFlightController;
use App\Http\Controllers\staffTransactionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\BookingController; 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HrdController;
use App\Http\Controllers\AdminCmsController;
use App\Http\Controllers\StaffPromoController;
use App\Http\Controllers\ManagerController; // <--- Panggil Manager Controller di atas


// ==========================================
// 1. FRONTEND (Halaman Bebas Akses Tanpa Login)
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// Cek Pesanan & Promo (Bisa diakses tanpa login agar user bisa lihat promo)
Route::get('/cek-pesanan', [BookingController::class, 'checkOrder'])->name('booking.check');
Route::post('/cek-pesanan', [BookingController::class, 'findOrder'])->name('booking.find');
Route::post('/check-promo', [BookingController::class, 'checkPromo'])->name('promo.check');


// ==========================================
// 2. AUTHENTICATION (Login & Register)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'registerSubmit'])->name('register.submit');
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'loginSubmit'])->name('login.submit');
});


// ==========================================
// 3. AREA USER (WAJIB LOGIN) 🛡️
// ==========================================
Route::middleware('auth')->group(function () {
    
    // Fitur Pencarian & Booking
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');

    // Callback & Invoice
    Route::get('/payment/success-callback/{code}', [BookingController::class, 'paymentSuccess'])->name('payment.success_callback');
    Route::get('/booking/success/{code}', [BookingController::class, 'success'])->name('booking.success');

    // Fitur Pilih Kursi & Cetak Tiket
    Route::get('/booking/seats/{code}', [BookingController::class, 'showSeatMap'])->name('booking.seats');
    Route::post('/booking/seats/{code}', [BookingController::class, 'saveSeats'])->name('booking.seats.save');
    Route::get('/booking/ticket/{code}', [BookingController::class, 'downloadTicket'])->name('booking.ticket');

    // Fitur History User
    Route::get('/booking/history', [BookingController::class, 'history'])->name('booking.history');
    Route::post('/booking/cancel/{code}', [BookingController::class, 'cancelOrder'])->name('booking.cancel');
    
    // Rute logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


// ==========================================
// 4. RUANG STAFF (Operasional Bandara & Promo)
// ==========================================
Route::prefix('staff')
    ->name('staff.')
    ->middleware(['auth', 'role:staff']) 
    ->group(function () {
    
    Route::get('/dashboard', function () {
        return view('staff.dashboard'); 
    })->name('dashboard');

    Route::resource('airports', staffAirportController::class);
    Route::resource('airlines', staffAirlineController::class);
    Route::resource('airplanes', staffAirplaneController::class);
    Route::resource('flights', staffFlightController::class);
    
    Route::get('transactions', [staffTransactionController::class, 'index'])->name('transactions.index');
    Route::put('transactions/{id}/status', [staffTransactionController::class, 'updateStatus'])->name('transactions.updateStatus');

    // Promo Routes
    Route::get('promos', [StaffPromoController::class, 'index'])->name('promos.index');
    Route::get('promos/create', [StaffPromoController::class, 'create'])->name('promos.create');
    Route::post('promos/store', [StaffPromoController::class, 'store'])->name('promos.store');
    Route::delete('promos/{id}', [StaffPromoController::class, 'destroy'])->name('promos.destroy');
});


// ==========================================
// 5. RUANG HRD (Manajemen Jabatan Akun)
// ==========================================
Route::middleware(['auth', 'role:hrd'])->prefix('hrd')->name('hrd.')->group(function () {
    Route::get('/dashboard', [HrdController::class, 'dashboard'])->name('dashboard');
    Route::post('/user/update-role/{id}', [HrdController::class, 'updateRole'])->name('updateRole');
});

// ==========================================
// 6. RUANG MANAGER (Keuangan & Approval Refund)
// ==========================================
Route::middleware(['auth', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('dashboard');
    Route::post('/cancel/approve/{id}', [ManagerController::class, 'approveCancel'])->name('approve'); 
});


// ==========================================
// 7. RUANG ADMIN (CMS Landing Page)
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminCmsController::class, 'index'])->name('dashboard');
    Route::post('/cms/update', [AdminCmsController::class, 'update'])->name('cms.update');
});