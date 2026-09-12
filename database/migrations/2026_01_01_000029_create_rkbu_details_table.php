<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rkbu_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('rkbu_id');
            $table->uuid('komponen_id')->nullable();
            $table->string('jenis_detail', 30); // BARJAS/MODAL/PERSEDIAAN/PEGAWAI
            $table->string('nama');
            $table->text('spesifikasi')->nullable();
            $table->decimal('volume', 18, 2)->default(0);
            $table->string('satuan', 100)->nullable();
            $table->decimal('harga_satuan', 18, 2)->default(0);
            $table->decimal('ppn', 5, 2)->default(0);
            $table->decimal('total_anggaran', 18, 2)->default(0);
            $table->string('status', 30)->default('draft')->index();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->foreign('rkbu_id')->references('id')->on('rkbus')->cascadeOnDelete();
            $table->foreign('komponen_id')->references('id')->on('komponens')->nullOnDelete();
            $table->index(['rkbu_id', 'jenis_detail']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rkbu_details');
    }
};
