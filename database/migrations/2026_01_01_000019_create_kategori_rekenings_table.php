<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_rekenings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_kategori_rekening', 100)->unique();
            $table->string('nama_kategori_rekening');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_rekenings');
    }
};
