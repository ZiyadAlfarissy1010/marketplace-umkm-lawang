<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanans';

            protected $fillable = [
        'user_id', 'total_harga', 'status', 'metode_pembayaran', 'bukti_bayar', 'ekspedisi', 'ongkir', 'buyer_seen'
    ];

    // Relasi: Pesanan milik 1 User (Pembeli)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Pesanan punya banyak DetailPesanan
    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class);
    }
}