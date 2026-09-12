<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procurement_packages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_paket', 100)->unique();
            $table->uuid('tahun_anggaran_id');
            $table->uuid('unit_id');
            $table->uuid('procurement_method_id')->nullable();
            $table->string('nama_paket');
            $table->decimal('nilai_pagu', 18, 2)->default(0);
            $table->decimal('nilai_hps', 18, 2)->nullable();
            $table->string('status', 30)->default('draft')->index();
            $table->uuid('created_by');
            $table->timestamps();
            $table->foreign('tahun_anggaran_id')->references('id')->on('tahun_anggarans')->restrictOnDelete();
            $table->foreign('unit_id')->references('id')->on('units')->restrictOnDelete();
            $table->foreign('procurement_method_id')->references('id')->on('procurement_methods')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procurement_packages');
    }
};
