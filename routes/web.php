<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboard;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboard;
use App\Http\Controllers\Tenant\InvoiceController as TenantInvoiceController;
use App\Http\Controllers\Tenant\ComplaintController as TenantComplaintController;
use App\Http\Controllers\Owner\RoomController;
use App\Http\Controllers\Owner\TenantController;
use App\Http\Controllers\Owner\LeaseController;
use App\Http\Controllers\Owner\InvoiceController;
use App\Http\Controllers\Owner\ComplaintController;
use App\Http\Controllers\Owner\PaymentController;
use App\Http\Controllers\Auth\TenantRegisterController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// ==========================================
// ROUTE AUTENTIKASI (LOGIN & LOGOUT)
// ==========================================

// Hanya bisa diakses oleh pengunjung yang BELUM login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.process');
});

// Hanya bisa diakses oleh pengguna yang SUDAH login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


// ==========================================
// ROUTE KHUSUS PEMILIK (OWNER)
// ==========================================
Route::middleware(['auth', 'owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboard::class, 'index'])->name('dashboard');
    // Nanti rute CRUD Kamar, Penghuni, dll ditambahkan di sini

    Route::resource('rooms', RoomController::class);

    Route::resource('tenants', TenantController::class);

    // Route CRUD untuk Manajemen Penyewaan (Lease)
    Route::resource('leases', LeaseController::class);
    // Route CRUD untuk Manajemen Tagihan (Invoices)
    Route::resource('invoices', InvoiceController::class);

    // Route Verifikasi Pembayaran oleh Owner
    Route::patch('/payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
    Route::patch('/leases/{lease}/approve', [LeaseController::class, 'approve'])->name('leases.approve');

    // Route Manajemen Keluhan (Complaints)
    Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');
    Route::patch('/complaints/{complaint}/status', [ComplaintController::class, 'updateStatus'])->name('complaints.updateStatus');


});


Route::middleware('guest')->group(function () {
    Route::get('/register', [TenantRegisterController::class, 'create'])->name('register');
    Route::post('/register', [TenantRegisterController::class, 'store'])->name('register.process');
});


Route::post('/login', [TenantRegisterController::class, 'login'])->name('login.process');
Route::post('/logout', [TenantRegisterController::class, 'logout'])->name('logout');

// ==========================================
// ROUTE KHUSUS PENGHUNI (TENANT)
// ==========================================
Route::middleware(['auth', 'tenant'])->prefix('tenant')->name('tenant.')->group(function () {
    Route::get('/dashboard', [TenantDashboard::class, 'index'])->name('dashboard');
    // Nanti rute Tagihan, Pembayaran, Keluhan ditambahkan di sini

    Route::get('/invoices', [TenantInvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [TenantInvoiceController::class, 'show'])->name('invoices.show');
    Route::post('/invoices/{invoice}/pay', [TenantInvoiceController::class, 'pay'])->name('invoices.pay');

    // Keluhan Tenant
    Route::resource('complaints', TenantComplaintController::class)->except(['edit', 'update', 'destroy']);
});