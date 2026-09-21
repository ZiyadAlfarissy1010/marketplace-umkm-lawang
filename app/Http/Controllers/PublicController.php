<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Toko;
use App\Models\Kategori;

class PublicController extends Controller
{
    // Halaman Beranda
    public function beranda()
    {
        $produks = Produk::with('toko')->latest()->take(8)->get(); 
        return view('public.beranda', compact('produks'));
    }

    // Halaman Katalog (Dengan Pencarian, Filter, & Pagination)
    public function katalog(Request $request)
    {
        $query = Produk::with('toko')->latest();

        // Logika Pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        // Logika Filter Kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_id', $request->kategori);
        }

        // UBAH get() MENJADI paginate(8) -> 8 produk per halaman
        // appends(request()->query()) berfungsi agar saat pindah halaman, filter kategori/pencarian tetap tersimpan
        $produks = $query->paginate(8)->appends(request()->query());
        
        $kategoris = Kategori::all();
        
        return view('public.katalog', compact('produks', 'kategoris'));
    }

    // Halaman Profil Toko Publik
    public function profilToko($id)
    {
        $toko = Toko::with('produks')->findOrFail($id);
        return view('public.profil_toko', compact('toko'));
    }
}