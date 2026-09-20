<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Kategori - UMKM Lawang</title>
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
        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 1050; }
        .custom-toast { background-color: var(--white); color: var(--text-dark); border-left: 5px solid var(--primary-dark); border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
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
    <div class="toast-container"><div id="kategoriToast" class="toast custom-toast" role="alert" data-bs-delay="1500"><div class="toast-body"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Berhasil!</strong> <span id="toastText">Kategori ditambahkan.</span></div></div></div>
    <div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header"><i class="bi bi-shield-lock fs-2"></i><h5 class="mt-2">Admin Panel</h5></div>
        <ul class="sidebar-menu">
            <li><a href="/admin/dashboard"><i class="bi bi-grid"></i> Dashboard</a></li>
            <li><a href="/admin/kategori" class="active"><i class="bi bi-tags"></i> Kelola Kategori</a></li>
            <li><a href="/admin/verifikasi"><i class="bi bi-shop"></i> Verifikasi Toko @if(isset($pendingTokoCount) && $pendingTokoCount > 0) <span class="badge bg-danger rounded-pill ms-auto">{{ $pendingTokoCount }}</span> @endif</a></li>
            <li><a href="/admin/pengguna"><i class="bi bi-people"></i> Kelola Pengguna</a></li>
            <li><a href="/profile/edit"><i class="bi bi-person-circle"></i> Profil Akun</a></li>
        </ul>
        <div class="sidebar-footer"><form action="/logout" method="POST">@csrf<button type="submit" class="btn btn-outline-light w-100 rounded-pill"><i class="bi bi-box-arrow-right"></i> Logout</button></form></div>
    </div>
    <div class="main-content">
        <button class="mobile-toggle" onclick="toggleSidebar()"><i class="bi bi-list"></i> Menu</button>
        <h2 class="fw-bold mb-4"><i class="bi bi-tags"></i> Kelola Kategori</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="custom-card">
                    <h5 class="mb-3">Tambah Kategori</h5>
                    <form id="formKategori">@csrf<div class="mb-3"><input type="text" name="nama_kategori" id="nama_kategori" class="form-control" placeholder="Nama Kategori" required></div><button type="submit" class="btn btn-success-custom w-100 rounded-pill">Tambah</button></form>
                </div>
            </div>
            <div class="col-md-8">
                <div class="custom-card">
                    <div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>#</th><th>Nama</th><th class="text-end">Aksi</th></tr></thead><tbody id="tableKategori">@foreach($kategoris as $index => $kategori)<tr id="row-{{ $kategori->id }}"><td>{{ $index + 1 }}</td><td class="fw-semibold">{{ $kategori->nama_kategori }}</td><td class="text-end"><form action="/admin/kategori/{{ $kategori->id }}" method="POST" onsubmit="return confirm('Yakin hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger rounded-pill"><i class="bi bi-trash"></i></button></form></td></tr>@endforeach</tbody></table></div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('active'); document.getElementById('overlay').classList.toggle('active'); }
        document.getElementById('formKategori').addEventListener('submit', function(e) {
            e.preventDefault(); const namaKategori = document.getElementById('nama_kategori').value; const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            fetch('/admin/kategori', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: JSON.stringify({ nama_kategori: namaKategori }) })
            .then(response => response.json()).then(data => {
                if(data.success) {
                    let tableBody = document.getElementById('tableKategori'); let rowCount = tableBody.rows.length + 1;
                    let newRow = `<tr id="row-${data.kategori.id}"><td>${rowCount}</td><td class="fw-semibold">${data.kategori.nama_kategori}</td><td class="text-end"><form action="/admin/kategori/${data.kategori.id}" method="POST" onsubmit="return confirm('Yakin hapus?')"><input type="hidden" name="_token" value="${csrfToken}"><input type="hidden" name="_method" value="DELETE"><button class="btn btn-sm btn-outline-danger rounded-pill"><i class="bi bi-trash"></i></button></form></td></tr>`;
                    tableBody.insertAdjacentHTML('beforeend', newRow); document.getElementById('nama_kategori').value = '';
                    new bootstrap.Toast(document.getElementById('kategoriToast')).show();
                } else { alert('Gagal: ' + data.message); }
            }).catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>