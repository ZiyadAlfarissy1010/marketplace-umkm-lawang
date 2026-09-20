<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    protected $table = 'tokos';

    protected $fillable = [
        'user_id', 'nama_toko', 'logo', 'deskripsi', 'instagram', 'tiktok', 'facebook', 'nama_bank', 'no_rekening', 'atas_nama', 'qris_image', 'alamat', 'status_verifikasi'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produks()
    {
        return $this->hasMany(Produk::class);
    }
}