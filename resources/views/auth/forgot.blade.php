<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - UMKM Lawang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root { --primary-dark: #1B4332; --primary-mid: #2D6A4F; --bg-light: #E0FBFC; --white: #FFFFFF; }
        body { margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; background-color: var(--primary-dark); overflow: hidden; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .bubble { position: absolute; border-radius: 50%; background: rgba(224, 251, 252, 0.1); box-shadow: 0 0 10px rgba(224, 251, 252, 0.2); animation: floatUp 8s infinite linear; }
        .bubble:nth-child(1) { width: 80px; height: 80px; left: 10%; bottom: -80px; animation-duration: 9s; }
        .bubble:nth-child(2) { width: 40px; height: 40px; left: 30%; bottom: -40px; animation-duration: 6s; animation-delay: 1s; }
        @keyframes floatUp { 0% { transform: translateY(0) scale(1); opacity: 0; } 50% { opacity: 0.8; } 100% { transform: translateY(-110vh) scale(0.5); opacity: 0; } }
        .login-card { background: var(--white); border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.3); padding: 2.5rem; width: 100%; max-width: 400px; position: relative; z-index: 10; animation: fadeInDown 0.8s ease-out; }
        @keyframes fadeInDown { 0% { transform: translateY(-50px); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }
        .login-card h3 { color: var(--primary-dark); font-weight: bold; }
        .form-control { border-radius: 10px; padding: 12px; border: 1px solid #ced4da; }
        .form-control:focus { border-color: var(--primary-mid); box-shadow: 0 0 0 0.2rem rgba(45, 106, 79, 0.25); }
        .btn-login { background-color: var(--primary-dark); color: var(--white); border-radius: 10px; padding: 12px; font-weight: bold; transition: 0.3s; border: none; }
        .btn-login:hover { background-color: var(--primary-mid); transform: translateY(-2px); }
        .alert { border-radius: 10px; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="bubble"></div><div class="bubble"></div>

    <div class="login-card">
        <div class="text-center mb-4">
            <i class="bi bi-key fs-1" style="color: var(--primary-dark);"></i>
            <h3 class="mt-2 mb-0">Lupa Password</h3>
            <p class="text-muted">Masukkan email Anda. Kami akan mengirim link reset password.</p>
        </div>

        @if(session('success')) <div class="alert alert-success text-center">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger text-center">{{ session('error') }}</div> @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="form-label fw-semibold">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope-fill text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0" placeholder="nama@email.com" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-login w-100 mb-3">Kirim Link Reset <i class="bi bi-send"></i></button>
        </form>
        
        <div class="text-center">
            <a href="{{ route('login') }}" class="text-muted text-decoration-none" style="font-size: 0.9rem;">Kembali ke Halaman Login</a>
        </div>
    </div>
</body>
</html>