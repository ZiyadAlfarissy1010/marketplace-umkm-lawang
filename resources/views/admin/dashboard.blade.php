<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - UMKM Lawang</title>
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
        .stat-card { background-color: var(--white); border-radius: 15px; padding: 20px; box-shadow: 0 4px 15px rgba(27, 67, 50, 0.05); border: none; display: flex; align-items: center; gap: 15px; }
        .stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--white); }
        .table-card { background-color: var(--white); border-radius: 15px; padding: 20px; box-shadow: 0 4px 15px rgba(27, 67, 50, 0.05); border: none; }
        .mobile-toggle { display: none; background-color: var(--primary-dark); color: white; border: none; padding: 10px 15px; border-radius: 10px; font-size: 1.2rem; margin-bottom: 15px; }
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1020; }
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); } .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 15px; } .mobile-toggle { display: inline-block; }
            .sidebar-overlay.active { display: block; }
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

/* Ganti selector sesuai halaman */
/* Untuk Halaman Pembeli (Katalog/Beranda) */
.cart-badge {
    animation: pulse-badge 2s infinite;
}

/* Untuk Halaman Admin & Penjual (Sidebar) */
.sidebar-menu .badge.bg-danger {
    animation: pulse-badge 2s infinite;
}
   </style>
</head>
<body>
    <div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header"><i class="bi bi-shield-lock fs-2"></i><h5 class="mt-2">Admin Panel</h5><small>Marketplace UMKM</small></div>
        <ul class="sidebar-menu">
            <li><a href="/admin/dashboard" class="active"><i class="bi bi-grid"></i> Dashboard</a></li>
            <li><a href="/admin/kategori"><i class="bi bi-tags"></i> Kelola Kategori</a></li>
            <li><a href="/admin/verifikasi"><i class="bi bi-shop"></i> Verifikasi Toko @if(isset($pendingTokoCount) && $pendingTokoCount > 0) <span class="badge bg-danger rounded-pill ms-auto">{{ $pendingTokoCount }}</span> @endif</a></li>
            <li><a href="/admin/pengguna"><i class="bi bi-people"></i> Kelola Pengguna</a></li>
            <li><a href="/profile/edit"><i class="bi bi-person-circle"></i> Profil Akun</a></li>
            <li><a href="/admin/ongkir"><i class="bi bi-truck"></i> Pengaturan Ongkir</a></li>
        </ul>
        <div class="sidebar-footer"><form action="/logout" method="POST">@csrf<button type="submit" class="btn btn-outline-light w-100 rounded-pill"><i class="bi bi-box-arrow-right"></i> Logout</button></form></div>
    </div>

    <!-- Modal Daftar Produk Toko (Pop-up) -->
    <div class="modal fade" id="produkModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px; border: none;">
                <div class="modal-header" style="background-color: var(--primary-dark); color: var(--white); border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title"><i class="bi bi-box-seam"></i> Daftar Produk Toko</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="produkModalBody"></div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <button class="mobile-toggle" onclick="toggleSidebar()"><i class="bi bi-list"></i> Menu</button>
        <h2 class="fw-bold mb-4">Dashboard & Laporan Kinerja Web</h2>
        <div class="row g-3 mb-4">
            <div class="col-md-4"><div class="stat-card"><div class="stat-icon" style="background-color: #198754;"><i class="bi bi-cash-stack"></i></div><div><h6 class="text-muted mb-0">Total Transaksi pada Web</h6><h5 class="fw-bold mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h5></div></div></div>
            <div class="col-md-4"><div class="stat-card"><div class="stat-icon" style="background-color: #0DCAF0;"><i class="bi bi-receipt"></i></div><div><h6 class="text-muted mb-0">Total Transaksi</h6><h5 class="fw-bold mb-0">{{ $totalTransaksi }} Pesanan</h5></div></div></div>
            <div class="col-md-4"><div class="stat-card"><div class="stat-icon" style="background-color: #FFC107;"><i class="bi bi-hourglass-split"></i></div><div><h6 class="text-muted mb-0">Toko Menunggu Verifikasi</h6><h5 class="fw-bold mb-0">{{ $tokoPending }} Toko</h5></div></div></div>
        </div>
        <div class="table-card">
            <h5 class="fw-bold mb-3"><i class="bi bi-shop-window"></i> Laporan Data Toko & Penjualan</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nama Toko</th>
                            <th>Status</th>
                            <th>Jumlah Produk</th>
                            <th>Total Penjualan</th>
                            <th class="text-end">Aksi</th> <!-- KOLOM AKSI BARU -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporanToko as $toko)
                        <tr>
                            <td class="fw-semibold">{{ $toko->nama_toko }}</td>
                            <td>@if($toko->status_verifikasi == 'disetujui') <span class="badge bg-success">Disetujui</span> @elseif($toko->status_verifikasi == 'pending') <span class="badge bg-warning text-dark">Pending</span> @else <span class="badge bg-danger">Ditolak</span> @endif</td>
                            <td>{{ $toko->jumlah_produk }} Produk</td>
                            <td class="fw-bold text-success">Rp {{ number_format($toko->total_penjualan, 0, ',', '.') }}</td>
                            <td class="text-end">
                                <!-- TOMBOL LIHAT PRODUK -->
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" onclick="lihatProduk({{ $toko->id }})">
                                    <i class="bi bi-eye"></i> Lihat Produk
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('active'); document.getElementById('overlay').classList.toggle('active'); }

        // Script AJAX untuk Lihat Produk Toko
        function lihatProduk(tokoId) {
            fetch(`/admin/toko/${tokoId}/produk`, {
                method: 'GET',
                headers: { 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    document.getElementById('produkModalBody').innerHTML = data.html;
                    new bootstrap.Modal(document.getElementById('produkModal')).show();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>