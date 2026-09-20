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

    // Halaman Katalog (Dengan Pencarian & Filter)
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

        $produks = $query->get();
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