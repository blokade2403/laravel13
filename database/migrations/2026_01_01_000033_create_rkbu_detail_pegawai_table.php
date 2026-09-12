<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rkbu_detail_pegawai', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('rkbu_detail_id')->unique();
            $table->string('nama_pegawai')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('status_kawin')->nullable();
            $table->string('nomor_kontrak')->nullable();
            $table->date('tmt_pegawai')->nullable();
            $table->decimal('gaji_pokok', 18, 2)->nullable();
            $table->decimal('remunerasi', 18, 2)->nullable();
            $table->decimal('koefisien_remunerasi', 10, 2)->nullable();
            $table->decimal('koefisien_gaji', 10, 2)->nullable();
            $table->decimal('bpjs_kesehatan', 18, 2)->nullable();
            $table->decimal('bpjs_tk', 18, 2)->nullable();
            $table->decimal('bpjs_jht', 18, 2)->nullable();
            $table->decimal('total_gaji_pokok', 18, 2)->nullable();
            $table->decimal('total_remunerasi', 18, 2)->nullable();
            $table->unsignedInteger('sisa_volume')->nullable();
            $table->decimal('sisa_anggaran', 18, 2)->nullable();
            $table->timestamps();
            $table->foreign('rkbu_detail_id')->references('id')->on('rkbu_details')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rkbu_detail_pegawai');
    }
};
