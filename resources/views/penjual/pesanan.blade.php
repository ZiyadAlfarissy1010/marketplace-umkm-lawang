<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Masuk - UMKM Lawang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root { --primary-dark: #1B4332; --primary-mid: #2D6A4F; --bg-light: #E0FBFC; --text-dark: #1B2A2E; --white: #FFFFFF; }
        body { background-color: var(--bg-light); font-family: 'Segoe UI', sans-serif; color: var(--text-dark); min-height: 100vh; }
        .sidebar { width: 250px; background-color: var(--primary-dark); color: var(--white); height: 100vh; position: fixed; top: 0; left: 0; padding: 20px 0; display: flex; flex-direction: column; transition: transform 0.3s ease-in-out; z-index: 1030; }
        .sidebar-header { text-align: center; padding: 20px 10px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 20px; }
        .sidebar-menu { list-style: none; padding: 0; margin: 0; flex-grow: 1; }
        .sidebar-menu li a { display: flex; align-items: center; padding: 15px 25px; color: rgba(255,255,255,0.8); text-decoration: none; transition: 0.3s; }
        .sidebar-menu li a:hover, .sidebar-menu li a.active { background-color: var(--primary-mid); color: var(--white); border-left: 4px solid var(--white); }
        .sidebar-menu li a i { margin-right: 10px; }
        .sidebar-footer { padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); }
        .main-content { margin-left: 250px; padding: 30px; transition: margin-left 0.3s ease; }
        .custom-card { background-color: var(--white); border-radius: 15px; box-shadow: 0 4px 15px rgba(27, 67, 50, 0.05); border: none; padding: 20px; }
        .btn-success-custom { background-color: var(--primary-dark); border: none; color: var(--white); }
        .btn-success-custom:hover { background-color: var(--primary-mid); color: var(--white); }
        .mobile-toggle { display: none; background-color: var(--primary-dark); color: white; border: none; padding: 10px 15px; border-radius: 10px; font-size: 1.2rem; margin-bottom: 15px; }
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1020; }
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); } .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 15px; } .mobile-toggle { display: inline-block; }
            .sidebar-overlay.active { display: block; }
        }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="bi bi-shop fs-2"></i>
            <h5 class="mt-2">{{ $toko->nama_toko }}</h5>
            <small>Dashboard UMKM</small>
        </div>
        <ul class="sidebar-menu">
            <li><a href="/penjual/dashboard"><i class="bi bi-grid"></i> Dashboard</a></li>
            <li><a href="/penjual/pesanan" class="active"><i class="bi bi-bag-check"></i> Pesanan Masuk</a></li>
            <li><a href="/penjual/laporan"><i class="bi bi-graph-up"></i> Laporan Penjualan</a></li>
            <li><a href="/penjual/profil"><i class="bi bi-shop-window"></i> Profil Toko</a></li>
            <li><a href="/profile/edit"><i class="bi bi-person-circle"></i> Profil Akun</a></li>
        </ul>
        <div class="sidebar-footer">
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-light w-100 rounded-pill"><i class="bi bi-box-arrow-right"></i> Logout</button>
            </form>
        </div>
    </div>

    <!-- Modal Detail Pesanan (Pop-up) -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px; border: none;">
                <div class="modal-header" style="background-color: var(--primary-dark); color: var(--white); border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title"><i class="bi bi-receipt-cutoff"></i> Detail Pesanan Pembeli</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="detailModalBody">
                    <!-- Isi detail akan dimasukkan di sini oleh JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <button class="mobile-toggle" onclick="toggleSidebar()"><i class="bi bi-list"></i> Menu</button>
        
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h2 class="fw-bold mb-0"><i class="bi bi-bag-check"></i> Pesanan Masuk</h2>
        </div>

        @if(session('success'))<div class="alert alert-success rounded-pill">{{ session('success') }}</div>@endif

        <div class="custom-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Pembeli</th>
                            <th>Total</th>
                            <th>Metode Bayar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($pesanans->count() > 0)
                            @foreach($pesanans as $pesanan)
                            <tr>
                                <td class="fw-bold">#ORD-{{ $pesanan->id }}</td>
                                <td>{{ $pesanan->user->name }}</td>
                                <td class="text-success fw-bold">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                                <td>
                                    @if($pesanan->metode_pembayaran == 'transfer')
                                        <span class="badge bg-secondary"><i class="bi bi-bank"></i> Transfer</span>
                                    @elseif($pesanan->metode_pembayaran == 'qris')
                                        <span class="badge bg-info text-dark"><i class="bi bi-qr-code"></i> QRIS</span>
                                    @else
                                        <span class="badge bg-success"><i class="bi bi-whatsapp"></i> WhatsApp</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pesanan->status == 'checkout') <span class="badge bg-info text-dark">Menunggu WA</span>
                                    @elseif($pesanan->status == 'pending_payment') <span class="badge bg-warning text-dark">Menunggu Bayar</span>
                                    @elseif($pesanan->status == 'dibayar') <span class="badge bg-primary">Bayar Dibayar</span>
                                    @elseif($pesanan->status == 'diproses') <span class="badge bg-warning text-dark">Diproses</span>
                                    @elseif($pesanan->status == 'dikirim') <span class="badge bg-primary">Dikirim</span>
                                    @elseif($pesanan->status == 'selesai') <span class="badge bg-success">Selesai</span>
                                    @elseif($pesanan->status == 'dibatalkan') <span class="badge bg-danger">Dibatalkan</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-2">
                                        <!-- TOMBOL LIHAT DETAIL BARU -->
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" onclick="lihatDetail({{ $pesanan->id }})">
                                            <i class="bi bi-eye"></i> Detail Pesanan
                                        </button>

                                        @if($pesanan->status == 'selesai')
                                            <span class="badge bg-success p-2">Selesai</span>
                                        @elseif($pesanan->status == 'dibatalkan')
                                            <span class="badge bg-danger p-2">Dibatalkan</span>
                                        @elseif($pesanan->status == 'dibayar')
                                            <a href="{{ asset('storage/'.$pesanan->bukti_bayar) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                                                <i class="bi bi-image"></i> Lihat Bukti
                                            </a>
                                            <form action="/penjual/pesanan/{{ $pesanan->id }}/update" method="POST">
                                                @csrf
                                                <input type="hidden" name="konfirmasi_bayar" value="true">
                                                <button type="submit" class="btn btn-sm btn-success-custom rounded-pill w-100"><i class="bi bi-check-lg"></i> Konfirmasi Bayar</button>
                                            </form>
                                        @else
                                            <form action="/penjual/pesanan/{{ $pesanan->id }}/update" method="POST">@csrf
                                                <div class="input-group">
                                                    <select name="status" class="form-select form-select-sm" style="border-radius: 20px 0 0 20px;">
                                                        @if($pesanan->status == 'checkout')
                                                            <option value="checkout" selected>Menunggu WA</option>
                                                            <option value="diproses">Diproses</option>
                                                        @elseif($pesanan->status == 'diproses')
                                                            <option value="diproses" selected>Diproses</option>
                                                            <option value="dikirim">Dikirim</option>
                                                        @elseif($pesanan->status == 'dikirim')
                                                            <option value="dikirim" selected>Dikirim</option>
                                                        @endif
                                                    </select>
                                                    <button type="submit" class="btn btn-sm btn-success-custom" style="border-radius: 0 20px 20px 0;"><i class="bi bi-check-lg"></i></button>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr><td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-1 d-block mb-2"></i>Belum ada pesanan masuk.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('active');
        }

        // Script AJAX untuk Lihat Detail Pesanan
        function lihatDetail(pesananId) {
            fetch(`/penjual/pesanan/${pesananId}/detail`, {
                method: 'GET',
                headers: { 'Accept': 'application/json' }
            })
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