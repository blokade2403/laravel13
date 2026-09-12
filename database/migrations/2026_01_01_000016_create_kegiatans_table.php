<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('program_id');
            $table->string('kode_kegiatan', 100);
            $table->string('nama_kegiatan');
            $table->timestamps();
            $table->foreign('program_id')->references('id')->on('programs')->restrictOnDelete();
            $table->unique(['program_id', 'kode_kegiatan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};
