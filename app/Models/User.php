<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     * Ditambahkan 'otp_code' dan 'phone_verified_at' untuk fitur OTP.
     */
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role', 
        'alamat', 
        'no_telp', 
        'foto',
        'otp_code',           // Kolom untuk menyimpan kode OTP
        'phone_verified_at',   // Kolom untuk mencatat waktu verifikasi berhasil
    ];

    /**
     * Atribut yang harus disembunyikan saat serialisasi.
     */
    protected $hidden = [
        'password', 
        'remember_token',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'phone_verified_at' => 'datetime', // Ditambahkan agar tanggal verifikasi terformat dengan baik
        ];
    }

    // Relasi: 1 User (Penjual) punya 1 Toko
    public function toko()
    {
        return $this->hasOne(Toko::class);
    }
}