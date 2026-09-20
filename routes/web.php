<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PenjualController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routing Halaman Publik (Bisa diakses tanpa login)
Route::get('/', [PublicController::class, 'beranda'])->name('beranda');
Route::get('/katalog', [PublicController::class, 'katalog'])->name('katalog');
Route::get('/toko/{id}', [PublicController::class, 'profilToko'])->name('profil.toko');

// Routing Autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Routing Lupa Password
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Routing Verifikasi OTP (Diletakkan di luar middleware auth)
Route::get('/verify-otp', [AuthController::class, 'showOtp'])->name('otp.verify');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.check');

// Routing Profile (Bisa diakses semua role yang sudah login)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

// Routing Admin (Diproteksi Middleware Role Admin)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Kelola Kategori
    Route::get('/admin/kategori', [AdminController::class, 'kategoriIndex'])->name('admin.kategori');
    Route::post('/admin/kategori', [AdminController::class, 'kategoriStore'])->name('admin.kategori.store');
    Route::delete('/admin/kategori/{id}', [AdminController::class, 'kategoriDestroy'])->name('admin.kategori.destroy');
    
    // Verifikasi Toko
    Route::get('/admin/verifikasi', [AdminController::class, 'verifikasiIndex'])->name('admin.verifikasi');
    Route::post('/admin/verifikasi/{id}', [AdminController::class, 'verifikasiUpdate'])->name('admin.verifikasi.update');

    // Kelola Pengguna
    Route::get('/admin/pengguna', [AdminController::class, 'penggunaIndex'])->name('admin.pengguna');
    Route::delete('/admin/pengguna/{id}', [AdminController::class, 'penggunaDestroy'])->name('admin.pengguna.destroy');
    
    // Lihat Produk Toko (AJAX)
    Route::get('/admin/toko/{id}/produk', [AdminController::class, 'lihatProdukToko'])->name('admin.toko.produk');
});

// Routing Penjual (UMKM)
Route::middleware(['auth', 'role:penjual'])->group(function () {
    Route::get('/penjual/dashboard', [PenjualController::class, 'dashboard'])->name('penjual.dashboard');
    
    // Laporan & Profil Toko
    Route::get('/penjual/laporan', [PenjualController::class, 'laporan'])->name('penjual.laporan');
    Route::get('/penjual/profil', [PenjualController::class, 'editProfil'])->name('penjual.profil');
    Route::post('/penjual/profil', [PenjualController::class, 'updateProfil'])->name('penjual.profil.update');
    
    // Pesanan
    Route::get('/penjual/pesanan', [PenjualController::class, 'pesananMasuk'])->name('penjual.pesanan');
    Route::post('/penjual/pesanan/{id}/update', [PenjualController::class, 'updateStatusPesanan'])->name('penjual.pesanan.update');
    
    // CRUD Produk
    Route::get('/penjual/produk/create', [PenjualController::class, 'createProduk'])->name('penjual.produk.create');
    Route::post('/penjual/produk', [PenjualController::class, 'storeProduk'])->name('penjual.produk.store');
    Route::get('/penjual/produk/{id}/edit', [PenjualController::class, 'editProduk'])->name('penjual.produk.edit');
    Route::put('/penjual/produk/{id}', [PenjualController::class, 'updateProduk'])->name('penjual.produk.update');
    Route::delete('/penjual/produk/{id}', [PenjualController::class, 'destroyProduk'])->name('penjual.produk.destroy');
    // Route Detail Pesanan Penjual (AJAX)
    Route::get('/penjual/pesanan/{id}/detail', [PenjualController::class, 'pesananDetail'])->name('penjual.pesanan.detail');
    });

// Routing Pembeli
Route::middleware(['auth', 'role:pembeli'])->group(function () {
    Route::get('/pembeli/dashboard', [PembeliController::class, 'dashboard'])->name('pembeli.dashboard');
    Route::get('/pembeli/keranjang', [PembeliController::class, 'keranjang'])->name('pembeli.keranjang');
    
    // Tambah dan Update Keranjang
    Route::post('/pembeli/keranjang/tambah/{produk_id}', [PembeliController::class, 'addToCart'])->name('pembeli.add_cart');
    Route::post('/pembeli/beli-sekarang/{produk_id}', [PembeliController::class, 'beliSekarang'])->name('pembeli.beli_sekarang');
    Route::post('/pembeli/keranjang/update/{id}', [PembeliController::class, 'updateJumlah'])->name('pembeli.update_cart');
    
    // Cek Stok Sebelum Checkout (AJAX)
    Route::post('/pembeli/checkout/cek-stok', [PembeliController::class, 'cekStokCheckout'])->name('pembeli.cek_stok');
    
    Route::delete('/pembeli/keranjang/hapus/{id}', [PembeliController::class, 'destroyCart'])->name('pembeli.cart_destroy');
    Route::post('/pembeli/checkout', [PembeliController::class, 'checkout'])->name('pembeli.checkout');
    
    // Detail & Batalkan Pesanan
    Route::get('/pembeli/pesanan/{id}/detail', [PembeliController::class, 'pesananDetail'])->name('pembeli.detail');
    Route::post('/pembeli/pesanan/{id}/batalkan', [PembeliController::class, 'batalkanPesanan'])->name('pembeli.batalkan');
    Route::post('/pembeli/pesanan/{id}/terima', [PembeliController::class, 'terimaBarang'])->name('pembeli.terima');
        // Route Upload Bukti Bayar (BARU)
    Route::post('/pembeli/pesanan/{id}/upload-bukti', [PembeliController::class, 'uploadBuktiBayar'])->name('pembeli.upload_bukti');
});