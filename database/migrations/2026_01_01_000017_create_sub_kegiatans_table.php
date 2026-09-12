<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_kegiatans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('kegiatan_id');
            $table->uuid('sumber_dana_id')->nullable();
            $table->string('kode_sub_kegiatan', 100);
            $table->string('nama_sub_kegiatan');
            $table->string('tujuan_sub_kegiatan')->nullable();
            $table->string('indikator_sub_kegiatan')->nullable();
            $table->timestamps();
            $table->foreign('kegiatan_id')->references('id')->on('kegiatans')->restrictOnDelete();
            $table->foreign('sumber_dana_id')->references('id')->on('sumber_danas')->nullOnDelete();
            $table->unique(['kegiatan_id', 'kode_sub_kegiatan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_kegiatans');
    }
};
