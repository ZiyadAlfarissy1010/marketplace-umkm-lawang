<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - UMKM Lawang</title>
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
        .profile-card { background: var(--white); border-radius: 20px; box-shadow: 0 10px 30px rgba(27, 67, 50, 0.1); padding: 2.5rem; animation: fadeIn 0.8s ease-out; }
        @keyframes fadeIn { 0% { transform: translateY(20px); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }
        .form-control { border-radius: 10px; padding: 12px; border: 1px solid #ced4da; }
        .form-control:focus { border-color: var(--primary-mid); box-shadow: 0 0 0 0.2rem rgba(45, 106, 79, 0.15); }
        .input-group-text { cursor: pointer; background-color: #f8f9fa; }
        .footer-custom { background-color: var(--primary-dark); color: var(--white); padding: 1.5rem 0; margin-top: 4rem; flex-shrink: 0; }
        
        /* Style Preview Foto Profil */
        .foto-preview {
            width: 150px; height: 150px; border-radius: 50%; object-fit: cover;
            background-color: var(--bg-light); border: 3px solid var(--primary-dark);
        }
    </style>
</head>
<body>

    @php
        $dashboardRoute = '/';
        if(auth()->user()->role == 'admin') $dashboardRoute = '/admin/dashboard';
        elseif(auth()->user()->role == 'penjual') $dashboardRoute = '/penjual/dashboard';
        elseif(auth()->user()->role == 'pembeli') $dashboardRoute = '/pembeli/dashboard';
    @endphp

    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/"><i class="bi bi-shop"></i> UMKM Lawang</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item ms-2"><a class="nav-link p-0" href="/profile/edit" title="Edit Profil"><img src="{{ auth()->user()->foto ? asset('storage/'.auth()->user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=1B4332&color=fff' }}" class="rounded-circle" width="35" height="35" style="object-fit: cover; border: 2px solid #FFFFFF;" alt="Foto Profil"></a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ $dashboardRoute }}">Kembali ke Dashboard</a></li>
                    <li class="nav-item"><form action="/logout" method="POST">@csrf<button class="btn btn-outline-light rounded-pill px-3">Logout</button></form></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="content-wrapper">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7">
                    
                    @if(session('success'))
                        <div class="alert alert-success rounded-pill">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="profile-card">
                        <div class="text-center mb-4">
                            <i class="bi bi-person-circle fs-1" style="color: var(--primary-dark);"></i>
                            <h3 class="mt-2 mb-0">Edit Profil Akun</h3>
                            <p class="text-muted">Perbarui data pribadi dan keamanan akun Anda</p>
                        </div>

                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Bagian Upload Foto Profil -->
                            <div class="d-flex flex-column align-items-center mb-4 pb-4 border-bottom">
                                <img id="fotoPreview" src="{{ $user->foto ? asset('storage/'.$user->foto) : 'https://via.placeholder.com/150?text=Foto+Profil' }}" class="foto-preview mb-3" alt="Foto Profil">
                                <label for="fotoInput" class="btn btn-outline-secondary rounded-pill px-4 cursor-pointer">
                                    <i class="bi bi-camera"></i> Ganti Foto Profil
                                </label>
                                <input type="file" name="foto" id="fotoInput" class="d-none" accept="image/*" onchange="previewFoto(this)">
                                <small class="text-muted mt-2">Format: JPG, PNG. Maks 2MB.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">No. Telepon</label>
                                    <input type="text" name="no_telp" class="form-control" value="{{ $user->no_telp }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Role Akun</label>
                                    <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled style="background-color: #f8f9fa;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2" required>{{ $user->alamat }}</textarea>
                            </div>

                            <hr class="my-4">
                            <h6 class="text-muted mb-3">Ubah Password (Kosongkan jika tidak ingin ganti)</h6>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                                    <input type="password" name="password" id="passwordInput" class="form-control border-start-0 border-end-0" placeholder="Min. 6 karakter">
                                    <span class="input-group-text bg-light border-start-0" id="togglePassword" onclick="togglePassword('passwordInput', 'togglePassword')">
                                        <i class="bi bi-eye text-muted"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                                    <input type="password" name="password_confirmation" id="confirmPasswordInput" class="form-control border-start-0 border-end-0" placeholder="Ulangi password baru">
                                    <span class="input-group-text bg-light border-start-0" id="toggleConfirmPassword" onclick="togglePassword('confirmPasswordInput', 'toggleConfirmPassword')">
                                        <i class="bi bi-eye text-muted"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between flex-wrap gap-2">
                                <a href="{{ $dashboardRoute }}" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
                                <button type="submit" class="btn btn-primary-custom rounded-pill px-4">
                                    <i class="bi bi-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer-custom text-center">
        <div class="container">
            <small class="text-white-50">&copy; 2026 Marketplace UMKM Lawang.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function togglePassword(inputId, toggleId) {
            const input = document.getElementById(inputId);
            const toggle = document.getElementById(toggleId);
            const icon = toggle.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        // Script untuk Preview Foto sebelum disimpan
        function previewFoto(input) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('fotoPreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    </script>
</body>
</html>