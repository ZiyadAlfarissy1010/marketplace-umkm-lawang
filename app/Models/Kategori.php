<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategoris';

    // TAMBAHKAN BARIS INI AGAR BISA DISIMPAN KE DATABASE
    protected $fillable = ['nama_kategori'];

    // Relasi: Kategori punya banyak Produk
    public function produks()
    {
        return $this->hasMany(Produk::class);
    }
}