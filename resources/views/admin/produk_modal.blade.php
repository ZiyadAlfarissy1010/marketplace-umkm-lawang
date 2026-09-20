<h6 class="text-muted mb-3">Daftar produk yang dijual oleh: <strong>{{ $toko->nama_toko }}</strong></h6>
<ul class="list-group list-group-flush">
    @if($produks->count() > 0)
        @foreach($produks as $produk)
        <li class="list-group-item d-flex align-items-center gap-3 px-0">
            @if(str_starts_with($produk->gambar, 'http'))
                <img src="{{ $produk->gambar }}" width="60" height="60" style="object-fit:cover; border-radius:8px;" alt="...">
            @else
                <img src="{{ asset('storage/'.$produk->gambar) }}" width="60" height="60" style="object-fit:cover; border-radius:8px;" alt="...">
            @endif
            <div class="flex-grow-1">
                <h6 class="mb-0">{{ $produk->nama_produk }}</h6>
                <small class="text-muted">Kategori: {{ $produk->kategori->nama_kategori ?? '-' }}</small>
            </div>
            <div class="text-end">
                <div class="fw-bold text-success">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                @if($produk->stok > 0)
                    <small class="badge bg-light text-dark">Stok: {{ $produk->stok }}</small>
                @else
                    <small class="badge bg-danger">Habis</small>
                @endif
            </div>
        </li>
        @endforeach
    @else
        <li class="list-group-item text-center text-muted py-4">
            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
            Toko ini belum memiliki produk.
        </li>
    @endif
</ul>