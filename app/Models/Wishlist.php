<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlists';

    protected $fillable = ['user_id', 'produk_id'];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}