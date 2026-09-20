@php
    // Kelompokkan detail pesanan berdasarkan ID Toko
    $groupedDetails = $pesanan->detailPesanans->groupBy(function($item) {
        return $item->produk->toko_id;
    });
@endphp

<ul class="list-group list-group-flush">
    @foreach($groupedDetails as $tokoId => $details)
        @php
            $toko = $details->first()->produk->toko;
            
            // Format nomor WA (hilangkan angka 0 di depan, ganti dengan 62)
            $noTelp = preg_replace('/[^0-9]/', '', $toko->user->no_telp);
            if (substr($noTelp, 0, 1) === '0') {
                $noTelp = '62' . substr($noTelp, 1);
            }
            
            // Buat teks pesan WhatsApp
            $pesan = "Halo *{$toko->nama_toko}*, saya baru saja melakukan pemesanan di UMKM Lawang.\n\n";
            $pesan .= "Kode Pesanan: *#ORD-{$pesanan->id}*\n";
            $pesan .= "Daftar Pesanan:\n";
            $totalToko = 0;
            foreach ($details as $d) {
                $pesan .= "- {$d->produk->nama_produk} (x{$d->jumlah}) : Rp " . number_format($d->subtotal, 0, ',', '.') . "\n";
                $totalToko += $d->subtotal;
            }
            $pesan .= "\nTotal: Rp " . number_format($totalToko, 0, ',', '.') . "\n\n";
            $pesan .= "Mohon konfirmasi ketersediaan dan pengiriman ya. Terima kasih!";
            
            // Encode pesan agar bisa dikirim via URL
            $waLink = "https://wa.me/{$noTelp}?text=" . rawurlencode($pesan);
        @endphp
        
        <li class="list-group-item mb-3 p-3 rounded-3" style="background-color: #f8f9fa; border: 1px solid #e9ecef;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0 text-success"><i class="bi bi-shop"></i> {{ $toko->nama_toko }}</h6>
                <a href="{{ $waLink }}" target="_blank" class="btn btn-sm btn-success rounded-pill fw-bold">
                    <i class="bi bi-whatsapp"></i> Chat Penjual
                </a>
            </div>
            <ul class="list-unstyled mb-0">
                @foreach($details as $detail)
                    <li class="d-flex align-items-center gap-2 mb-2">
                        @if(str_starts_with($detail->produk->gambar, 'http'))
                            <img src="{{ $detail->produk->gambar }}" width="50" height="50" style="object-fit:cover; border-radius:8px;" alt="...">
                        @else
                            <img src="{{ asset('storage/'.$detail->produk->gambar) }}" width="50" height="50" style="object-fit:cover; border-radius:8px;" alt="...">
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-0" style="font-size: 0.9rem;">{{ $detail->produk->nama_produk }}</h6>
                            <small class="text-muted">{{ $detail->jumlah }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</small>
                        </div>
                        <span class="fw-bold text-success small">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
        </li>
    @endforeach
</ul>

<div class="d-flex justify-content-between mt-3 pt-3 border-top">
    <h5 class="mb-0">Total Pembayaran Keseluruhan</h5>
    <h5 class="mb-0 fw-bold text-success">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</h5>
</div>