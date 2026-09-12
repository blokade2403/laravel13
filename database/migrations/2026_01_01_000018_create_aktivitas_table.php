<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aktivitas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('program_id')->nullable();
            $table->uuid('sub_kegiatan_id');
            $table->string('kode_aktivitas', 100);
            $table->string('nama_aktivitas');
            $table->timestamps();
            $table->foreign('program_id')->references('id')->on('programs')->nullOnDelete();
            $table->foreign('sub_kegiatan_id')->references('id')->on('sub_kegiatans')->restrictOnDelete();
            $table->unique(['sub_kegiatan_id', 'kode_aktivitas']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aktivitas');
    }
};
