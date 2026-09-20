<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Toko - UMKM Lawang</title>
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
        <div class="sidebar-header"><i class="bi bi-shield-lock fs-2"></i><h5 class="mt-2">Admin Panel</h5></div>
        <ul class="sidebar-menu">
            <li><a href="/admin/dashboard"><i class="bi bi-grid"></i> Dashboard</a></li>
            <li><a href="/admin/kategori"><i class="bi bi-tags"></i> Kelola Kategori</a></li>
            <li><a href="/admin/verifikasi" class="active"><i class="bi bi-shop"></i> Verifikasi Toko @if(isset($pendingTokoCount) && $pendingTokoCount > 0) <span class="badge bg-danger rounded-pill ms-auto">{{ $pendingTokoCount }}</span> @endif</a></li>
            <li><a href="/admin/pengguna"><i class="bi bi-people"></i> Kelola Pengguna</a></li>
            <li><a href="/profile/edit"><i class="bi bi-person-circle"></i> Profil Akun</a></li>
        </ul>
        <div class="sidebar-footer"><form action="/logout" method="POST">@csrf<button type="submit" class="btn btn-outline-light w-100 rounded-pill"><i class="bi bi-box-arrow-right"></i> Logout</button></form></div>
    </div>
    <div class="main-content">
        <button class="mobile-toggle" onclick="toggleSidebar()"><i class="bi bi-list"></i> Menu</button>
        <h2 class="fw-bold mb-4"><i class="bi bi-shop"></i> Verifikasi Toko</h2>
        <div class="custom-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead><tr><th>Toko</th><th>Pemilik</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                    <tbody>
                        @foreach($tokos as $toko)
                        <tr id="row-toko-{{ $toko->id }}">
                            <td class="fw-semibold">{{ $toko->nama_toko }}<br><small class="text-muted">{{ $toko->alamat }}</small></td>
                            <td>{{ $toko->user->name }}<br><small class="text-muted">{{ $toko->user->email }}</small></td>
                            <td><span class="badge bg-warning text-dark status-badge" id="badge-{{ $toko->id }}">{{ ucfirst($toko->status_verifikasi) }}</span></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-success-custom rounded-pill btn-terima" data-id="{{ $toko->id }}"><i class="bi bi-check-lg"></i></button>
                                <button class="btn btn-sm btn-outline-danger rounded-pill btn-tolak" data-id="{{ $toko->id }}"><i class="bi bi-x-lg"></i></button>
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
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        document.querySelectorAll('.btn-terima').forEach(b => b.addEventListener('click', () => updateStatus(b.dataset.id, 'disetujui')));
        document.querySelectorAll('.btn-tolak').forEach(b => b.addEventListener('click', () => updateStatus(b.dataset.id, 'ditolak')));
        function updateStatus(id, status) {
            fetch(`/admin/verifikasi/${id}`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: JSON.stringify({ status }) })
            .then(r => r.json()).then(data => {
                if(data.success) { let b = document.getElementById(`badge-${id}`); b.innerText = data.status.charAt(0).toUpperCase() + data.status.slice(1); b.className = data.status === 'disetujui' ? 'badge bg-success status-badge' : 'badge bg-danger status-badge'; }
            });
        }
    </script>
</body>
</html>