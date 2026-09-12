<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rkbu_detail_modal', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('rkbu_detail_id')->unique();
            $table->string('rating', 100)->nullable();
            $table->string('link_ekatalog')->nullable();
            $table->string('penempatan')->nullable();
            $table->text('status_komponen')->nullable();
            $table->unsignedInteger('standar_kebutuhan')->nullable();
            $table->unsignedInteger('eksisting')->nullable();
            $table->string('kondisi_baik', 100)->nullable();
            $table->string('kondisi_rusak_berat', 100)->nullable();
            $table->timestamps();
            $table->foreign('rkbu_detail_id')->references('id')->on('rkbu_details')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rkbu_detail_modal');
    }
};
