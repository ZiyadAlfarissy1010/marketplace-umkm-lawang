<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Katalog Produk - UMKM Lawang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root { --primary-dark: #1B4332; --primary-mid: #2D6A4F; --bg-light: #E0FBFC; --text-dark: #1B2A2E; --white: #FFFFFF; }
        
        html { overflow-y: scroll !important; scroll-behavior: smooth; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: var(--primary-dark); border-radius: 10px; }
        ::selection { background-color: var(--primary-dark); color: var(--white); }

        body { 
            background-color: var(--bg-light); 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            color: var(--text-dark); 
            min-height: 100vh; 
            display: flex; 
            flex-direction: column; 
            animation: fadeInBody 0.4s ease-in-out; 
        }
        @keyframes fadeInBody { from { opacity: 0; } to { opacity: 1; } }
        .btn:active { transform: translateY(2px); box-shadow: none !important; }
        
        .content-wrapper { flex: 1 0 auto; }
        .navbar-custom { background-color: var(--primary-dark); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .navbar-custom .nav-link, .navbar-custom .navbar-brand { color: var(--white) !important; position: relative; transition: 0.3s; }
        .navbar-custom .nav-link:hover { color: var(--bg-light) !important; }
        .navbar-custom .nav-link.active-nav { color: var(--white) !important; font-weight: bold; }
        .navbar-custom .nav-link.active-nav::after { content: ''; position: absolute; bottom: -2px; left: 50%; transform: translateX(-50%); width: 60%; height: 3px; background-color: var(--white); border-radius: 5px; }
        
        .navbar-search input { background-color: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.3); color: var(--white); border-radius: 20px 0 0 20px; }
        .navbar-search input::placeholder { color: rgba(255, 255, 255, 0.7); }
        .navbar-search input:focus { background-color: var(--white); color: var(--text-dark); box-shadow: none; border-color: var(--white); }
        .navbar-search button { border-radius: 0 20px 20px 0; background-color: #2D6A4F; color: var(--white); border: 1px solid #2D6A4F; font-weight: bold; }
        
        .btn-primary-custom { background-color: var(--primary-dark); border: none; color: var(--white); transition: all 0.3s ease; }
        .btn-primary-custom:hover { background-color: var(--primary-mid); color: var(--white); }
        .btn-primary-custom i { transition: transform 0.3s ease; }
        .btn-primary-custom:hover i { transform: translateX(5px); }

        .product-card { background-color: var(--white); border: 1px solid #e9ecef; border-radius: 12px; transition: transform 0.2s ease, box-shadow 0.2s ease; overflow: hidden; position: relative; }
        .product-card::before { content: 'UMKM Lokal'; position: absolute; top: 10px; left: 10px; background-color: rgba(27, 67, 50, 0.9); color: var(--white); font-size: 0.7rem; font-weight: 600; padding: 4px 10px; border-radius: 20px; z-index: 2; backdrop-filter: blur(4px); }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(27, 67, 50, 0.1); border-color: var(--primary-mid); }
        .product-card img { width: 100%; aspect-ratio: 4/3; object-fit: cover; background-color: #f8f9fa; transition: transform 0.3s ease; }
        .product-card:hover img { transform: scale(1.05); }
        .card-title { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; min-height: 2.5rem; }
        .price-tag { color: var(--primary-dark); font-weight: bold; font-size: 1.1rem; background-color: rgba(27, 67, 50, 0.05); padding: 5px 10px; border-radius: 8px; display: inline-block; }
        
        .footer-custom { background-color: var(--primary-dark); color: var(--white); padding: 2.5rem 0; margin-top: 6rem; flex-shrink: 0; border-top: 4px solid var(--primary-mid); }
        
        .filter-card { background-color: var(--white); border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(27, 67, 50, 0.05); position: sticky; top: 90px; padding: 20px; }
        .filter-card h5 { font-size: 1.1rem; }
        
        .filter-search { position: relative; }
        .filter-search input { border-radius: 10px; padding-left: 38px; background-color: #f8f9fa; border: 1px solid #e9ecef; }
        .filter-search input:focus { border-color: var(--primary-mid); box-shadow: 0 0 0 0.2rem rgba(45, 106, 79, 0.15); background-color: var(--white); }
        .filter-search i { position: absolute; top: 50%; left: 12px; transform: translateY(-50%); color: #6c757d; }

        .list-group-filter { max-height: 400px; overflow-y: auto; padding-right: 5px; }
        .list-group-filter .list-group-item { border: none; border-radius: 8px !important; margin-bottom: 5px; transition: 0.3s; display: flex; align-items: center; justify-content: space-between; padding: 10px 15px; font-weight: 500; color: var(--text-dark); }
        .list-group-filter .list-group-item:hover { background-color: var(--bg-light); color: var(--primary-dark); transform: translateX(5px); }
        .list-group-filter .list-group-item.active { background-color: var(--primary-dark); color: var(--white); box-shadow: 0 4px 10px rgba(27, 67, 50, 0.2); }
        
        .toast-container { position: fixed; bottom: 20px; left: 20px; z-index: 1050; }
        .custom-toast { background-color: var(--white); color: var(--text-dark); border-left: 5px solid var(--primary-dark); border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        
        /* Style Badge Keranjang & Notif Pesanan */
        .cart-badge { position: absolute; top: -5px; right: -5px; background-color: #FF6B6B; color: white; border-radius: 50%; font-size: 10px; padding: 2px 6px; font-weight: bold; display: none; animation: pulse-badge 2s infinite; }
        @keyframes pulse-badge {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 107, 107, 0.7); }
            70% { transform: scale(1.1); box-shadow: 0 0 0 6px rgba(255, 107, 107, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 107, 107, 0); }
        }
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
                <form action="{{ route('katalog') }}" method="GET" class="d-flex mx-auto my-lg-0 my-2 navbar-search" style="width: 100%; max-width: 450px;">
                    <input type="text" name="search" class="form-control" placeholder="Cari produk UMKM..." value="{{ request('search') }}">
                    <button class="btn" type="submit"><i class="bi bi-search"></i></button>
                </form>
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('beranda') ? 'active-nav' : '' }}" href="/">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('katalog') ? 'active-nav' : '' }}" href="/katalog">Katalog</a></li>
                    @if(auth()->check() && auth()->user()->role == 'pembeli')
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
                                                <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('pembeli.wishlist') ? 'active-nav' : '' }}" href="/pembeli/wishlist">
                                <i class="bi bi-heart-fill fs-5"></i>
                            </a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="nav-link p-0" href="/profile/edit" title="Edit Profil">
                                <img src="{{ auth()->user()->foto ? asset('storage/'.auth()->user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=1B4332&color=fff' }}" class="rounded-circle" width="35" height="35" style="object-fit: cover; border: 2px solid #FFFFFF;" alt="Foto Profil">
                            </a>
                        </li>
                    @endif
                    @if(!auth()->check())
                        <li class="nav-item"><a href="/login" class="btn btn-outline-light rounded-pill px-4">Login</a></li>
                        <li class="nav-item"><a href="/register" class="btn btn-light text-success rounded-pill px-4 fw-bold">Daftar</a></li>
                    @else
                        <li class="nav-item"><form action="/logout" method="POST">@csrf<button class="btn btn-outline-light rounded-pill px-3">Logout</button></form></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="content-wrapper">
        <div class="container mt-4">
            <div class="row">
                <!-- Sidebar Filter -->
                <div class="col-lg-3 mb-4">
                    <div class="filter-card">
                         <div class="filter-search mb-3">
                            <i class="bi bi-search"></i>
                            <input type="text" id="searchKategori" class="form-control form-control-sm" placeholder="Cari kategori..." onkeyup="filterKategori()">
                        </div>
                        <ul class="list-group list-group-filter" id="listKategori">
                            <a href="{{ route('katalog') }}" class="list-group-item {{ !request('kategori') ? 'active' : '' }}">Semua Kategori</a>
                            @foreach($kategoris as $kategori)
                                <a href="{{ route('katalog', ['kategori' => $kategori->id]) }}" class="list-group-item {{ request('kategori') == $kategori->id ? 'active' : '' }}">
                                    {{ $kategori->nama_kategori }}
                                </a>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Grid Produk -->
                <div class="col-lg-9">
                    <h2 class="fw-bold mb-4">Daftar Produk</h2>
                    @if($produks->count() > 0)
                    <div class="row g-4">
                        @foreach($produks as $produk)
                            <div class="col-6 col-md-4 col-sm-6">
                                                                    @php
                                        $isWishlisted = App\Models\Wishlist::where('user_id', auth()->id())->where('produk_id', $produk->id)->exists();
                                    @endphp
                                    <div class="card product-card h-100">
                                    @if(str_starts_with($produk->gambar, 'http'))
                                        <img src="{{ $produk->gambar }}" class="card-img-top" alt="{{ $produk->nama_produk }}">
                                    @else
                                        <img src="{{ asset('storage/'.$produk->gambar) }}" class="card-img-top" alt="{{ $produk->nama_produk }}">
                                    @endif
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-title fw-semibold">{{ $produk->nama_produk }}</h6>
                                        <p class="text-muted small mb-1"><i class="bi bi-shop"></i> {{ $produk->toko->nama_toko }}</p>
                                        <p class="small mb-1">
                                            @if($produk->stok > 0)
                                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Stok: {{ $produk->stok }}</span>
                                            @else
                                                <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Stok Habis</span>
                                            @endif
                                        </p>
                                                                                {{-- TAMPILKAN RATING ULASAN --}}
                                        @php
                                            $avgRating = round($produk->reviews->avg('rating'), 1);
                                            $totalReviews = $produk->reviews->count();
                                        @endphp
                                        <div class="mb-1" style="color: #FFA500; font-size: 0.8rem;">
                                            @if($totalReviews > 0)
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= round($avgRating))
                                                        <i class="bi bi-star-fill"></i>
                                                    @else
                                                        <i class="bi bi-star"></i>
                                                    @endif
                                                @endfor
                                                <span class="text-muted ms-1">({{ $totalReviews }} ulasan)</span>
                                            @else
                                                <i class="bi bi-star"></i> <span class="text-muted">Belum ada ulasan</span>
                                            @endif
                                        </div>
                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div class="price-tag">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                                                @if(auth()->check() && auth()->user()->role == 'pembeli')
                                                    <button type="button" class="btn btn-sm p-0 border-0 bg-transparent" onclick="toggleWishlist(this, {{ $produk->id }})">
                                                        @if($isWishlisted)
                                                            <i class="bi bi-heart-fill fs-4 text-danger"></i>
                                                        @else
                                                            <i class="bi bi-heart fs-4 text-muted"></i>
                                                        @endif
                                                    </button>
                                                @endif
                                            </div>
                                            @if(auth()->check() && auth()->user()->role == 'pembeli')
                                                @if($produk->stok > 0)
                                                    <div class="d-flex gap-2 mb-2">
                                                        <button type="button" class="btn btn-sm btn-primary-custom w-100 rounded-pill" onclick="addToCart({{ $produk->id }})"><i class="bi bi-cart-plus"></i></button>
                                                        <form action="/pembeli/beli-sekarang/{{ $produk->id }}" method="POST" class="w-100">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success w-100 rounded-pill fw-bold">Beli</button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-secondary w-100 rounded-pill mb-2" disabled>Stok Habis</button>
                                                @endif
                                            @endif
                                            <a href="/toko/{{ $produk->toko_id }}" class="btn btn-sm btn-outline-secondary w-100 rounded-pill">Lihat Toko</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- TOMBOL PAGINATION --}}
                    <div class="d-flex justify-content-center mt-5">
                        {{ $produks->links('pagination::bootstrap-5') }}
                    </div>

                    @else
                    <div class="text-center py-5 bg-white rounded-3 shadow-sm">
                        <i class="bi bi-search fs-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">Produk Tidak Ditemukan</h4>
                        <p class="text-muted">Coba kata kunci lain atau pilih kategori berbeda.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <footer class="footer-custom text-center">
        <div class="container">
            <h5><i class="bi bi-shop"></i> UMKM Lawang</h5>
            <p class="mb-0 text-white-50">Platform digital untuk memperluas jangkauan pasar UMKM Nagari Lawang.</p>
            <small class="text-white-50">&copy; 2026 Marketplace UMKM Lawang.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
                function toggleWishlist(btn, produkId) {
            fetch(`/pembeli/wishlist/toggle/${produkId}`, {
                method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    let icon = btn.querySelector('i');
                    if(data.status === 'added') {
                        icon.classList.remove('bi-heart', 'text-muted');
                        icon.classList.add('bi-heart-fill', 'text-danger');
                    } else {
                        icon.classList.remove('bi-heart-fill', 'text-danger');
                        icon.classList.add('bi-heart', 'text-muted');
                    }
                }
            });
        }
        function addToCart(produkId) {
            fetch(`/pembeli/keranjang/tambah/${produkId}`, {
                method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                let toast = document.getElementById('cartToast');
                let toastMessage = document.getElementById('toastMessage');
                if(data.success) {
                    let cartCount = document.getElementById('cartCount');
                    cartCount.innerText = data.total_item; cartCount.style.display = 'inline';
                    toast.style.borderLeftColor = '#1B4332';
                    toastMessage.innerHTML = `<i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Berhasil!</strong> ${data.message}`;
                } else {
                    toast.style.borderLeftColor = '#dc3545';
                    toastMessage.innerHTML = `<i class="bi bi-x-circle-fill text-danger me-2"></i> <strong>Gagal!</strong> ${data.message}`;
                }
                new bootstrap.Toast(toast).show();
            })
            .catch(error => console.error('Error:', error));
        }

        function filterKategori() {
            let input = document.getElementById('searchKategori');
            let filter = input.value.toLowerCase();
            let ul = document.getElementById('listKategori');
            let items = ul.getElementsByTagName('a');
            for (let i = 0; i < items.length; i++) {
                let txtValue = items[i].textContent || items[i].innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    items[i].style.display = "";
                } else {
                    items[i].style.display = "none";
                }
            }
        }
    </script>
</body>
</html>