<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - UMKM Lawang</title>
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
        <div class="sidebar-header"><i class="bi bi-shop fs-2"></i><h5 class="mt-2">{{ $toko->nama_toko }}</h5><small>Dashboard UMKM</small></div>
        <ul class="sidebar-menu">
            <li>
                <a href="/penjual/dashboard" class="{{ request()->routeIs('penjual.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid"></i> Dashboard
                    @if(isset($lowStockCount) && $lowStockCount > 0)
                        <span class="badge bg-danger rounded-pill ms-auto">{{ $lowStockCount }}</span>
                    @endif
                </a>
            </li>
            <li>
                <a href="/penjual/pesanan" class="{{ request()->routeIs('penjual.pesanan') ? 'active' : '' }}">
                    <i class="bi bi-bag-check"></i> Pesanan Masuk 
                    @if(isset($newOrdersCount) && $newOrdersCount > 0) 
                        <span class="badge bg-danger rounded-pill ms-auto">{{ $newOrdersCount }}</span> 
                    @endif
                </a>
            </li>
            <li><a href="/penjual/laporan" class="{{ request()->routeIs('penjual.laporan') ? 'active' : '' }}"><i class="bi bi-graph-up"></i> Laporan Penjualan</a></li>
            <li><a href="/penjual/profil" class="{{ request()->routeIs('penjual.profil') ? 'active' : '' }}"><i class="bi bi-shop-window"></i> Profil Toko</a></li>
            <li><a href="/profile/edit" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}"><i class="bi bi-person-circle"></i> Profil Akun</a></li>
        </ul>
        <div class="sidebar-footer"><form action="/logout" method="POST">@csrf<button type="submit" class="btn btn-outline-light w-100 rounded-pill"><i class="bi bi-box-arrow-right"></i> Logout</button></form></div>
    </div>

    <div class="main-content">
        <button class="mobile-toggle" onclick="toggleSidebar()"><i class="bi bi-list"></i> Menu</button>
        <h2 class="fw-bold mb-4"><i class="bi bi-graph-up"></i> Laporan Penjualan</h2>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="custom-card" style="border-left: 5px solid var(--primary-dark);">
                    <h6 class="text-muted">Total Keuntungan (Pendapatan)</h6>
                    <h3 class="fw-bold text-success">Rp {{ number_format($totalKeuntunganKeseluruhan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="custom-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead><tr><th>Nama Produk</th><th>Stok Tersisa</th><th>Terjual</th><th>Pendapatan</th></tr></thead>
                    <tbody>
                        @foreach($laporan as $item)
                        <tr>
                            <td class="fw-semibold">{{ $item['nama'] }}</td>
                            <td><span class="badge bg-light text-dark">{{ $item['stok'] }} Pcs</span></td>
                            <td class="fw-bold text-primary">{{ $item['terjual'] }} Pcs</td>
                            <td class="text-success fw-bold">Rp {{ number_format($item['pendapatan'], 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>function toggleSidebar() { document.getElementById('sidebar').classList.toggle('active'); document.getElementById('overlay').classList.toggle('active'); }</script>
</body>
</html>