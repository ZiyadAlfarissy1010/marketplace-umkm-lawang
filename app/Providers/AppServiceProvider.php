<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Toko;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. View Composer untuk Notifikasi Penjual
        View::composer('penjual.*', function ($view) {
            if (Auth::check() && Auth::user()->role == 'penjual') {
                $toko = Auth::user()->toko;
                $newOrdersCount = 0;
                
                if ($toko) {
                    // Hitung pesanan yang butuh perhatian penjual (checkout, pending_payment, dibayar)
                    $newOrdersCount = Pesanan::whereHas('detailPesanans.produk', function($query) use ($toko) {
                        $query->where('toko_id', $toko->id);
                    })->whereIn('status', ['checkout', 'pending_payment', 'dibayar'])->count();
                }
                
                $view->with('newOrdersCount', $newOrdersCount);
            }
        });

        // 2. View Composer untuk Notifikasi Admin (Verifikasi Toko)
        View::composer('admin.*', function ($view) {
            if (Auth::check() && Auth::user()->role == 'admin') {
                // Hitung semua toko yang berstatus 'pending'
                $pendingTokoCount = Toko::where('status_verifikasi', 'pending')->count();
                $view->with('pendingTokoCount', $pendingTokoCount);
            }
        });

        // 3. View Composer untuk Badge Keranjang Pembeli
        View::composer(['public.beranda', 'public.katalog', 'public.profil_toko', 'pembeli.keranjang', 'pembeli.dashboard'], function ($view) {
            if (Auth::check() && Auth::user()->role == 'pembeli') {
                $pesanan = Pesanan::where('user_id', Auth::id())->where('status', 'keranjang')->first();
                $cartCount = $pesanan ? DetailPesanan::where('pesanan_id', $pesanan->id)->sum('jumlah') : 0;
                $view->with('cartCount', $cartCount);
            } else {
                $view->with('cartCount', 0);
            }
        });
    }
}