<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = ['key', 'value'];

    // Fungsi bantu untuk mengambil nilai ongkir
    public static function getOngkir()
    {
        return self::where('key', 'ongkir_flat')->value('value') ?? 0;
    }
}