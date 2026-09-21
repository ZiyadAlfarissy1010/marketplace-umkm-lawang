<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Toko;
use App\Models\Produk;
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
                // View Composer untuk Notifikasi Penjual (Pesanan & Stok Menipis)
        View::composer('penjual.*', function ($view) {
            if (Auth::check() && Auth::user()->role == 'penjual') {
                $toko = Auth::user()->toko;
                $newOrdersCount = 0;
                $lowStockCount = 0;
                
                if ($toko) {
                    // Hitung pesanan yang butuh perhatian penjual
                    $newOrdersCount = Pesanan::whereHas('detailPesanans.produk', function($query) use ($toko) {
                        $query->where('toko_id', $toko->id);
                    })->whereIn('status', ['checkout', 'pending_payment', 'dibayar'])->count();

                    // Hitung produk dengan stok menipis (5 atau kurang, tapi bukan 0)
                    $lowStockCount = Produk::where('toko_id', $toko->id)
                        ->where('stok', '>', 0)
                        ->where('stok', '<=', 5)
                        ->count();
                }
                
                $view->with('newOrdersCount', $newOrdersCount)->with('lowStockCount', $lowStockCount);
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

                // View Composer untuk Badge Keranjang & Notifikasi Pesanan Pembeli
        View::composer(['public.beranda', 'public.katalog', 'public.profil_toko', 'pembeli.keranjang', 'pembeli.dashboard', 'pembeli.wishlist'], function ($view) {
            if (Auth::check() && Auth::user()->role == 'pembeli') {
                $pesanan = Pesanan::where('user_id', Auth::id())->where('status', 'keranjang')->first();
                $cartCount = $pesanan ? DetailPesanan::where('pesanan_id', $pesanan->id)->sum('jumlah') : 0;
                
                // Hitung pesanan yang statusnya baru diupdate oleh penjual (buyer_seen = false)
                $newOrdersCount = Pesanan::where('user_id', Auth::id())
                    ->where('status', '!=', 'keranjang')
                    ->where('buyer_seen', false)
                    ->count();
                    
                $view->with('cartCount', $cartCount)->with('newOrdersCount', $newOrdersCount);
            } else {
                $view->with('cartCount', 0)->with('newOrdersCount', 0);
            }
        });
    }
}