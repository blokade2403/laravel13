<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_rkbus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('obyek_belanja_id')->nullable();
            $table->uuid('jenis_kategori_rkbu_id');
            $table->string('kode_kategori_rkbu', 100);
            $table->string('nama_kategori_rkbu');
            $table->timestamps();
            $table->foreign('obyek_belanja_id')->references('id')->on('obyek_belanjas')->nullOnDelete();
            $table->foreign('jenis_kategori_rkbu_id')->references('id')->on('jenis_kategori_rkbus')->restrictOnDelete();
            $table->unique(['jenis_kategori_rkbu_id', 'kode_kategori_rkbu']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_rkbus');
    }
};
