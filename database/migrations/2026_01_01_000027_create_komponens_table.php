<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komponens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('jenis_kategori_rkbu_id')->nullable();
            $table->string('kode_barang', 100)->nullable();
            $table->string('kode_komponen', 100)->unique();
            $table->string('nama_barang');
            $table->string('satuan', 100)->nullable();
            $table->text('spek')->nullable();
            $table->decimal('harga_barang', 18, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreign('jenis_kategori_rkbu_id')->references('id')->on('jenis_kategori_rkbus')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komponens');
    }
};
