<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pembeli yang memberi ulasan
            $table->foreignId('produk_id')->constrained()->onDelete('cascade'); // Produk yang diulas
            $table->foreignId('pesanan_id')->constrained()->onDelete('cascade'); // Pesanan terkait
            $table->integer('rating'); // Nilai 1-5
            $table->text('komentar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};