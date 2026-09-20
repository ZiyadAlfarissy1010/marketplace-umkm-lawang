<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Toko - UMKM Lawang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        
        :root { --primary-dark: #1B4332; --primary-mid: #2D6A4F; --bg-light: #E0FBFC; --text-dark: #1B2A2E; --white: #FFFFFF; }
        
        html { overflow-y: scroll !important; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: var(--primary-dark); border-radius: 10px; }

        body { background-color: var(--bg-light); font-family: 'Poppins', 'Segoe UI', sans-serif; color: var(--text-dark); min-height: 100vh; animation: fadeInBody 0.4s ease-in-out; }
        @keyframes fadeInBody { from { opacity: 0; } to { opacity: 1; } }

        .btn:active { transform: translateY(2px); box-shadow: none !important; }

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
        .logo-preview { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; background-color: var(--bg-light); border: 3px solid var(--primary-dark); }
        
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
            <!-- TAMBAHAN BADGE NOTIFIKASI -->
            <li><a href="/penjual/pesanan"><i class="bi bi-bag-check"></i> Pesanan Masuk @if(isset($newOrdersCount) && $newOrdersCount > 0) <span class="badge bg-danger rounded-pill ms-auto">{{ $newOrdersCount }}</span> @endif</a></li>
            <li><a href="/penjual/laporan"><i class="bi bi-graph-up"></i> Laporan Penjualan</a></li>
            <li><a href="/penjual/profil" class="active"><i class="bi bi-shop-window"></i> Profil Toko</a></li>
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
        <button class="mobile-toggle" onclick="toggleSidebar()"><i class="bi bi-list"></i> Menu</button>
        <h2 class="fw-bold mb-4"><i class="bi bi-shop-window"></i> Pengaturan Profil Toko</h2>

        @if(session('success'))<div class="alert alert-success rounded-pill">{{ session('success') }}</div>@endif

        <div class="custom-card">
            <form action="/penjual/profil" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="d-flex flex-column align-items-center mb-4 pb-4 border-bottom">
                    <img id="logoPreview" src="{{ $toko->logo ? asset('storage/'.$toko->logo) : 'https://via.placeholder.com/150?text=Logo+Toko' }}" class="logo-preview mb-3" alt="Logo Toko">
                    <label for="logoInput" class="btn btn-outline-secondary rounded-pill px-4 cursor-pointer">
                        <i class="bi bi-camera"></i> Ganti Logo Toko
                    </label>
                    <input type="file" name="logo" id="logoInput" class="d-none" accept="image/*" onchange="previewLogo(this)">
                    <small class="text-muted mt-2">Format: JPG, PNG. Maks 2MB.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Toko</label>
                    <input type="text" name="nama_toko" class="form-control" value="{{ $toko->nama_toko }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat</label>
                    <input type="text" name="alamat" class="form-control" value="{{ $toko->alamat }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4" placeholder="Deskripsi toko belum diisi. Silakan edit profil toko Anda." required>{{ $toko->deskripsi == 'Deskripsi toko belum diisi. Silakan edit profil toko Anda.' ? '' : $toko->deskripsi }}</textarea>
                </div>
                
                <hr class="my-4">
                <h6 class="text-muted mb-3">Informasi Pembayaran (Transfer Bank)</h6>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Nama Bank</label>
                        <input type="text" name="nama_bank" class="form-control" value="{{ $toko->nama_bank }}" placeholder="Contoh: BRI / BCA / Mandiri">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">No. Rekening</label>
                        <input type="text" name="no_rekening" class="form-control" value="{{ $toko->no_rekening }}" placeholder="Contoh: 1234567890">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Atas Nama</label>
                        <input type="text" name="atas_nama" class="form-control" value="{{ $toko->atas_nama }}" placeholder="Contoh: Ziyad Alfarissy">
                    </div>
                </div>

                <!-- TAMBAHAN FORM UPLOAD QRIS -->
                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="bi bi-qr-code"></i> Upload Gambar QRIS (Opsional)</label>
                    <input type="file" name="qris_image" class="form-control" accept="image/*">
                    @if($toko->qris_image)
                        <img src="{{ asset('storage/'.$toko->qris_image) }}" class="mt-2" width="150" style="border-radius: 10px; border: 1px solid #ccc;">
                    @endif
                </div>

                <hr class="my-4">
                <h6 class="text-muted mb-3">Tautan Sosial Media (Opsional)</h6>
                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="bi bi-instagram text-danger"></i> Instagram URL</label>
                    <input type="url" name="instagram" class="form-control" value="{{ $toko->instagram }}" placeholder="https://instagram.com/namatoko">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="bi bi-tiktok text-dark"></i> TikTok URL</label>
                    <input type="url" name="tiktok" class="form-control" value="{{ $toko->tiktok }}" placeholder="https://tiktok.com/@namatoko">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="bi bi-facebook text-primary"></i> Facebook URL</label>
                    <input type="url" name="facebook" class="form-control" value="{{ $toko->facebook }}" placeholder="https://facebook.com/namatoko">
                </div>
                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success-custom rounded-pill px-4"><i class="bi bi-save"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('active'); document.getElementById('overlay').classList.toggle('active'); }
        function previewLogo(input) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logoPreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    </script>
</body>
</html>