<ul class="list-group list-group-flush">
    @foreach($pesanan->detailPesanans as $detail)
    <li class="list-group-item d-flex align-items-center gap-3 px-0">
        @if(str_starts_with($detail->produk->gambar, 'http'))
            <img src="{{ $detail->produk->gambar }}" width="60" height="60" style="object-fit:cover; border-radius:8px;" alt="...">
        @else
            <img src="{{ asset('storage/'.$detail->produk->gambar) }}" width="60" height="60" style="object-fit:cover; border-radius:8px;" alt="...">
        @endif
        <div class="flex-grow-1">
            <h6 class="mb-0">{{ $detail->produk->nama_produk }}</h6>
            <small class="text-muted">{{ $detail->jumlah }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</small>
        </div>
        <span class="fw-bold text-success">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
    </li>

    {{-- FORM ULASAN (HANYA MUNCUL JIKA PESANAN SELESAI) --}}
    @if($pesanan->status == 'selesai')
        @php
            // Cek apakah sudah pernah ngasih ulasan
            $sudahUlas = App\Models\Review::where('user_id', $pesanan->user_id)
                            ->where('produk_id', $detail->produk_id)
                            ->where('pesanan_id', $pesanan->id)
                            ->exists();
        @endphp
        
        <li class="list-group-item px-0 mb-3 border-top-0">
            @if($sudahUlas)
                <div class="text-center text-muted small py-2">
                    <i class="bi bi-check-circle-fill text-success"></i> Anda sudah memberikan ulasan untuk produk ini.
                </div>
            @else
                <form action="/pembeli/pesanan/{{ $pesanan->id }}/review" method="POST" class="p-2 border rounded bg-light">
                    @csrf
                    <input type="hidden" name="produk_id" value="{{ $detail->produk_id }}">
                    <h6 class="mb-2">Beri Ulasan Produk:</h6>
                    <div class="mb-2">
                        <select name="rating" class="form-select form-select-sm" required>
                            <option value="">Pilih Rating Bintang</option>
                            <option value="5">★★★★★ (Sangat Puas)</option>
                            <option value="4">★★★★ (Puas)</option>
                            <option value="3">★★★ (Cukup)</option>
                            <option value="2">★★ (Kurang)</option>
                            <option value="1">★ (Kecewa)</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <textarea name="komentar" class="form-control form-control-sm" rows="2" placeholder="Tulis komentar (opsional)..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary-custom w-100 rounded-pill">Kirim Ulasan</button>
                </form>
            @endif
        </li>
    @endif
    @endforeach
</ul>

<div class="mt-3 pt-3 border-top">
    <div class="d-flex justify-content-between mb-2">
        <span class="text-muted">Subtotal Produk</span>
        <span class="fw-semibold">Rp {{ number_format($pesanan->total_harga - $pesanan->ongkir, 0, ',', '.') }}</span>
    </div>
    <div class="d-flex justify-content-between mb-2">
        <span class="text-muted">Ongkir ({{ $pesanan->ekspedisi }})</span>
        <span class="fw-semibold">Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}</span>
    </div>
    <div class="d-flex justify-content-between mt-2 pt-2 border-top">
        <h5 class="mb-0">Total Pembayaran</h5>
        <h5 class="mb-0 fw-bold text-success">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</h5>
    </div>
</div>