<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('obyek_belanjas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('jenis_kategori_rkbu_id');
            $table->string('kode_obyek_belanja', 100);
            $table->string('nama_obyek_belanja');
            $table->timestamps();
            $table->foreign('jenis_kategori_rkbu_id')->references('id')->on('jenis_kategori_rkbus')->restrictOnDelete();
            $table->unique(['jenis_kategori_rkbu_id', 'kode_obyek_belanja']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obyek_belanjas');
    }
};
