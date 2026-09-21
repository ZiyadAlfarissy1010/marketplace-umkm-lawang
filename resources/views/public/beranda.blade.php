<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace UMKM Nagari Lawang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-dark: #1B4332;
            --primary-mid: #2D6A4F;
            --bg-light: #E0FBFC;
            --text-dark: #1B2A2E;
            --white: #FFFFFF;
        }
        body {
            background-color: var(--bg-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .content-wrapper { flex: 1 0 auto; }
        .navbar-custom {
            background-color: var(--primary-dark);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .navbar-custom .nav-link, .navbar-custom .navbar-brand {
            color: var(--white) !important;
            position: relative;
            transition: 0.3s;
        }
        .navbar-custom .nav-link:hover {
            color: var(--bg-light) !important;
        }
        .navbar-custom .nav-link.active-nav {
            color: var(--white) !important;
            font-weight: bold;
        }
        .navbar-custom .nav-link.active-nav::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 3px;
            background-color: var(--white);
            border-radius: 5px;
        }
        
        /* Style untuk Search Bar di Navbar */
        .navbar-search input {
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: var(--white);
            border-radius: 20px 0 0 20px;
        }
        .navbar-search input::placeholder { color: rgba(255, 255, 255, 0.7); }
        .navbar-search input:focus {
            background-color: var(--white);
            color: var(--text-dark);
            box-shadow: none;
            border-color: var(--white);
        }
        .navbar-search button {
            border-radius: 0 20px 20px 0;
            background-color: var(--white);
            color: var(--primary-dark);
            border: 1px solid var(--white);
        }

        .btn-primary-custom {
            background-color: var(--primary-dark);
            border: none;
            color: var(--white);
            transition: background-color 0.3s ease;
        }
        .btn-primary-custom:hover {
            background-color: var(--primary-mid);
            color: var(--white);
        }
        .hero-section {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-mid) 100%);
            color: var(--white);
            border-radius: 20px;
            padding: 3.5rem 2rem;
            box-shadow: 0 8px 20px rgba(27, 67, 50, 0.2);
            position: relative;
            overflow: hidden;
        }
        .hero-section::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 100%;
            background: var(--primary-mid);
            opacity: 0.3;
            transform: skewX(-15deg);
        }
        .product-card {
            background-color: var(--white);
            border: 1px solid #e9ecef;
            border-radius: 12px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
            position: relative;
        }
        .product-card img {
            transition: transform 0.3s ease;
        }
        .product-card:hover img {
            transform: scale(1.05);
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(27, 67, 50, 0.1);
            border-color: var(--primary-mid);
        }
        .product-card img {
            width: 100%;
            aspect-ratio: 4/3;
            object-fit: cover;
            background-color: #f8f9fa;
        }
        .product-card::before {
            content: 'UMKM Lokal';
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: rgba(27, 67, 50, 0.9);
            color: var(--white);
            font-size: 0.7rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            z-index: 2;
            backdrop-filter: blur(4px);
        }
        .price-tag {
            color: var(--primary-dark);
            font-weight: bold;
            font-size: 1.1rem;
            background-color: rgba(27, 67, 50, 0.05);
            padding: 5px 10px;
            border-radius: 8px;
            display: inline-block;
        }
        .footer-custom {
            background-color: var(--primary-dark);
            color: var(--white);
            padding: 2.5rem 0;
            margin-top: 4rem;
            flex-shrink: 0;
        }
        body {
            animation: fadeInBody 0.4s ease-in-out;
        }
        @keyframes fadeInBody {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes pulse-badge {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 107, 107, 0.7); }
            70% { transform: scale(1.1); box-shadow: 0 0 0 6px rgba(255, 107, 107, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 107, 107, 0); }
        }

        /* Style Badge Keranjang & Notif Pesanan */
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #FF6B6B;
            color: white;
            border-radius: 50%;
            font-size: 10px;
            padding: 2px 6px;
            font-weight: bold;
            display: none;
            animation: pulse-badge 2s infinite;
        }
        .sidebar-menu .badge.bg-danger {
            animation: pulse-badge 2s infinite;
        }
        
        .hero-section .bi-basket2-fill {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        .hero-section .btn-light {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        .hero-section .btn-light:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }
        html {
            scroll-behavior: smooth;
        }
        .hero-section h1 {
            font-size: clamp(2rem, 5vw, 4rem) !important;
            letter-spacing: -1px;
            line-height: 1.2;
        }
        
        /* CSS untuk Foto di Hero Banner */
        .hero-img {
            width: 100%;
            height: 350px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            border: 4px solid rgba(255, 255, 255, 0.2);
            animation: float 3s ease-in-out infinite;
        }
                       /* CSS untuk Carousel (Floating Glass Style) */
        .hero-carousel {
            border-radius: 18px;
            overflow: hidden;
            height: 350px; 
            position: relative;
            border: none;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.35);
            outline: 1px solid rgba(255, 255, 255, 0.15);
            outline-offset: -1px;
        }
        
        .hero-carousel::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 40%;
            background: linear-gradient(to top, rgba(0,0,0,0.5), transparent);
            z-index: 1;
            pointer-events: none;
        }

        .hero-carousel .carousel-inner, 
        .hero-carousel .carousel-item {
            height: 100%;
        }
        .hero-carousel .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover; 
        }
        
        .hero-carousel .carousel-control-prev, 
        .hero-carousel .carousel-control-next {
            width: 15%;
            opacity: 0.8;
            z-index: 10;
        }
        .hero-carousel .carousel-indicators {
            bottom: 15px;
            z-index: 10;
        }
        .hero-carousel .carousel-indicators button {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            border: 2px solid #FFFFFF;
            background-color: transparent;
            transition: 0.3s;
        }
        .hero-carousel .carousel-indicators .active {
            background-color: #FFFFFF;
            transform: scale(1.2);
        }
    </style>
</head>
<body>

    <!-- Navbar Profesional (Dengan Search Bar) -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/"><i class="bi bi-shop"></i> UMKM Lawang</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
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
                        <li class="nav-item">
                            <form action="/logout" method="POST">
                                @csrf
                                <button class="btn btn-outline-light rounded-pill px-3">Logout</button>
                            </form>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="content-wrapper">
        <div class="container mt-4">
            <!-- Hero Banner -->
            <div class="hero-section mb-5">
                <div class="row align-items-center position-relative" style="z-index: 2;">
                    <div class="col-md-7">
                        <h1 class="display-5 fw-bold mb-3">Dukung UMKM Lokal Nagari Lawang</h1>
                        <p class="lead mb-4 text-white-50">Temukan produk kuliner, kerajinan, dan oleh-oleh khas Lawang secara online. Belanja mudah, bantu ekonomi lokal tumbuh!</p>
                        <a href="/katalog" class="btn btn-light btn-lg rounded-pill px-4 fw-semibold text-dark">
                            Mulai Belanja <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                    <div class="col-md-5 text-center d-none d-md-block">
                        <!-- Mulai Carousel -->
                        <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel" data-bs-interval="3000">
                            
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                            </div>
                            
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ asset('images/lawang1.jpg') }}" alt="Pemandangan Lawang 1">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('images/lawang2.jpg') }}" alt="Pemandangan Lawang 2">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('images/lawang3.jpg') }}" alt="Pemandangan Lawang 3">
                                </div>
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Produk Terbaru -->
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h3 class="fw-bold mb-0 text-dark">Produk Terbaru</h3>
                    <p class="text-muted">Pilihan terbaik dari pelaku UMKM lokal</p>
                </div>
                <a href="/katalog" class="btn btn-sm btn-primary-custom rounded-pill">Lihat Semua</a>
            </div>

            <div class="row g-4">
                @foreach($produks as $produk)
                    <div class="col-md-3 col-sm-6">
                        <div class="card product-card h-100">
                            @if(str_starts_with($produk->gambar, 'http'))
                                <img src="{{ $produk->gambar }}" class="card-img-top" alt="{{ $produk->nama_produk }}">
                            @else
                                <img src="{{ asset('storage/'.$produk->gambar) }}" class="card-img-top" alt="{{ $produk->nama_produk }}">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title fw-semibold text-truncate">{{ $produk->nama_produk }}</h6>
                                <p class="text-muted small mb-1"><i class="bi bi-shop"></i> {{ $produk->toko->nama_toko }}</p>
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
                                    <div class="price-tag mb-2">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                                    <a href="/toko/{{ $produk->toko_id }}" class="btn btn-sm btn-primary-custom w-100 rounded-pill">Lihat Produk</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-custom text-center">
        <div class="container">
            <h5><i class="bi bi-shop"></i> UMKM Lawang</h5>
            <p class="mb-0 text-white-50">Platform digital untuk memperluas jangkauan pasar UMKM Nagari Lawang.</p>
            <small class="text-white-50">&copy; 2026 Marketplace UMKM Lawang.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>