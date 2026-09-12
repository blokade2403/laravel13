<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekening_belanjas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('aktivitas_id');
            $table->uuid('sub_kategori_rekening_id')->nullable();
            $table->string('kode_rekening_belanja', 100)->unique();
            $table->string('nama_rekening_belanja');
            $table->timestamps();
            $table->foreign('aktivitas_id')->references('id')->on('aktivitas')->restrictOnDelete();
            $table->foreign('sub_kategori_rekening_id')->references('id')->on('sub_kategori_rekenings')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekening_belanjas');
    }
};
