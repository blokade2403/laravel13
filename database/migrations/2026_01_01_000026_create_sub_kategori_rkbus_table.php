<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_kategori_rkbus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('kategori_rkbu_id');
            $table->uuid('sub_kategori_rekening_id')->nullable();
            $table->uuid('rekening_belanja_id')->nullable();
            $table->string('kode_sub_kategori_rkbu', 100);
            $table->string('nama_sub_kategori_rkbu');
            $table->string('status', 20)->default('aktif');
            $table->timestamps();
            $table->foreign('kategori_rkbu_id')->references('id')->on('kategori_rkbus')->restrictOnDelete();
            $table->foreign('sub_kategori_rekening_id')->references('id')->on('sub_kategori_rekenings')->nullOnDelete();
            $table->foreign('rekening_belanja_id')->references('id')->on('rekening_belanjas')->nullOnDelete();
            $table->unique(['kategori_rkbu_id', 'kode_sub_kategori_rkbu'], 'kategori_rkbu_id_kode_sub_kategori_rkbu_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_kategori_rkbus');
    }
};
