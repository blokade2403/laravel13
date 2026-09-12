<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_kategori_rekenings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('kategori_rekening_id');
            $table->string('kode_sub_kategori_rekening', 100);
            $table->string('nama_sub_kategori_rekening');
            $table->timestamps();
            $table->foreign('kategori_rekening_id')->references('id')->on('kategori_rekenings')->restrictOnDelete();
            $table->unique(['kategori_rekening_id', 'kode_sub_kategori_rekening'], 'kat_rek_kode_sub_kat_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_kategori_rekenings');
    }
};
