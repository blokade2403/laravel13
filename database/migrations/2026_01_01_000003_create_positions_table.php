<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('unit_id');
            $table->string('kode_jabatan', 100)->nullable();
            $table->string('nama_jabatan');
            $table->string('level_jabatan', 50)->nullable();
            $table->string('jenis_jabatan', 50)->nullable();
            $table->boolean('is_validator')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['unit_id', 'is_active']);
            $table->foreign('unit_id')->references('id')->on('units')->restrictOnDelete();
            $table->unique(['unit_id', 'nama_jabatan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
