<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist Saya - UMKM Lawang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root { --primary-dark: #1B4332; --primary-mid: #2D6A4F; --bg-light: #E0FBFC; --text-dark: #1B2A2E; --white: #FFFFFF; }
        body { background-color: var(--bg-light); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: var(--text-dark); min-height: 100vh; display: flex; flex-direction: column; }
        .content-wrapper { flex: 1 0 auto; }
        .navbar-custom { background-color: var(--primary-dark); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .navbar-custom .nav-link, .navbar-custom .navbar-brand { color: var(--white) !important; position: relative; transition: 0.3s; }
        .navbar-custom .nav-link:hover { color: var(--bg-light) !important; }
        .navbar-custom .nav-link.active-nav { color: var(--white) !important; font-weight: bold; }
        .navbar-custom .nav-link.active-nav::after { content: ''; position: absolute; bottom: -2px; left: 50%; transform: translateX(-50%); width: 60%; height: 3px; background-color: var(--white); border-radius: 5px; }
        .btn-primary-custom { background-color: var(--primary-dark); border: none; color: var(--white); }
        .btn-primary-custom:hover { background-color: var(--primary-mid); color: var(--white); }
        .product-card { background-color: var(--white); border: 1px solid #e9ecef; border-radius: 12px; transition: transform 0.2s ease, box-shadow 0.2s ease; overflow: hidden; position: relative; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(27, 67, 50, 0.1); border-color: var(--primary-mid); }
        .product-card img { width: 100%; aspect-ratio: 4/3; object-fit: cover; background-color: #f8f9fa; transition: transform 0.3s ease; }
        .product-card:hover img { transform: scale(1.05); }
        .price-tag { color: var(--primary-dark); font-weight: bold; font-size: 1.1rem; background-color: rgba(27, 67, 50, 0.05); padding: 5px 10px; border-radius: 8px; display: inline-block; }
        .footer-custom { background-color: var(--primary-dark); color: var(--white); padding: 2.5rem 0; margin-top: 4rem; flex-shrink: 0; }
        .toast-container { position: fixed; bottom: 20px; left: 20px; z-index: 1050; }
        .custom-toast { background-color: var(--white); color: var(--text-dark); border-left: 5px solid var(--primary-dark); border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .cart-badge { position: absolute; top: -5px; right: -5px; background-color: #FF6B6B; color: white; border-radius: 50%; font-size: 10px; padding: 2px 6px; font-weight: bold; display: none; }
    </style>
</head>
<body>

    <div class="toast-container">
        <div id="cartToast" class="toast custom-toast" role="alert" data-bs-delay="1500">
            <div class="toast-body" id="toastMessage"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Berhasil!</strong></div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/"><i class="bi bi-shop"></i> UMKM Lawang</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('beranda') ? 'active-nav' : '' }}" href="/">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('katalog') ? 'active-nav' : '' }}" href="/katalog">Katalog</a></li>
                    <li class="nav-item position-relative">
                        <a class="nav-link {{ request()->routeIs('pembeli.keranjang') ? 'active-nav' : '' }}" href="/pembeli/keranjang">
                            <i class="bi bi-cart3 fs-5"></i>
                            <span class="cart-badge" id="cartCount" style="@if($cartCount > 0) display: inline; @else display: none; @endif">{{ $cartCount }}</span>
                        </a>
                    </li>
                    <li class="nav-item position-relative">
                        <a class="nav-link {{ request()->routeIs('pembeli.dashboard') ? 'active-nav' : '' }}" href="/pembeli/dashboard">
                            Pesanan
                            @if($newOrdersCount > 0)
                                <span class="cart-badge" style="top: 0; right: -10px; display: inline;">{{ $newOrdersCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item position-relative">
                        <a class="nav-link active" href="/pembeli/wishlist">
                            <i class="bi bi-heart-fill fs-5"></i>
                        </a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="nav-link p-0" href="/profile/edit" title="Edit Profil">
                            <img src="{{ auth()->user()->foto ? asset('storage/'.auth()->user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=1B4332&color=fff' }}" class="rounded-circle" width="35" height="35" style="object-fit: cover; border: 2px solid #FFFFFF;" alt="Foto Profil">
                        </a>
                    </li>
                    <li class="nav-item"><form action="/logout" method="POST">@csrf<button class="btn btn-outline-light rounded-pill px-3">Logout</button></form></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="content-wrapper">
        <div class="container mt-4">
            <h2 class="fw-bold mb-4"><i class="bi bi-heart-fill text-danger"></i> Wishlist Favorit Saya</h2>

            @if($wishlists->count() > 0)
                <div class="row g-4">
                    @foreach($wishlists as $wishlist)
                        <div class="col-6 col-md-3">
                            <div class="card product-card h-100">
                                @if(str_starts_with($wishlist->produk->gambar, 'http'))
                                    <img src="{{ $wishlist->produk->gambar }}" class="card-img-top" alt="{{ $wishlist->produk->nama_produk }}">
                                @else
                                    <img src="{{ asset('storage/'.$wishlist->produk->gambar) }}" class="card-img-top" alt="{{ $wishlist->produk->nama_produk }}">
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title fw-semibold text-truncate">{{ $wishlist->produk->nama_produk }}</h6>
                                    <p class="text-muted small mb-1"><i class="bi bi-shop"></i> {{ $wishlist->produk->toko->nama_toko }}</p>
                                    <div class="mt-auto">
                                        <div class="price-tag mb-2">Rp {{ number_format($wishlist->produk->harga, 0, ',', '.') }}</div>
                                        
                                        <div class="d-flex gap-2 mb-2">
                                            @if($wishlist->produk->stok > 0)
                                                <button type="button" class="btn btn-sm btn-primary-custom w-100 rounded-pill" onclick="addToCart({{ $wishlist->produk->id }})"><i class="bi bi-cart-plus"></i></button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-secondary w-100 rounded-pill mb-2" disabled>Habis</button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" onclick="toggleWishlist({{ $wishlist->produk->id }})" title="Hapus dari Wishlist">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                        <a href="/toko/{{ $wishlist->produk->toko_id }}" class="btn btn-sm btn-outline-secondary w-100 rounded-pill">Lihat Toko</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card text-center py-5 shadow-sm border-0">
                    <i class="bi bi-bag-x fs-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">Wishlist Anda masih kosong</h4>
                    <p class="text-muted">Yuk simpan produk favoritmu dengan menekan ikon hati di katalog!</p>
                    <a href="/katalog" class="btn btn-primary-custom mt-3 rounded-pill px-4">Mulai Belanja</a>
                </div>
            @endif
        </div>
    </div>

    <footer class="footer-custom text-center">
        <div class="container">
            <h5><i class="bi bi-shop"></i> UMKM Lawang</h5>
            <p class="mb-0 text-white-50">Platform digital untuk memperluas jangkauan pasar UMKM Nagari Lawang.</p>
            <small class="text-white-50">&copy; 2026 Marketplace UMKM Lawang. Dibuat untuk Skripsi.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function addToCart(produkId) {
            fetch(`/pembeli/keranjang/tambah/${produkId}`, {
                method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}', 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                let toast = document.getElementById('cartToast');
                let toastMessage = document.getElementById('toastMessage');
                if(data.success) {
                    toastMessage.innerHTML = `<i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Berhasil!</strong> ${data.message}`;
                } else {
                    toastMessage.innerHTML = `<i class="bi bi-x-circle-fill text-danger me-2"></i> <strong>Gagal!</strong> ${data.message}`;
                }
                new bootstrap.Toast(toast).show();
            });
        }

        function toggleWishlist(produkId) {
            fetch(`/pembeli/wishlist/toggle/${produkId}`, {
                method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload(); // Refresh halaman untuk update tampilan wishlist
                }
            });
        }
    </script>
</body>
</html>