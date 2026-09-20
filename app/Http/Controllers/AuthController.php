<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;
use App\Models\Toko;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif (Auth::user()->role == 'penjual') {
                return redirect()->route('penjual.dashboard');
            } else {
                return redirect()->route('beranda');
            }
        }

        return back()->with('error', 'Email atau Password salah!');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:pembeli,penjual',
            'alamat' => 'required|string',
            'no_telp' => 'required|string',
            'nama_toko' => 'required_if:role,penjual|string|nullable',
        ]);

        $otp = rand(100000, 999999);

        session([
            'register_data' => $request->all(),
            'register_otp' => $otp
        ]);

        return redirect()->route('otp.verify')->with('success', 'Kode OTP telah dikirim ke nomor ' . $request->no_telp . '. (Simulasi OTP: ' . $otp . ')');
    }

    public function showOtp()
    {
        if (!session('register_data')) {
            return redirect()->route('register');
        }
        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        if ($request->otp != session('register_otp')) {
            return back()->with('error', 'Kode OTP salah! Silakan coba lagi.');
        }

        $data = session('register_data');
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['role'],
            'alamat' => $data['alamat'],
            'no_telp' => $data['no_telp'],
            'is_verified' => true,
            'otp_code' => $request->otp
        ]);

        if ($data['role'] == 'penjual') {
            Toko::create([
                'user_id' => $user->id,
                'nama_toko' => $data['nama_toko'],
                'deskripsi' => 'Deskripsi toko belum diisi. Silakan edit profil toko Anda.',
                'alamat' => $data['alamat'],
                'status_verifikasi' => 'pending',
            ]);
        }

        session()->forget(['register_data', 'register_otp']);

        return redirect()->route('login')->with('success', 'Nomor berhasil diverifikasi! Silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // ==========================================
    // FITUR LUPA PASSWORD
    // ==========================================

    public function showForgotForm()
    {
        return view('auth.forgot');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
            ? back()->with('success', 'Link reset password telah dikirim! Karena simulasi, link ada di file storage/logs/laravel.log')
            : back()->with('error', 'Email tidak ditemukan dalam sistem kami.');
    }

    public function showResetForm($token)
    {
        return view('auth.reset', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login dengan password baru.')
            : back()->with('error', 'Gagal mereset password. Token mungkin sudah kedaluwarsa.');
    }
}