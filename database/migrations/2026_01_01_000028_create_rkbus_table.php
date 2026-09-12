<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rkbus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nomor_rkbu', 100);
            $table->uuid('tahun_anggaran_id');
            $table->uuid('unit_id');
            $table->uuid('sumber_dana_id')->nullable();
            $table->uuid('jenis_belanja_id')->nullable();
            $table->uuid('jenis_kategori_rkbu_id')->nullable();
            $table->uuid('sub_kategori_rkbu_id')->nullable();
            $table->uuid('sub_kegiatan_id')->nullable();
            $table->uuid('created_by');
            $table->string('status', 30)->default('draft')->index();
            $table->unsignedInteger('version')->default(1);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->foreign('tahun_anggaran_id')->references('id')->on('tahun_anggarans')->restrictOnDelete();
            $table->foreign('unit_id')->references('id')->on('units')->restrictOnDelete();
            $table->foreign('sumber_dana_id')->references('id')->on('sumber_danas')->nullOnDelete();
            $table->foreign('jenis_belanja_id')->references('id')->on('jenis_belanjas')->nullOnDelete();
            $table->foreign('jenis_kategori_rkbu_id')->references('id')->on('jenis_kategori_rkbus')->nullOnDelete();
            $table->foreign('sub_kategori_rkbu_id')->references('id')->on('sub_kategori_rkbus')->nullOnDelete();
            $table->foreign('sub_kegiatan_id')->references('id')->on('sub_kegiatans')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->restrictOnDelete();
            $table->unique(['tahun_anggaran_id', 'nomor_rkbu']);
            $table->index(['unit_id', 'tahun_anggaran_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rkbus');
    }
};
