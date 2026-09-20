<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $toko->nama_toko }} - UMKM Lawang</title>
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
        .navbar-search input { background-color: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.3); color: var(--white); border-radius: 20px 0 0 20px; }
        .navbar-search input::placeholder { color: rgba(255, 255, 255, 0.7); }
        .navbar-search input:focus { background-color: var(--white); color: var(--text-dark); box-shadow: none; border-color: var(--white); }
        .navbar-search button { border-radius: 0 20px 20px 0; background-color: var(--white); color: var(--primary-dark); border: 1px solid var(--white); }
        .btn-primary-custom { background-color: var(--primary-dark); border: none; color: var(--white); transition: background-color 0.3s ease; }
        .btn-primary-custom:hover { background-color: var(--primary-mid); color: var(--white); }
        .store-header { background-color: var(--white); border-radius: 20px; box-shadow: 0 4px 15px rgba(27, 67, 50, 0.05); padding: 2rem; margin-bottom: 2rem; }
        .store-logo { width: 120px; height: 120px; border-radius: 50%; overflow: hidden; border: 4px solid var(--primary-dark); display: flex; align-items: center; justify-content: center; background-color: var(--bg-light); flex-shrink: 0; }
        .store-logo img { width: 100%; height: 100%; object-fit: cover; }
        .store-badge { background-color: var(--bg-light); color: var(--primary-dark); font-weight: 600; padding: 5px 15px; border-radius: 20px; font-size: 0.85rem; }
        .product-card { background-color: var(--white); border: 1px solid #e9ecef; border-radius: 12px; transition: transform 0.2s ease, box-shadow 0.2s ease; overflow: hidden; position: relative; }
        .product-card::before { content: 'UMKM Lokal'; position: absolute; top: 10px; left: 10px; background-color: rgba(27, 67, 50, 0.9); color: var(--white); font-size: 0.7rem; font-weight: 600; padding: 4px 10px; border-radius: 20px; z-index: 2; backdrop-filter: blur(4px); }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(27, 67, 50, 0.1); border-color: var(--primary-mid); }
        .product-card img { width: 100%; aspect-ratio: 4/3; object-fit: cover; background-color: #f8f9fa; transition: transform 0.3s ease; }
        .product-card:hover img { transform: scale(1.05); }
        .price-tag { color: var(--primary-dark); font-weight: bold; font-size: 1.1rem; background-color: rgba(27, 67, 50, 0.05); padding: 5px 10px; border-radius: 8px; display: inline-block; }
        .footer-custom { background-color: var(--primary-dark); color: var(--white); padding: 2.5rem 0; margin-top: 4rem; flex-shrink: 0; border-top: 4px solid var(--primary-mid); }
        .toast-container { position: fixed; bottom: 20px; left: 20px; z-index: 1050; }
        .custom-toast { background-color: var(--white); color: var(--text-dark); border-left: 5px solid var(--primary-dark); border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        
        /* Style Badge Keranjang */
        .cart-badge { position: absolute; top: -5px; right: -5px; background-color: #FF6B6B; color: white; border-radius: 50%; font-size: 10px; padding: 2px 6px; font-weight: bold; display: none; animation: pulse-badge 2s infinite; }

        body { animation: fadeInBody 0.4s ease-in-out; }
        @keyframes fadeInBody { from { opacity: 0; } to { opacity: 1; } }
        @keyframes pulse-badge {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 107, 107, 0.7); }
            70% { transform: scale(1.1); box-shadow: 0 0 0 6px rgba(255, 107, 107, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 107, 107, 0); }
        }
        
        /* Style Ikon Sosmed */
        .socmed-icon { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: var(--white); transition: 0.3s; }
        .socmed-instagram { background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); }
        .socmed-tiktok { background-color: #000000; }
        .socmed-facebook { background-color: #1877F2; }
        .socmed-icon:hover { transform: translateY(-3px); color: var(--white); opacity: 0.9; }
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
                    <input type="text" name="search" class="form-control" placeholder="Cari produk UMKM...">
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
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('pembeli.dashboard') ? 'active-nav' : '' }}" href="/pembeli/dashboard">Pesanan</a></li>
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
            
            <!-- Header Profil Toko (Dengan Logo & Sosmed) -->
            <div class="store-header d-flex flex-column flex-md-row align-items-center gap-4">
                <div class="store-logo">
                    @if($toko->logo)
                        <img src="{{ asset('storage/'.$toko->logo) }}" alt="Logo {{ $toko->nama_toko }}">
                    @else
                        <i class="bi bi-shop-window fs-1" style="color: var(--primary-dark);"></i>
                    @endif
                </div>
                <div class="text-center text-md-start flex-grow-1">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 mb-2">
                        <h2 class="fw-bold mb-0">{{ $toko->nama_toko }}</h2>
                        @if($toko->status_verifikasi == 'disetujui') <span class="store-badge"><i class="bi bi-patch-check-fill"></i> Terverifikasi</span> @endif
                    </div>
                    <p class="text-muted mb-2"><i class="bi bi-geo-alt-fill"></i> {{ $toko->alamat }}</p>
                    <p class="mb-3">{{ $toko->deskripsi }}</p>
                    
                    <!-- IKON SOSIAL MEDIA -->
                    <div class="d-flex gap-2 justify-content-center justify-content-md-start">
                        @if($toko->instagram)
                            <a href="{{ $toko->instagram }}" target="_blank" class="socmed-icon socmed-instagram" title="Instagram"><i class="bi bi-instagram"></i></a>
                        @endif
                        @if($toko->tiktok)
                            <a href="{{ $toko->tiktok }}" target="_blank" class="socmed-icon socmed-tiktok" title="TikTok"><i class="bi bi-tiktok"></i></a>
                        @endif
                        @if($toko->facebook)
                            <a href="{{ $toko->facebook }}" target="_blank" class="socmed-icon socmed-facebook" title="Facebook"><i class="bi bi-facebook"></i></a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Daftar Produk Toko -->
            <h4 class="fw-bold mb-4">Produk dari Toko Ini</h4>

            @if($toko->produks->count() > 0)
            <div class="row g-4">
                @foreach($toko->produks as $produk)
                    <div class="col-6 col-md-3 col-sm-6">
                        <div class="card product-card h-100">
                            @if(str_starts_with($produk->gambar, 'http'))
                                <img src="{{ $produk->gambar }}" class="card-img-top" alt="{{ $produk->nama_produk }}">
                            @else
                                <img src="{{ asset('storage/'.$produk->gambar) }}" class="card-img-top" alt="{{ $produk->nama_produk }}">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title fw-semibold text-truncate">{{ $produk->nama_produk }}</h6>
                                <p class="small mb-1">
                                    @if($produk->stok > 0)
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Stok: {{ $produk->stok }}</span>
                                    @else
                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Stok Habis</span>
                                    @endif
                                </p>
                                <div class="mt-auto">
                                    <div class="price-tag mb-2">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                                    @if(auth()->check() && auth()->user()->role == 'pembeli')
                                        @if($produk->stok > 0)
                                            <div class="d-flex gap-2 mb-2">
                                                <button type="button" class="btn btn-sm btn-primary-custom w-100 rounded-pill" onclick="addToCart({{ $produk->id }})">
                                                    <i class="bi bi-cart-plus"></i>
                                                </button>
                                                <form action="/pembeli/beli-sekarang/{{ $produk->id }}" method="POST" class="w-100">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success w-100 rounded-pill fw-bold">
                                                        Beli
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <button type="button" class="btn btn-sm btn-secondary w-100 rounded-pill mb-2" disabled>Stok Habis</button>
                                        @endif
                                    @endif
                                    <a href="/katalog" class="btn btn-sm btn-outline-secondary w-100 rounded-pill">Lihat di Katalog</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @else
                <div class="text-center py-5 bg-white rounded-3 shadow-sm">
                    <i class="bi bi-box-seam fs-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">Belum Ada Produk</h4>
                    <p class="text-muted">Toko ini belum mengunggah produknya.</p>
                </div>
            @endif
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
    </script>
</body>
</html>