<div class="mb-3 border-bottom pb-3">
    <h6 class="mb-1 text-muted">Informasi Pembeli & Pengiriman</h6>
    <p class="mb-0 fs-5 fw-bold text-dark">{{ $pesanan->user->name }}</p>
    <p class="mb-0 text-muted small"><i class="bi bi-telephone"></i> {{ $pesanan->user->no_telp }}</p>
    <p class="mb-0 text-muted small"><i class="bi bi-geo-alt"></i> {{ $pesanan->user->alamat }}</p>
</div>

<div class="mb-3 border-bottom pb-3">
    <h6 class="mb-2 text-muted">Daftar Produk Dipesan</h6>
    <ul class="list-group list-group-flush">
        @foreach($pesanan->detailPesanans as $detail)
        <li class="list-group-item d-flex align-items-center gap-3 px-0 py-2">
            @if(str_starts_with($detail->produk->gambar, 'http'))
                <img src="{{ $detail->produk->gambar }}" width="50" height="50" style="object-fit:cover; border-radius:8px;" alt="...">
            @else
                <img src="{{ asset('storage/'.$detail->produk->gambar) }}" width="50" height="50" style="object-fit:cover; border-radius:8px;" alt="...">
            @endif
            <div class="flex-grow-1">
                <h6 class="mb-0" style="font-size: 0.9rem;">{{ $detail->produk->nama_produk }}</h6>
                <small class="text-muted">{{ $detail->jumlah }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</small>
            </div>
            <span class="fw-bold small text-dark">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
        </li>
        @endforeach
    </ul>
</div>

<div class="d-flex justify-content-between align-items-center mt-3">
    <h5 class="mb-0">Total Pendapatan</h5>
    <h5 class="mb-0 fw-bold text-success">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</h5>
</div>