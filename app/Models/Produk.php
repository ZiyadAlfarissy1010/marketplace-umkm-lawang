<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produks';

    protected $fillable = [
        'toko_id', 'kategori_id', 'nama_produk', 'deskripsi', 'harga', 'stok', 'gambar'
    ];

    // Relasi: Produk milik 1 Toko
    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    // Relasi: Produk milik 1 Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}