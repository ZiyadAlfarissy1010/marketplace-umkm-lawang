<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - UMKM Lawang</title>
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
        
        /* Style Badge Keranjang */
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
        }
        
        .order-card {
            background-color: var(--white);
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(27, 67, 50, 0.05);
            border: none;
        }
        .footer-custom {
            background-color: var(--primary-dark);
            color: var(--white);
            padding: 2.5rem 0;
            margin-top: 4rem;
            flex-shrink: 0;
        }
        .toast-container {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1050;
        }
        .custom-toast {
            background-color: var(--white);
            color: var(--text-dark);
            border-left: 5px solid var(--primary-dark);
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .payment-info-box {
            background-color: #e7f5ff;
            border: 1px solid #b3d7ff;
            border-radius: 12px;
            padding: 20px;
            margin-top: 1rem;
        }
    </style>
</head>
<body>

    <div class="toast-container">
        <div id="cartToast" class="toast custom-toast" role="alert" data-bs-delay="1500">
            <div class="toast-body"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Berhasil!</strong> {{ session('success') }}</div>
        </div>
        <div id="errorToast" class="toast custom-toast" role="alert" data-bs-delay="3000" style="border-left-color: #dc3545;">
            <div class="toast-body"><i class="bi bi-x-circle-fill text-danger me-2"></i> <strong>Gagal!</strong> {{ session('error') }}</div>
        </div>
    </div>

    <!-- Modal Detail Pesanan (Pop-up) -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px; border: none;">
                <div class="modal-header" style="background-color: var(--primary-dark); color: var(--white); border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title"><i class="bi bi-receipt-cutoff"></i> Detail Pesanan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="detailModalBody"></div>
            </div>
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
                    <li class="nav-item"><form action="/logout" method="POST">@csrf<button class="btn btn-outline-light rounded-pill px-3">Logout</button></form></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="content-wrapper">
        <div class="container mt-4">
            <h2 class="fw-bold mb-4"><i class="bi bi-receipt"></i> Riwayat Pesanan</h2>

            @if($pesanans->count() > 0)
                @foreach($pesanans as $pesanan)
                    <div class="order-card p-4 mb-3">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3 flex-wrap gap-2">
                            <div>
                                <span class="text-muted small">Kode Pesanan:</span>
                                <h5 class="mb-0 d-inline">#ORD-{{ $pesanan->id }}</h5>
                                @if($pesanan->metode_pembayaran == 'transfer')
                                    <span class="badge bg-secondary ms-2"><i class="bi bi-bank"></i> Transfer Bank</span>
                                @elseif($pesanan->metode_pembayaran == 'qris')
                                    <span class="badge bg-info text-dark ms-2"><i class="bi bi-qr-code"></i> QRIS</span>
                                @else
                                    <span class="badge bg-success ms-2"><i class="bi bi-whatsapp"></i> WhatsApp</span>
                                @endif
                            </div>
                            <div>
                                @if($pesanan->status == 'checkout') <span class="badge bg-info text-dark px-3 py-2">Menunggu Diproses (WA)</span>
                                @elseif($pesanan->status == 'pending_payment') <span class="badge bg-warning text-dark px-3 py-2">Menunggu Pembayaran</span>
                                @elseif($pesanan->status == 'dibayar') <span class="badge bg-primary px-3 py-2">Menunggu Konfirmasi Penjual</span>
                                @elseif($pesanan->status == 'diproses') <span class="badge bg-warning text-dark px-3 py-2">Diproses Penjual</span>
                                @elseif($pesanan->status == 'dikirim') <span class="badge bg-primary px-3 py-2">Sedang Dikirim</span>
                                @elseif($pesanan->status == 'selesai') <span class="badge bg-success px-3 py-2">Selesai</span>
                                @elseif($pesanan->status == 'dibatalkan') <span class="badge bg-danger px-3 py-2">Dibatalkan</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <p class="text-muted mb-0">Total Pembayaran <span class="badge bg-secondary ms-1"><i class="bi bi-truck"></i> {{ $pesanan->ekspedisi }}</span></p>
                                <h4 class="fw-bold mb-0" style="color: var(--primary-dark);">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</h4>
                                <small class="text-muted">(Termasuk Ongkir: Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }})</small>
                            </div>
                            <div class="d-flex gap-2">
                                @if($pesanan->status == 'checkout' || $pesanan->status == 'pending_payment')
                                    <form action="/pembeli/pesanan/{{ $pesanan->id }}/batalkan" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                        @csrf
                                        <button class="btn btn-outline-danger rounded-pill px-4">Batalkan</button>
                                    </form>
                                @endif

                                @if($pesanan->status == 'dikirim')
                                    <form action="/pembeli/pesanan/{{ $pesanan->id }}/terima" method="POST" onsubmit="return confirm('Konfirmasi bahwa Anda sudah menerima barang ini?')">
                                        @csrf
                                        <button class="btn btn-success rounded-pill px-4"><i class="bi bi-box-seam"></i> Barang Diterima</button>
                                    </form>
                                @endif

                                @if($pesanan->status == 'checkout' && $pesanan->wa_link)
                                    <a href="{{ $pesanan->wa_link }}" target="_blank" class="btn btn-success rounded-pill px-4">
                                        <i class="bi bi-whatsapp"></i> Chat Penjual via WA
                                    </a>
                                @endif

                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" onclick="lihatDetail({{ $pesanan->id }})">Lihat Detail</button>
                            </div>
                        </div>

                        {{-- TAMPILAN KARTU PEMBAYARAN TRANSFER BANK / QRIS --}}
                        @if($pesanan->status == 'pending_payment' && ($pesanan->metode_pembayaran == 'transfer' || $pesanan->metode_pembayaran == 'qris'))
                            @php
                                $toko = $pesanan->detailPesanans->first()->produk->toko;
                            @endphp
                            <div class="payment-info-box mt-4">
                                <h5 class="text-primary mb-3">
                                    @if($pesanan->metode_pembayaran == 'qris')
                                        <i class="bi bi-qr-code"></i> Instruksi Pembayaran QRIS
                                    @else
                                        <i class="bi bi-credit-card"></i> Instruksi Pembayaran Transfer Bank
                                    @endif
                                </h5>
                                <p class="mb-1">Silakan bayar sejumlah <strong>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</strong> melalui:</p>
                                
                                <div class="d-flex align-items-center justify-content-center bg-white p-3 rounded-3 mb-3">
                                    @if($pesanan->metode_pembayaran == 'qris')
                                        @if($toko->qris_image)
                                            <img src="{{ asset('storage/'.$toko->qris_image) }}" width="200" alt="QRIS">
                                        @else
                                            <p class="text-danger">Penjual belum mengunggah QRIS. Silakan hubungi penjual.</p>
                                        @endif
                                    @else
                                        <div class="text-center">
                                            <h4 class="mb-0 text-primary"><strong>{{ $toko->nama_bank ?? 'Bank Belum Diisi' }}</strong></h4>
                                            <p class="mb-0 fs-5 tracking-wider">{{ $toko->no_rekening ?? '0000000000' }}</p>
                                            <small class="text-muted">a.n. {{ $toko->atas_nama ?? $toko->nama_toko }}</small>
                                        </div>
                                    @endif
                                </div>
                                
                                <form action="/pembeli/pesanan/{{ $pesanan->id }}/upload-bukti" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <label class="form-label fw-semibold">Upload Bukti Pembayaran:</label>
                                    <div class="input-group">
                                        <input type="file" name="bukti_bayar" class="form-control" accept="image/*" required>
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Unggah</button>
                                    </div>
                                    <small class="text-muted">Format: JPG, PNG. Maks 2MB. Setelah diunggah, tunggu konfirmasi dari penjual.</small>
                                </form>
                            </div>
                        @endif

                        {{-- TAMPILAN JIKA SUDAH UPLOAD BUKTI BAYAR --}}
                        @if($pesanan->status == 'dibayar' && $pesanan->metode_pembayaran == 'transfer')
                            <div class="payment-info-box mt-4 bg-light">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ asset('storage/'.$pesanan->bukti_bayar) }}" width="80" height="80" style="object-fit:cover; border-radius:8px;" alt="Bukti Bayar">
                                    <div>
                                        <h6 class="mb-0 text-primary"><i class="bi bi-check-circle"></i> Bukti Pembayaran Diunggah</h6>
                                        <small class="text-muted">Menunggu penjual mengkonfirmasi pembayaran Anda.</small>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                @endforeach
            @else
                <div class="order-card text-center py-5">
                    <i class="bi bi-bag-x fs-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">Belum Ada Pesanan</h4>
                    <p class="text-muted">Anda belum pernah melakukan checkout. Yuk mulai belanja!</p>
                    <a href="/katalog" class="btn btn-lg rounded-pill px-4 mt-2" style="background-color: var(--primary-dark); color: var(--white);">Mulai Belanja</a>
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
        @if(session('success')) document.addEventListener('DOMContentLoaded', () => new bootstrap.Toast(document.getElementById('cartToast')).show()); @endif
        @if(session('error')) document.addEventListener('DOMContentLoaded', () => new bootstrap.Toast(document.getElementById('errorToast')).show()); @endif

        function lihatDetail(pesananId) {
            fetch(`/pembeli/pesanan/${pesananId}/detail`, { method: 'GET', headers: { 'Accept': 'application/json' } })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    document.getElementById('detailModalBody').innerHTML = data.html;
                    new bootstrap.Modal(document.getElementById('detailModal')).show();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>