<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keranjang Belanja - UMKM Lawang</title>
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
        .cart-card { background-color: var(--white); border-radius: 15px; box-shadow: 0 4px 15px rgba(27, 67, 50, 0.05); border: none; }
        .footer-custom { background-color: var(--primary-dark); color: var(--white); padding: 2.5rem 0; margin-top: 4rem; flex-shrink: 0; }
        .toast-container { position: fixed; bottom: 20px; left: 20px; z-index: 1050; }
        .custom-toast { background-color: var(--white); color: var(--text-dark); border-left: 5px solid var(--primary-dark); border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .custom-toast.error { border-left-color: #dc3545; }
        .cart-item-img { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; }
        
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
    </style>
</head>
<body>

    <div class="toast-container">
        <div id="cartToast" class="toast custom-toast" role="alert" data-bs-delay="1500">
            <div class="toast-body" id="toastMessage"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Berhasil!</strong></div>
        </div>
    </div>

    <!-- Modal Pilihan Metode Pembayaran -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px; border: none;">
                <div class="modal-header" style="background-color: var(--primary-dark); color: var(--white); border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title"><i class="bi bi-credit-card"></i> Pilih Metode Pembayaran</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/pembeli/checkout" method="POST" id="formCheckout">
                    @csrf
                    <div class="modal-body p-4">
                        <p class="text-muted small">Silakan pilih bagaimana Anda ingin menyelesaikan pembayaran untuk pesanan ini.</p>
                        
                        <div class="form-check border rounded-3 p-3 mb-2">
                            <input class="form-check-input" type="radio" name="metode_pembayaran" value="whatsapp" id="metodeWa" checked required>
                            <label class="form-check-label w-100" for="metodeWa">
                                <strong><i class="bi bi-whatsapp text-success"></i> Bayar via WhatsApp</strong>
                                <small class="d-block text-muted">Konfirmasi pembayaran langsung ke penjual via chat WhatsApp.</small>
                            </label>
                        </div>

                        <div class="form-check border rounded-3 p-3 mb-2">
                            <input class="form-check-input" type="radio" name="metode_pembayaran" value="transfer" id="metodeTransfer" required>
                            <label class="form-check-label w-100" for="metodeTransfer">
                                <strong><i class="bi bi-bank text-primary"></i> Transfer Bank (Upload Bukti)</strong>
                                <small class="d-block text-muted">Transfer ke rekening penjual, lalu unggah bukti transfer di halaman pesanan.</small>
                            </label>
                        </div>
                        
                        <div class="form-check border rounded-3 p-3">
                            <input class="form-check-input" type="radio" name="metode_pembayaran" value="qris" id="metodeQris" required>
                            <label class="form-check-label w-100" for="metodeQris">
                                <strong><i class="bi bi-qr-code"></i> Scan QRIS</strong>
                                <small class="d-block text-muted">Scan kode QRIS penjual menggunakan e-wallet/m-banking, lalu unggah bukti bayar.</small>
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer border-0 justify-content-center">
                        <button type="submit" class="btn btn-primary-custom btn-lg rounded-pill px-5 w-100">Lanjutkan Checkout</button>
                    </div>
                </form>
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
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('pembeli.dashboard') ? 'active-nav' : '' }}" href="/pembeli/dashboard">Pesanan</a></li>
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
            <h2 class="fw-bold mb-4"><i class="bi bi-cart3"></i> Keranjang Belanja</h2>

            @if(session('error'))
                <div class="alert alert-danger rounded-pill">{{ session('error') }}</div>
            @endif

            @if(count($detailPesanans) > 0)
                <div class="cart-card p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr><th>Produk</th><th>Harga</th><th style="width: 150px;">Jumlah</th><th>Subtotal</th><th>Aksi</th></tr>
                            </thead>
                            <tbody>
                                @foreach($detailPesanans as $detail)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @if(str_starts_with($detail->produk->gambar, 'http'))
                                                <img src="{{ $detail->produk->gambar }}" class="cart-item-img" alt="...">
                                            @else
                                                <img src="{{ asset('storage/'.$detail->produk->gambar) }}" class="cart-item-img" alt="...">
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $detail->produk->nama_produk }}</h6>
                                                <small class="text-muted">Stok Maksimal: {{ $detail->produk->stok }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                    <td>
                                        <input type="number" class="form-control jumlah-item" 
                                               value="{{ $detail->jumlah }}" 
                                               min="1" 
                                               max="{{ $detail->produk->stok }}"
                                               data-id="{{ $detail->id }}"
                                               data-harga="{{ $detail->harga_satuan }}"
                                               oninput="updateJumlah(this)">
                                    </td>
                                    <td class="fw-bold subtotal-cell">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    <td>
                                        <form action="/pembeli/keranjang/hapus/{{ $detail->id }}" method="POST" onsubmit="return confirm('Yakin hapus produk ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger rounded-pill"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold fs-5">Total Bayar:</td>
                                    <td class="text-danger fw-bold fs-5" id="grand-total">Rp 0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between mt-4 flex-wrap gap-2">
                        <a href="/katalog" class="btn btn-outline-secondary rounded-pill px-4"><i class="bi bi-arrow-left"></i> Lanjut Belanja</a>
                        <!-- Tombol Checkout sekarang memunculkan Modal -->
                        <button type="button" class="btn btn-primary-custom btn-lg rounded-pill px-5" data-bs-toggle="modal" data-bs-target="#paymentModal">
                            Checkout Sekarang <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
            @else
                <div class="cart-card text-center py-5">
                    <i class="bi bi-bag-x fs-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">Keranjang Anda masih kosong</h4>
                    <p class="text-muted">Yuk mulai belanja dan dukung UMKM lokal Lawang!</p>
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
        function hitungTotal() {
            let grandTotal = 0;
            document.querySelectorAll('.jumlah-item').forEach(function(input) {
                let jumlah = input.value, harga = input.getAttribute('data-harga'), subtotal = jumlah * harga;
                let row = input.parentElement.parentElement;
                row.querySelector('.subtotal-cell').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
                grandTotal += subtotal;
            });
            document.getElementById('grand-total').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
        }

        function updateJumlah(input) {
            let detailId = input.getAttribute('data-id'), jumlah = input.value, csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            fetch(`/pembeli/keranjang/update/${detailId}`, {
                method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: JSON.stringify({ jumlah: jumlah })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) { hitungTotal(); } 
                else {
                    input.value = data.stok_tersedia; hitungTotal();
                    let toast = document.getElementById('cartToast');
                    toast.className = 'toast custom-toast error';
                    document.getElementById('toastMessage').innerHTML = `<i class="bi bi-x-circle-fill text-danger me-2"></i> <strong>Gagal!</strong> ${data.message}`;
                    new bootstrap.Toast(toast).show();
                }
            });
        }

        // Validasi stok sebelum modal checkout di-submit
        document.getElementById('formCheckout').addEventListener('submit', function(e) {
            e.preventDefault(); 
            let csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch('/pembeli/checkout/cek-stok', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    e.target.submit();
                } else {
                    // Tutup modal pembayaran
                    bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
                    
                    // Tampilkan error di toast
                    let toast = document.getElementById('cartToast');
                    toast.className = 'toast custom-toast error';
                    document.getElementById('toastMessage').innerHTML = `<i class="bi bi-x-circle-fill text-danger me-2"></i> <strong>Gagal!</strong> ${data.message}`;
                    new bootstrap.Toast(toast).show();
                }
            });
        });

        window.onload = hitungTotal;
    </script>
</body>
</html>