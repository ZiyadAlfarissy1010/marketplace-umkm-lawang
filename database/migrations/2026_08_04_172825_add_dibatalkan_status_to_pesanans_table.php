<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah enum untuk menambahkan status 'dibatalkan'
        DB::statement("ALTER TABLE pesanans MODIFY COLUMN status ENUM('keranjang', 'checkout', 'diproses', 'dikirim', 'selesai', 'dibatalkan') DEFAULT 'keranjang'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pesanans MODIFY COLUMN status ENUM('keranjang', 'checkout', 'diproses', 'dikirim', 'selesai') DEFAULT 'keranjang'");
    }
};