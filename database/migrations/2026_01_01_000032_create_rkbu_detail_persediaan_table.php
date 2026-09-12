<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rkbu_detail_persediaan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('rkbu_detail_id')->unique();
            $table->unsignedInteger('stok')->nullable();
            $table->unsignedInteger('rata_rata_pemakaian')->nullable();
            $table->unsignedInteger('kebutuhan_per_bulan')->nullable();
            $table->unsignedInteger('buffer')->nullable();
            $table->unsignedInteger('pengadaan_sebelumnya')->nullable();
            $table->unsignedInteger('proyeksi_sisa_stok')->nullable();
            $table->unsignedInteger('kebutuhan_plus_buffer')->nullable();
            $table->unsignedInteger('kebutuhan_tahun_x1')->nullable();
            $table->unsignedInteger('rencana_pengadaan_tahun_x1')->nullable();
            $table->unsignedInteger('sisa_volume')->nullable();
            $table->decimal('sisa_anggaran', 18, 2)->nullable();
            $table->timestamps();
            $table->foreign('rkbu_detail_id')->references('id')->on('rkbu_details')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rkbu_detail_persediaan');
    }
};
