<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'user_id', 'produk_id', 'pesanan_id', 'rating', 'komentar'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}