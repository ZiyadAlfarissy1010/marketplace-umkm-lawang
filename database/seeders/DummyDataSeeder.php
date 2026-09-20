<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Toko;
use App\Models\Produk;
use App\Models\User;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Penjual Dummy
        $penjual = User::create([
            'name' => 'UMKM Lawang Jaya',
            'email' => 'penjual@gmail.com',
            'password' => bcrypt('penjual123'),
            'role' => 'penjual',
            'alamat' => 'Nagari Lawang, Kec. Matur',
            'no_telp' => '081234567891',
        ]);

        // 2. Buat Kategori Dummy
        $kategori = Kategori::create(['nama_kategori' => 'Makanan Olahan']);

        // 3. Buat Toko Dummy (Status Disetujui)
        $toko = Toko::create([
            'user_id' => $penjual->id,
            'nama_toko' => 'Kuliner Lawang',
            'deskripsi' => 'Menyediakan makanan khas dan olahan Nagari Lawang.',
            'alamat' => 'Jl. Puncak Lawang, Nagari Lawang',
            'status_verifikasi' => 'disetujui',
        ]);

        // 4. Buat Produk Dummy
        Produk::create([
            'toko_id' => $toko->id,
            'kategori_id' => $kategori->id,
            'nama_produk' => 'Kopi Robusta Lawang',
            'deskripsi' => 'Kopi arabika asal pegunungan Lawang, dipanggang sempurna.',
            'harga' => 35000,
            'stok' => 50,
            'gambar' => 'https://via.placeholder.com/150',
        ]);

        Produk::create([
            'toko_id' => $toko->id,
            'kategori_id' => $kategori->id,
            'nama_produk' => 'Keripik Pisang',
            'deskripsi' => 'Keripik pisang renyah khas Lawang.',
            'harga' => 15000,
            'stok' => 100,
            'gambar' => 'https://via.placeholder.com/150',
        ]);
    }
}