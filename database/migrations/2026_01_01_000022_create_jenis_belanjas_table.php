<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_belanjas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_jenis_belanja', 50)->unique();
            $table->string('nama_jenis_belanja')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_belanjas');
    }
};
