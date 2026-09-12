<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_kategori_rkbus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('jenis_belanja_id');
            $table->string('kode_jenis_kategori_rkbu', 100);
            $table->string('nama_jenis_kategori_rkbu');
            $table->timestamps();
            $table->foreign('jenis_belanja_id')->references('id')->on('jenis_belanjas')->restrictOnDelete();
            $table->unique(['jenis_belanja_id', 'kode_jenis_kategori_rkbu'], 'jenis_belanja_id_kode_jenis_kategori_rkbu_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_kategori_rkbus');
    }
};
