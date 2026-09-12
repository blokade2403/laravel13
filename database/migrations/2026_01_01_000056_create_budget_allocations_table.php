<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_allocations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tahun_anggaran_id');
            $table->uuid('unit_id');
            $table->uuid('sumber_dana_id')->nullable();
            $table->uuid('rekening_belanja_id');
            $table->decimal('pagu', 18, 2)->default(0);
            $table->decimal('revisi', 18, 2)->default(0);
            $table->decimal('pagu_aktif', 18, 2)->default(0);
            $table->timestamps();
            $table->foreign('tahun_anggaran_id')->references('id')->on('tahun_anggarans')->restrictOnDelete();
            $table->foreign('unit_id')->references('id')->on('units')->restrictOnDelete();
            $table->foreign('sumber_dana_id')->references('id')->on('sumber_danas')->nullOnDelete();
            $table->foreign('rekening_belanja_id')->references('id')->on('rekening_belanjas')->restrictOnDelete();
            $table->unique(['tahun_anggaran_id', 'unit_id', 'sumber_dana_id', 'rekening_belanja_id'], 'tahun_anggaran_unit_sumber_rekening_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_allocations');
    }
};
