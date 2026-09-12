<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tanggal_perencanaans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tahun_anggaran_id');
            $table->uuid('fase_id');
            $table->date('tanggal');
            $table->string('no_dpa', 100)->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('status', 20)->default('aktif');
            $table->timestamps();
            $table->foreign('tahun_anggaran_id')->references('id')->on('tahun_anggarans')->restrictOnDelete();
            $table->foreign('fase_id')->references('id')->on('fases')->restrictOnDelete();
            $table->index(['tahun_anggaran_id', 'fase_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tanggal_perencanaans');
    }
};
