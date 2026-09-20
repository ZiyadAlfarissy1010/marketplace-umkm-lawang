<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanans';

    protected $fillable = [
        'pesanan_id', 'produk_id', 'jumlah', 'harga_satuan', 'subtotal'
    ];

    // Relasi: DetailPesanan milik 1 Pesanan
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    // Relasi: DetailPesanan milik 1 Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}