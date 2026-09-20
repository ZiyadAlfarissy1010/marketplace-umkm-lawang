<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Marketplace UMKM Lawang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root { --primary-dark: #1B4332; --primary-mid: #2D6A4F; --bg-light: #E0FBFC; --text-dark: #1B2A2E; --white: #FFFFFF; }
        body { background-color: var(--bg-light); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: var(--text-dark); min-height: 100vh; display: flex; flex-direction: column; }
        .navbar-custom { background-color: var(--primary-dark); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .navbar-custom .navbar-brand { color: var(--white) !important; }
        .content-wrapper { flex: 1 0 auto; }
        .register-card { background: var(--white); border-radius: 20px; box-shadow: 0 10px 30px rgba(27, 67, 50, 0.1); padding: 2.5rem; animation: fadeIn 0.8s ease-out; }
        @keyframes fadeIn { 0% { transform: translateY(20px); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }
        .register-card h3 { color: var(--primary-dark); font-weight: bold; }
        .form-control, .form-select { border-radius: 10px; padding: 12px; border: 1px solid #ced4da; transition: 0.3s; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-mid); box-shadow: 0 0 0 0.2rem rgba(45, 106, 79, 0.15); }
        .input-group-text { border-radius: 10px 0 0 10px; background-color: #f8f9fa; cursor: pointer; }
        .form-select { border-radius: 10px; cursor: pointer; }
        .btn-register { background-color: var(--primary-dark); color: var(--white); border-radius: 10px; padding: 14px; font-weight: bold; transition: 0.3s; border: none; }
        .btn-register:hover { background-color: var(--primary-mid); transform: translateY(-2px); color: var(--white); }
        .footer-custom { background-color: var(--primary-dark); color: var(--white); padding: 1.5rem 0; margin-top: 4rem; flex-shrink: 0; }
        .alert { border-radius: 10px; animation: shake 0.5s; }
        @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    body {
    animation: fadeInBody 0.4s ease-in-out;
}
@keyframes fadeInBody {
    from { opacity: 0; }
    to { opacity: 1; }
}
   /* Efek glow saat input difokuskan */
.form-control:focus, .form-select:focus {
    border-color: var(--primary-mid);
    box-shadow: 0 0 0 0.25rem rgba(27, 67, 50, 0.15); /* Hijau gelap transparan */
}
   </style>
</head>
<body>

    <nav class="navbar navbar-custom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/"><i class="bi bi-shop"></i> UMKM Lawang</a>
        </div>
    </nav>

    <div class="content-wrapper">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7">
                    
                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="register-card">
                        <div class="text-center mb-4">
                            <i class="bi bi-person-plus-fill fs-1" style="color: var(--primary-dark);"></i>
                            <h3 class="mt-2 mb-0">Buat Akun Baru</h3>
                            <p class="text-muted">Gabung untuk mendukung UMKM Nagari Lawang</p>
                        </div>

                        <form action="{{ route('register.process') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Nama Lengkap</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-end-0"><i class="bi bi-person text-muted"></i></span>
                                        <input type="text" name="name" class="form-control border-start-0" placeholder="Nama Anda" value="{{ old('name') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                        <input type="email" name="email" class="form-control border-start-0" placeholder="nama@email.com" value="{{ old('email') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-end-0"><i class="bi bi-lock text-muted"></i></span>
                                        <input type="password" name="password" id="passwordInput" class="form-control border-start-0 border-end-0" placeholder="Min. 6 karakter" required>
                                        <span class="input-group-text border-start-0" id="togglePassword" onclick="togglePassword('passwordInput', 'togglePassword')">
                                            <i class="bi bi-eye text-muted"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Daftar Sebagai</label>
                                    <select name="role" id="roleSelect" class="form-select" required>
                                        <option value="pembeli" {{ old('role') == 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                                        <option value="penjual" {{ old('role') == 'penjual' ? 'selected' : '' }}>Penjual (UMKM)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3" id="namaTokoField" style="display: none;">
                                <label class="form-label fw-semibold">Nama Toko / UMKM</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0"><i class="bi bi-shop text-muted"></i></span>
                                    <input type="text" name="nama_toko" id="namaTokoInput" class="form-control border-start-0" placeholder="Contoh: Kuliner Lawang" value="{{ old('nama_toko') }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2" placeholder="Contoh: Jl. Puncak Lawang, Nagari Lawang" required>{{ old('alamat') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">No. Telepon</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0"><i class="bi bi-telephone text-muted"></i></span>
                                    <input type="text" name="no_telp" class="form-control border-start-0" placeholder="0812xxxxxxx" value="{{ old('no_telp') }}" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-register w-100 mb-3">
                                Daftar Sekarang <i class="bi bi-arrow-right-circle"></i>
                            </button>
                        </form>
                        
                        <div class="text-center">
                            <p class="mb-0 text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color: var(--primary-mid);">Login di sini</a></p>
                        </div>
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

    <script>
        const roleSelect = document.getElementById('roleSelect');
        const namaTokoField = document.getElementById('namaTokoField');
        const namaTokoInput = document.getElementById('namaTokoInput');

        function toggleTokoField() {
            if (roleSelect.value === 'penjual') {
                namaTokoField.style.display = 'block';
                namaTokoInput.setAttribute('required', 'required');
            } else {
                namaTokoField.style.display = 'none';
                namaTokoInput.removeAttribute('required');
            }
        }
        toggleTokoField();
        roleSelect.addEventListener('change', toggleTokoField);

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
    </script>
</body>
</html>