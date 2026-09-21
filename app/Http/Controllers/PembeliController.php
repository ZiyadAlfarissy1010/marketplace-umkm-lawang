<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Review;
use App\Models\Setting; // Tambahkan ini untuk ongkir
use App\Models\Wishlist;
class PembeliController extends Controller
{
        // FUNGSI BARU: WISHLIST
    public function wishlist()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())->with('produk.toko')->latest()->get();
        return view('pembeli.wishlist', compact('wishlists'));
    }

    public function toggleWishlist($produk_id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->where('produk_id', $produk_id)->first();

        if ($wishlist) {
            // Jika sudah ada, hapus dari wishlist
            $wishlist->delete();
            return response()->json(['success' => true, 'status' => 'removed', 'message' => 'Produk dihapus dari wishlist.']);
        } else {
            // Jika belum ada, tambahkan ke wishlist
            Wishlist::create([
                'user_id' => Auth::id(),
                'produk_id' => $produk_id
            ]);
            return response()->json(['success' => true, 'status' => 'added', 'message' => 'Produk ditambahkan ke wishlist!']);
        }
    }
    public function dashboard()
    {
        $pesanans = Pesanan::with('detailPesanans.produk.toko')->where('user_id', Auth::id())->where('status', '!=', 'keranjang')->latest()->get();
        return view('pembeli.dashboard', compact('pesanans'));
    }

    public function keranjang()
    {
        $pesanan = Pesanan::where('user_id', Auth::id())->where('status', 'keranjang')->first();
        
        if (!$pesanan) {
            $detailPesanans = []; 
        } else {
            $detailPesanans = DetailPesanan::where('pesanan_id', $pesanan->id)->with('produk')->get();
        }

        $ongkir = Setting::getOngkir(); // Ambil ongkir dari setting admin

        return view('pembeli.keranjang', compact('detailPesanans', 'ongkir'));
    }

    public function addToCart($produk_id)
    {
        $produk = Produk::findOrFail($produk_id);
        $user_id = Auth::id();

        if ($produk->stok < 1) {
            return response()->json(['success' => false, 'message' => 'Stok produk habis!']);
        }

        $pesanan = Pesanan::firstOrCreate(
            ['user_id' => $user_id, 'status' => 'keranjang'],
            ['total_harga' => 0]
        );

        $detail = DetailPesanan::where('pesanan_id', $pesanan->id)->where('produk_id', $produk_id)->first();
        
        $jumlahSaatIni = $detail ? $detail->jumlah : 0;
        if ($jumlahSaatIni + 1 > $produk->stok) {
            return response()->json(['success' => false, 'message' => 'Jumlah melebihi stok yang tersedia!']);
        }

        if ($detail) {
            $detail->jumlah += 1;
            $detail->subtotal = $detail->jumlah * $produk->harga;
            $detail->save();
        } else {
            DetailPesanan::create([
                'pesanan_id' => $pesanan->id,
                'produk_id' => $produk_id,
                'jumlah' => 1,
                'harga_satuan' => $produk->harga,
                'subtotal' => $produk->harga
            ]);
        }

        $totalItem = DetailPesanan::where('pesanan_id', $pesanan->id)->sum('jumlah');

        return response()->json([
            'success' => true,
            'message' => 'Berhasil! Ada ' . $totalItem . ' item di keranjang Anda.',
            'total_item' => $totalItem
        ]);
    }

    public function beliSekarang($produk_id)
    {
        $produk = Produk::findOrFail($produk_id);
        $user_id = Auth::id();

        if ($produk->stok < 1) {
            return back()->with('error', 'Stok produk habis!');
        }

        $pesanan = Pesanan::firstOrCreate(
            ['user_id' => $user_id, 'status' => 'keranjang'],
            ['total_harga' => 0]
        );

        $detail = DetailPesanan::where('pesanan_id', $pesanan->id)->where('produk_id', $produk_id)->first();
        
        if ($detail) {
            if ($detail->jumlah + 1 > $produk->stok) {
                return back()->with('error', 'Jumlah melebihi stok yang tersedia!');
            }
            $detail->jumlah += 1;
            $detail->subtotal = $detail->jumlah * $produk->harga;
            $detail->save();
        } else {
            DetailPesanan::create([
                'pesanan_id' => $pesanan->id,
                'produk_id' => $produk_id,
                'jumlah' => 1,
                'harga_satuan' => $produk->harga,
                'subtotal' => $produk->harga
            ]);
        }

        return redirect()->route('pembeli.keranjang')->with('success', 'Produk ditambahkan! Silakan lanjutkan checkout.');
    }

    public function updateJumlah(Request $request, $id)
    {
        $detail = DetailPesanan::findOrFail($id);
        $produk = Produk::find($detail->produk_id);
        
        $jumlahBaru = $request->jumlah;

        if ($jumlahBaru > $produk->stok) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak cukup! Stok tersisa: ' . $produk->stok,
                'stok_tersedia' => $produk->stok
            ]);
        }

        $detail->jumlah = $jumlahBaru;
        $detail->subtotal = $jumlahBaru * $produk->harga;
        $detail->save();

        return response()->json(['success' => true]);
    }

    public function destroyCart($id)
    {
        $detail = DetailPesanan::findOrFail($id);
        $detail->delete();
        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function cekStokCheckout()
    {
        $pesanan = Pesanan::where('user_id', Auth::id())->where('status', 'keranjang')->first();
        if (!$pesanan) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong!']);
        }

        $details = DetailPesanan::where('pesanan_id', $pesanan->id)->get();
        foreach ($details as $detail) {
            $produk = Produk::find($detail->produk_id);
            if ($detail->jumlah > $produk->stok) {
                return response()->json(['success' => false, 'message' => 'Stok ' . $produk->nama_produk . ' tersisa ' . $produk->stok . '. Silakan kurangi jumlah pesanan.']);
            }
        }

        return response()->json(['success' => true]);
    }

    // FUNGSI UTAMA: CHECKOUT & KIRIM WA KE PENJUAL (SPLIT ORDER + ONGKIR)
    public function checkout(Request $request)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:whatsapp,transfer,qris',
            'ekspedisi' => 'required|in:JNE,J&T,SiCepat,Pos Indonesia'
        ]);

        $keranjang = Pesanan::where('user_id', Auth::id())->where('status', 'keranjang')->first();
        
        if (!$keranjang) {
            return back()->with('error', 'Keranjang Anda kosong!');
        }

        $details = DetailPesanan::with('produk.toko.user')->where('pesanan_id', $keranjang->id)->get();
        
        if ($details->isEmpty()) {
            return back()->with('error', 'Keranjang Anda kosong!');
        }

        // 1. VALIDASI STOK SEBELUM CHECKOUT
        foreach ($details as $detail) {
            $produk = $detail->produk;
            if ($detail->jumlah > $produk->stok) {
                return back()->with('error', 'Checkout DITOLAK! Jumlah pesanan ' . $produk->nama_produk . ' (' . $detail->jumlah . ') melebihi stok. Stok tersisa: ' . $produk->stok);
            }
        }

        // 2. KELOMPOKKAN PESANAN BERDASARKAN TOKO
        $groupedDetails = $details->groupBy('produk.toko_id');
        $token = env('FONNTE_TOKEN');
        $metodeBayar = $request->metode_pembayaran;
        $ekspedisi = $request->ekspedisi;
        $ongkir = Setting::getOngkir(); // Ambil ongkir

        // 3. LOOPING SETIAP TOKO DAN BUAT PESANAN BARU (SPLIT ORDER)
        foreach ($groupedDetails as $tokoId => $tokoDetails) {
            $toko = $tokoDetails->first()->produk->toko;
            $totalToko = 0;

            // Tentukan status awal pesanan berdasarkan metode bayar
            $statusAwal = ($metodeBayar == 'whatsapp') ? 'checkout' : 'pending_payment';

            // Buat record pesanan baru khusus untuk toko ini
            $pesananBaru = Pesanan::create([
                'user_id' => Auth::id(),
                'total_harga' => 0, 
                'status' => $statusAwal,
                'metode_pembayaran' => $metodeBayar,
                'ekspedisi' => $ekspedisi,
                'ongkir' => $ongkir // Simpan ongkir per pesanan toko
            ]);

            $pesanWA = "Halo *{$toko->nama_toko}*, Anda mendapatkan pesanan baru!\n\n";
            $pesanWA .= "Kode Pesanan: *#ORD-{$pesananBaru->id}*\n";
            $pesanWA .= "Pembeli: *{$keranjang->user->name}*\n";
            $pesanWA .= "Metode Bayar: *" . strtoupper($metodeBayar) . "*\n";
            $pesanWA .= "Ekspedisi: *{$ekspedisi}*\n";
            $pesanWA .= "Daftar Pesanan:\n";

            // Pindahkan detail pesanan ke pesanan baru, dan kurangi stok
            foreach ($tokoDetails as $detail) {
                $totalToko += $detail->subtotal;
                
                $detail->pesanan_id = $pesananBaru->id;
                $detail->save();

                $produk = $detail->produk;
                $produk->stok -= $detail->jumlah;
                $produk->save();

                $pesanWA .= "- {$detail->produk->nama_produk} (x{$detail->jumlah}) : Rp " . number_format($detail->subtotal, 0, ',', '.') . "\n";
            }

            // Total harga = total belanja toko + ongkir
            $pesananBaru->total_harga = $totalToko + $ongkir;
            $pesananBaru->save();

            // Jika metode bayar WhatsApp, kirim WA ke penjual
            if ($metodeBayar == 'whatsapp') {
                $pesanWA .= "\nSubtotal: Rp " . number_format($totalToko, 0, ',', '.') . "\n";
                $pesanWA .= "Ongkir: Rp " . number_format($ongkir, 0, ',', '.') . "\n";
                $pesanWA .= "Total: *Rp " . number_format($pesananBaru->total_harga, 0, ',', '.') . "*\n\n";
                $pesanWA .= "Segera periksa dashboard toko Anda untuk memproses pesanan ini. Terima kasih.";

                if ($token && $token != 'MASUKKAN_TOKEN_FONNTE_DISINI') {
                    $no_telp = $toko->user->no_telp;
                    if (substr($no_telp, 0, 1) === '0') {
                        $no_telp = '62' . substr($no_telp, 1);
                    }
                    Http::withHeaders(['Authorization' => $token])->asForm()->post('https://api.fonnte.com/send', [
                        'target' => $no_telp, 'message' => $pesanWA, 'countryCode' => '62',
                    ]);
                }
            } else {
                // Jika transfer bank / qris, kirim notifikasi WA biasa 
                $pesanWA .= "\nSubtotal: Rp " . number_format($totalToko, 0, ',', '.') . "\n";
                $pesanWA .= "Ongkir: Rp " . number_format($ongkir, 0, ',', '.') . "\n";
                $pesanWA .= "Total: Rp " . number_format($pesananBaru->total_harga, 0, ',', '.') . "\n\n";
                $pesanWA .= "Pembeli memilih metode " . strtoupper($metodeBayar) . ". Mohon pantau dashboard untuk verifikasi bukti bayar.";

                if ($token && $token != 'MASUKKAN_TOKEN_FONNTE_DISINI') {
                    $no_telp = $toko->user->no_telp;
                    if (substr($no_telp, 0, 1) === '0') {
                        $no_telp = '62' . substr($no_telp, 1);
                    }
                    Http::withHeaders(['Authorization' => $token])->asForm()->post('https://api.fonnte.com/send', [
                        'target' => $no_telp, 'message' => $pesanWA, 'countryCode' => '62',
                    ]);
                }
            }
        }

        // 4. HAPUS KERANJANG LAMA
        $keranjang->delete();

        return redirect()->route('pembeli.dashboard')->with('success', 'Checkout berhasil! Silakan lanjutkan pembayaran sesuai metode yang dipilih.');
    }

    // FUNGSI BARU: UPLOAD BUKTI BAYAR
    public function uploadBuktiBayar(Request $request, $id)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $pesanan = Pesanan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($pesanan->status == 'pending_payment') {
            if ($request->hasFile('bukti_bayar')) {
                if ($pesanan->bukti_bayar) {
                    Storage::disk('public')->delete($pesanan->bukti_bayar);
                }
                $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
                $pesanan->bukti_bayar = $path;
                $pesanan->status = 'dibayar';
                $pesanan->save();
            }
            return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu konfirmasi penjual.');
        }

        return back()->with('error', 'Pesanan tidak bisa diunggah bukti bayarnya.');
    }

    // FUNGSI BARU: SIMPAN ULASAN PRODUK
    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string'
        ]);

        $pesanan = Pesanan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($pesanan->status != 'selesai') {
            return back()->with('error', 'Hanya bisa memberi ulasan untuk pesanan yang sudah selesai.');
        }

        $existingReview = Review::where('user_id', Auth::id())
            ->where('produk_id', $request->produk_id)
            ->where('pesanan_id', $id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        Review::create([
            'user_id' => Auth::id(),
            'produk_id' => $request->produk_id,
            'pesanan_id' => $id,
            'rating' => $request->rating,
            'komentar' => $request->komentar
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim.');
    }

    public function batalkanPesanan($id)
    {
        $pesanan = Pesanan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($pesanan->status == 'checkout' || $pesanan->status == 'pending_payment') {
            $details = DetailPesanan::where('pesanan_id', $pesanan->id)->get();
            foreach ($details as $detail) {
                $produk = Produk::find($detail->produk_id);
                $produk->stok += $detail->jumlah;
                $produk->save();
            }

            $pesanan->status = 'dibatalkan';
            $pesanan->save();

            return back()->with('success', 'Pesanan berhasil dibatalkan.');
        }

        return back()->with('error', 'Pesanan tidak bisa dibatalkan karena sudah diproses oleh penjual.');
    }

    public function terimaBarang($id)
    {
        $pesanan = Pesanan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($pesanan->status == 'dikirim') {
            $pesanan->status = 'selesai';
            $pesanan->save();
            return back()->with('success', 'Pesanan dikonfirmasi telah diterima. Terima kasih!');
        }

        return back()->with('error', 'Pesanan tidak bisa dikonfirmasi.');
    }

    public function pesananDetail($id)
    {
        $pesanan = Pesanan::with('detailPesanans.produk.toko.user')->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $html = view('pembeli.detail_modal', compact('pesanan'))->render();
        return response()->json(['success' => true, 'html' => $html]);
    }
}