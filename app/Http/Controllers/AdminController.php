<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Toko;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUser = User::count();
        $totalToko = Toko::count();
        $totalProduk = Produk::count();
        
        $totalTransaksi = Pesanan::where('status', '!=', 'keranjang')->count();
        $totalPendapatan = Pesanan::where('status', '!=', 'keranjang')->sum('total_harga');
        $tokoPending = Toko::where('status_verifikasi', 'pending')->count();

        $laporanToko = Toko::join('produks', 'tokos.id', '=', 'produks.toko_id')
            ->leftJoin('detail_pesanans', 'produks.id', '=', 'detail_pesanans.produk_id')
            ->leftJoin('pesanans', function($join) {
                $join->on('detail_pesanans.pesanan_id', '=', 'pesanans.id')
                     ->where('pesanans.status', '!=', 'keranjang');
            })
            ->select(
                'tokos.id', 'tokos.nama_toko', 'tokos.status_verifikasi',
                DB::raw('COUNT(DISTINCT produks.id) as jumlah_produk'),
                DB::raw('COALESCE(SUM(detail_pesanans.subtotal), 0) as total_penjualan')
            )
            ->groupBy('tokos.id', 'tokos.nama_toko', 'tokos.status_verifikasi')
            ->get();

        return view('admin.dashboard', compact(
            'totalUser', 'totalToko', 'totalProduk', 'totalTransaksi', 'totalPendapatan', 'tokoPending', 'laporanToko'
        ));
    }

    // FUNGSI BARU: AMBIL DAFTAR PRODUK PER TOKO (AJAX)
    public function lihatProdukToko($id)
    {
        $toko = Toko::findOrFail($id);
        $produks = Produk::where('toko_id', $id)->with('kategori')->get();
        
        $html = view('admin.produk_modal', compact('produks', 'toko'))->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    public function kategoriIndex()
    {
        $kategoris = Kategori::all();
        return view('admin.kategori', compact('kategoris'));
    }

    public function kategoriStore(Request $request)
    {
        $validator = Validator::make($request->all(), ['nama_kategori' => 'required|string|max:255']);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }
        $kategori = Kategori::create(['nama_kategori' => $request->nama_kategori]);
        return response()->json(['success' => true, 'kategori' => $kategori]);
    }

    public function kategoriDestroy($id)
    {
        Kategori::find($id)->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }

    public function verifikasiIndex()
    {
        $tokos = Toko::with('user')->latest()->get();
        return view('admin.verifikasi', compact('tokos'));
    }

    public function verifikasiUpdate(Request $request, $id)
    {
        $toko = Toko::find($id);
        $toko->status_verifikasi = $request->status;
        $toko->save();

        return response()->json([
            'success' => true,
            'status' => $toko->status_verifikasi
        ]);
    }

    public function penggunaIndex()
    {
        $users = User::latest()->get();
        return view('admin.pengguna', compact('users'));
    }

    public function penggunaDestroy($id)
    {
        if(auth()->id() == $id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Admin sendiri!');
        }

        $user = User::find($id);
        if ($user) {
            $user->delete();
            return back()->with('success', 'Data pengguna berhasil dihapus!');
        }
        return back()->with('error', 'Pengguna tidak ditemukan.');
    }
}