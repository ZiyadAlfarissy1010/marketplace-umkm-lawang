<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Marketplace UMKM Lawang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-dark: #1B4332;
            --primary-mid: #2D6A4F;
            --bg-light: #E0FBFC;
            --white: #FFFFFF;
        }
        body {
            margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--primary-dark); overflow: hidden; height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }
        .bubble { position: absolute; border-radius: 50%; background: rgba(224, 251, 252, 0.1); box-shadow: 0 0 10px rgba(224, 251, 252, 0.2); animation: floatUp 8s infinite linear; }
        .bubble:nth-child(1) { width: 80px; height: 80px; left: 10%; bottom: -80px; animation-duration: 9s; }
        .bubble:nth-child(2) { width: 40px; height: 40px; left: 30%; bottom: -40px; animation-duration: 6s; animation-delay: 1s; }
        .bubble:nth-child(3) { width: 120px; height: 120px; left: 50%; bottom: -120px; animation-duration: 12s; animation-delay: 2s; }
        .bubble:nth-child(4) { width: 60px; height: 60px; left: 70%; bottom: -60px; animation-duration: 7s; animation-delay: 0s; }
        .bubble:nth-child(5) { width: 90px; height: 90px; left: 85%; bottom: -90px; animation-duration: 10s; animation-delay: 3s; }
        .bubble:nth-child(6) { width: 50px; height: 50px; left: 20%; bottom: -50px; animation-duration: 8s; animation-delay: 4s; background: rgba(45, 106, 79, 0.4); }
        @keyframes floatUp { 0% { transform: translateY(0) scale(1); opacity: 0; } 50% { opacity: 0.8; } 100% { transform: translateY(-110vh) scale(0.5); opacity: 0; } }
        .login-card { background: var(--white); border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.3); padding: 2.5rem; width: 100%; max-width: 400px; position: relative; z-index: 10; animation: fadeInDown 0.8s ease-out; }
        @keyframes fadeInDown { 0% { transform: translateY(-50px); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }
        .login-card h3 { color: var(--primary-dark); font-weight: bold; }
        .form-control { border-radius: 10px; padding: 12px; border: 1px solid #ced4da; }
        .form-control:focus { border-color: var(--primary-mid); box-shadow: 0 0 0 0.2rem rgba(45, 106, 79, 0.25); }
        .input-group-text { cursor: pointer; background-color: #f8f9fa; }
        .btn-login { background-color: var(--primary-dark); color: var(--white); border-radius: 10px; padding: 12px; font-weight: bold; transition: 0.3s; border: none; }
        .btn-login:hover { background-color: var(--primary-mid); transform: translateY(-2px); }
        .alert-danger { border-radius: 10px; font-size: 0.9rem; animation: shake 0.5s; }
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
    <div class="bubble"></div><div class="bubble"></div><div class="bubble"></div><div class="bubble"></div><div class="bubble"></div><div class="bubble"></div>

    <div class="login-card">
        <div class="text-center mb-4">
            <i class="bi bi-shop fs-1" style="color: var(--primary-dark);"></i>
            <h3 class="mt-2 mb-0">UMKM Lawang</h3>
            <p class="text-muted">Masuk untuk melanjutkan belanja</p>
        </div>

        @if(session('error')) <div class="alert alert-danger text-center">{{ session('error') }}</div> @endif
        @if(session('success')) <div class="alert alert-success text-center">{{ session('success') }}</div> @endif

        <form action="{{ route('login.process') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope-fill text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0" placeholder="nama@email.com" required>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
                    <input type="password" name="password" id="passwordInput" class="form-control border-start-0 border-end-0" placeholder="******" required>
                    <span class="input-group-text bg-light border-start-0" id="togglePassword" onclick="togglePassword('passwordInput', 'togglePassword')">
                        <i class="bi bi-eye text-muted"></i>
                    </span>
                </div>
            </div>
            
            <button type="submit" class="btn btn-login w-100 mb-3">Masuk <i class="bi bi-box-arrow-in-right"></i></button>
        </form>
        
        <div class="text-center">
            <p class="mb-0 text-muted">Belum punya akun? <a href="{{ route('register') }}" class="text-success fw-bold text-decoration-none">Daftar di sini</a></p>
                    <div class="text-center mt-3">
            <a href="{{ route('password.request') }}" class="text-muted text-decoration-none" style="font-size: 0.9rem;">Lupa Password?</a>
        </div>
        </div>
    </div>

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
    </script>
</body>
</html>