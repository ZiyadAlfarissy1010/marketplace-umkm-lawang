<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Toko;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PenjualController extends Controller
{
    public function dashboard()
    {
        $toko = Auth::user()->toko;
        if (!$toko) {
            return '<h1>Anda belum memiliki toko. Hubungi Admin.</h1>';
        }

        if ($toko->status_verifikasi != 'disetujui') {
            return view('penjual.dashboard', ['toko' => $toko, 'produks' => []])
                   ->with('error', 'Toko Anda sedang menunggu verifikasi Admin. Anda belum bisa menambahkan produk.');
        }

        $produks = Produk::where('toko_id', $toko->id)->latest()->get();
        return view('penjual.dashboard', compact('produks', 'toko'));
    }

    public function laporan()
    {
        $toko = Auth::user()->toko;
        $produks = Produk::where('toko_id', $toko->id)->get();
        
        $laporan = [];
        $totalKeuntunganKeseluruhan = 0;

        foreach ($produks as $produk) {
            $terjual = DetailPesanan::where('produk_id', $produk->id)
                        ->whereHas('pesanan', function($query) {
                            $query->where('status', '!=', 'keranjang');
                        })->sum('jumlah');
            
            $pendapatan = $terjual * $produk->harga;

            $laporan[] = [
                'nama' => $produk->nama_produk,
                'stok' => $produk->stok,
                'terjual' => $terjual,
                'pendapatan' => $pendapatan
            ];

            $totalKeuntunganKeseluruhan += $pendapatan;
        }

        return view('penjual.laporan', compact('toko', 'laporan', 'totalKeuntunganKeseluruhan'));
    }

    public function pesananMasuk()
    {
        $toko = Auth::user()->toko;
        $pesanans = Pesanan::whereHas('detailPesanans.produk', function($query) use ($toko) {
            $query->where('toko_id', $toko->id);
        })->where('status', '!=', 'keranjang')->latest()->get();

        return view('penjual.pesanan', compact('pesanans', 'toko'));
    }

    // FUNGSI BARU: AMBIL DETAIL PESANAN UNTUK MODAL
    public function pesananDetail($id)
    {
        $toko = Auth::user()->toko;
        
        // Pastikan pesanan ini benar-benar milik toko si penjual (keamanan)
        $pesanan = Pesanan::whereHas('detailPesanans.produk', function($query) use ($toko) {
            $query->where('toko_id', $toko->id);
        })->with('user', 'detailPesanans.produk')->findOrFail($id);

        $html = view('penjual.detail_modal', compact('pesanan'))->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    // UPDATE: Logika konfirmasi pembayaran transfer
    public function updateStatusPesanan(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        
        // Logika khusus konfirmasi pembayaran
        if ($request->has('konfirmasi_bayar')) {
            if ($pesanan->status == 'dibayar') {
                $pesanan->status = 'diproses';
                $pesanan->save();
                return back()->with('success', 'Pembayaran dikonfirmasi! Silakan proses pesanan ini.');
            }
        }

        // Logika ubah status biasa
        $pesanan->status = $request->status;
        $pesanan->save();
        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    public function editProfil()
    {
        $toko = Auth::user()->toko;
        return view('penjual.profil_toko', compact('toko'));
    }

    // UPDATE: Tambah validasi dan simpan rekening bank
    public function updateProfil(Request $request)
    {
        $toko = Auth::user()->toko;
        
        $request->validate([
            'nama_toko' => 'required',
            'alamat' => 'required',
            'deskripsi' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'instagram' => 'nullable|url',
            'tiktok' => 'nullable|url',
            'facebook' => 'nullable|url',
            'nama_bank' => 'nullable|string',
            'no_rekening' => 'nullable|string',
            'atas_nama' => 'nullable|string',
            'qris_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $toko->nama_toko = $request->nama_toko;
        $toko->alamat = $request->alamat;
        $toko->deskripsi = $request->deskripsi;
        
        $toko->instagram = $request->instagram;
        $toko->tiktok = $request->tiktok;
        $toko->facebook = $request->facebook;

        $toko->nama_bank = $request->nama_bank;
        $toko->no_rekening = $request->no_rekening;
        $toko->atas_nama = $request->atas_nama;

        // UPLOAD QRIS
        if ($request->hasFile('qris_image')) {
            if ($toko->qris_image) {
                Storage::disk('public')->delete($toko->qris_image);
            }
            $toko->qris_image = $request->file('qris_image')->store('qris_images', 'public');
        }

        if ($request->hasFile('logo')) {
            if ($toko->logo) { Storage::disk('public')->delete($toko->logo); }
            $toko->logo = $request->file('logo')->store('toko_logos', 'public');
        }

        $toko->save();
        return back()->with('success', 'Profil toko berhasil diperbarui!');
    }

    public function createProduk()
    {
        $toko = Auth::user()->toko;
        if ($toko->status_verifikasi != 'disetujui') {
            return redirect()->route('penjual.dashboard')->with('error', 'Toko Anda belum diverifikasi!');
        }

        $kategoris = Kategori::all();
        return view('penjual.produk_create', compact('kategoris'));
    }

    public function storeProduk(Request $request)
    {
        $toko = Auth::user()->toko;
        if ($toko->status_verifikasi != 'disetujui') {
            return redirect()->route('penjual.dashboard')->with('error', 'Toko Anda belum diverifikasi!');
        }

        $request->validate([
            'nama_produk' => 'required', 'harga' => 'required|numeric', 'stok' => 'required|numeric',
            'kategori_id' => 'required', 'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $gambarPath = $request->file('gambar')->store('produk_images', 'public');
        Produk::create([
            'toko_id' => $toko->id, 'kategori_id' => $request->kategori_id, 'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi, 'harga' => $request->harga, 'stok' => $request->stok, 'gambar' => $gambarPath,
        ]);
        return redirect()->route('penjual.dashboard')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function editProduk($id)
    {
        $produk = Produk::findOrFail($id);
        $kategoris = Kategori::all();
        if ($produk->toko_id != Auth::user()->toko->id) { abort(403); }
        return view('penjual.produk_edit', compact('produk', 'kategoris'));
    }

    public function updateProduk(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        if ($produk->toko_id != Auth::user()->toko->id) { abort(403); }
        $request->validate([
            'nama_produk' => 'required', 'harga' => 'required|numeric', 'stok' => 'required|numeric',
            'kategori_id' => 'required', 'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $data = $request->except('gambar');
        if ($request->hasFile('gambar')) {
            if ($produk->gambar) { Storage::disk('public')->delete($produk->gambar); }
            $data['gambar'] = $request->file('gambar')->store('produk_images', 'public');
        }
        $produk->update($data);
        return redirect()->route('penjual.dashboard')->with('success', 'Produk berhasil diupdate!');
    }

    public function destroyProduk($id)
    {
        $produk = Produk::findOrFail($id);
        if ($produk->toko_id != Auth::user()->toko->id) { abort(403); }
        if ($produk->gambar) { Storage::disk('public')->delete($produk->gambar); }
        $produk->delete();
        return back()->with('success', 'Produk berhasil dihapus!');
    }
}