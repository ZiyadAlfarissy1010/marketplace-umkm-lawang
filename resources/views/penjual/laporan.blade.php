<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - UMKM Lawang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-dark: #1B4332; --primary-mid: #2D6A4F; --bg-light: #E0FBFC;
            --text-dark: #1B2A2E; --white: #FFFFFF;
        }
        body { background-color: var(--bg-light); font-family: 'Segoe UI', sans-serif; color: var(--text-dark); min-height: 100vh; display: flex; }
        .sidebar { width: 250px; background-color: var(--primary-dark); color: var(--white); height: 100vh; position: fixed; top: 0; left: 0; padding: 20px 0; display: flex; flex-direction: column; }
        .sidebar-header { text-align: center; padding: 20px 10px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 20px; }
        .sidebar-menu { list-style: none; padding: 0; margin: 0; flex-grow: 1; }
        .sidebar-menu li a { display: block; padding: 15px 25px; color: rgba(255,255,255,0.8); text-decoration: none; transition: 0.3s; }
        .sidebar-menu li a:hover, .sidebar-menu li a.active { background-color: var(--primary-mid); color: var(--white); border-left: 4px solid var(--white); }
        .sidebar-menu li a i { margin-right: 10px; }
        .sidebar-footer { padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); }
        .main-content { margin-left: 250px; flex-grow: 1; padding: 30px; width: calc(100% - 250px); }
        .custom-card { background-color: var(--white); border-radius: 15px; box-shadow: 0 4px 15px rgba(27, 67, 50, 0.05); border: none; padding: 20px; }
        .stat-card { border-left: 5px solid var(--primary-dark); }
    body {
    animation: fadeInBody 0.4s ease-in-out;
}
@keyframes fadeInBody {
    from { opacity: 0; }
    to { opacity: 1; }
}
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="bi bi-shop fs-2"></i>
            <h5 class="mt-2">{{ $toko->nama_toko }}</h5>
            <small>Dashboard UMKM</small>
        </div>
        <ul class="sidebar-menu">
            <li><a href="/penjual/dashboard"><i class="bi bi-grid"></i> Dashboard</a></li>
            <li><a href="/penjual/pesanan"><i class="bi bi-bag-check"></i> Pesanan Masuk @if(isset($newOrdersCount) && $newOrdersCount > 0) <span class="badge bg-danger rounded-pill ms-2">{{ $newOrdersCount }}</span> @endif</a></li>
            <li><a href="/penjual/laporan" class="active"><i class="bi bi-graph-up"></i> Laporan Penjualan</a></li>
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

    <div class="main-content">
        <h2 class="fw-bold mb-4"><i class="bi bi-graph-up"></i> Laporan Penjualan Produk</h2>

        <!-- Kartu Statistik -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="custom-card stat-card">
                    <h6 class="text-muted">Total Keuntungan (Pendapatan Kotor)</h6>
                    <h3 class="fw-bold text-success">Rp {{ number_format($totalKeuntunganKeseluruhan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <!-- Tabel Laporan -->
        <div class="custom-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Stok Tersisa</th>
                            <th>Total Terjual</th>
                            <th>Keuntungan (Pendapatan)</th>
                        </tr>
                    </thead>
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
</body>
</html>