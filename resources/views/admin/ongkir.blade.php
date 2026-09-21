<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Ongkir - UMKM Lawang</title>
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
        .custom-card { background-color: var(--white); border-radius: 15px; box-shadow: 0 4px 15px rgba(27, 67, 50, 0.05); border: none; padding: 30px; }
        .btn-success-custom { background-color: var(--primary-dark); border: none; color: var(--white); }
        .btn-success-custom:hover { background-color: var(--primary-mid); color: var(--white); }
        .mobile-toggle { display: none; background-color: var(--primary-dark); color: white; border: none; padding: 10px 15px; border-radius: 10px; font-size: 1.2rem; margin-bottom: 15px; }
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1020; }
        @media (max-width: 992px) { .sidebar { transform: translateX(-100%); } .sidebar.active { transform: translateX(0); } .main-content { margin-left: 0; padding: 15px; } .mobile-toggle { display: inline-block; } .sidebar-overlay.active { display: block; } }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header"><i class="bi bi-shield-lock fs-2"></i><h5 class="mt-2">Admin Panel</h5></div>
        <ul class="sidebar-menu">
            <li><a href="/admin/dashboard"><i class="bi bi-grid"></i> Dashboard</a></li>
            <li><a href="/admin/kategori"><i class="bi bi-tags"></i> Kelola Kategori</a></li>
            <li><a href="/admin/verifikasi"><i class="bi bi-shop"></i> Verifikasi Toko @if(isset($pendingTokoCount) && $pendingTokoCount > 0) <span class="badge bg-danger rounded-pill ms-auto">{{ $pendingTokoCount }}</span> @endif</a></li>
            <li><a href="/admin/pengguna"><i class="bi bi-people"></i> Kelola Pengguna</a></li>
            <li><a href="/admin/ongkir" class="active"><i class="bi bi-truck"></i> Pengaturan Ongkir</a></li>
        </ul>
        <div class="sidebar-footer"><form action="/logout" method="POST">@csrf<button type="submit" class="btn btn-outline-light w-100 rounded-pill"><i class="bi bi-box-arrow-right"></i> Logout</button></form></div>
    </div>

    <div class="main-content">
        <button class="mobile-toggle" onclick="toggleSidebar()"><i class="bi bi-list"></i> Menu</button>
        <h2 class="fw-bold mb-4"><i class="bi bi-truck"></i> Pengaturan Ongkos Kirim</h2>

        @if(session('success'))<div class="alert alert-success rounded-pill">{{ session('success') }}</div>@endif

        <div class="custom-card">
            <p class="text-muted">Atur biaya ongkos kirim flat (seragam) untuk semua pesanan. Biaya ini akan otomatis ditambahkan ke total pembelian pembeli saat checkout.</p>
            <form action="/admin/ongkir" method="POST" style="max-width: 400px;">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Biaya Ongkir (Rp)</label>
                    <input type="number" name="ongkir" class="form-control" value="{{ $ongkir }}" required min="0">
                </div>
                <button type="submit" class="btn btn-success-custom rounded-pill px-4"><i class="bi bi-save"></i> Simpan Pengaturan</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>function toggleSidebar() { document.getElementById('sidebar').classList.toggle('active'); document.getElementById('overlay').classList.toggle('active'); }</script>
</body>
</html>