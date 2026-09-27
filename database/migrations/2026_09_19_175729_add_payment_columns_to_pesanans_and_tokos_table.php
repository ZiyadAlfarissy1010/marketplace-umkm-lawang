<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom ke tabel pesanans
        Schema::table('pesanans', function (Blueprint $table) {
            $table->enum('metode_pembayaran', ['whatsapp', 'transfer', 'qris'])->default('whatsapp')->after('status');
            $table->string('bukti_bayar')->nullable()->after('metode_pembayaran');
        });

        // Update enum status untuk menambahkan status pembayaran
        DB::statement("ALTER TABLE pesanans MODIFY COLUMN status ENUM('keranjang', 'checkout', 'pending_payment', 'dibayar', 'diproses', 'dikirim', 'selesai', 'dibatalkan') DEFAULT 'keranjang'");

        // 2. Tambah kolom rekening ke tabel tokos
        Schema::table('tokos', function (Blueprint $table) {
            $table->string('nama_bank')->nullable()->after('facebook');
            $table->string('no_rekening')->nullable()->after('nama_bank');
            $table->string('atas_nama')->nullable()->after('no_rekening');
        });
    }

    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn(['metode_pembayaran', 'bukti_bayar']);
        });
        DB::statement("ALTER TABLE pesanans MODIFY COLUMN status ENUM('keranjang', 'checkout', 'diproses', 'dikirim', 'selesai', 'dibatalkan') DEFAULT 'keranjang'");
        
        Schema::table('tokos', function (Blueprint $table) {
            $table->dropColumn(['nama_bank', 'no_rekening', 'atas_nama']);
        });
    }
};