<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('procurement_package_id');
            $table->uuid('vendor_id');
            $table->string('nomor_kontrak', 100)->unique();
            $table->date('tanggal_kontrak');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->decimal('nilai_kontrak', 18, 2);
            $table->decimal('ppn', 18, 2)->default(0);
            $table->decimal('nilai_setelah_pajak', 18, 2)->default(0);
            $table->string('status', 30)->default('draft');
            $table->timestamps();
            $table->foreign('procurement_package_id')->references('id')->on('procurement_packages')->restrictOnDelete();
            $table->foreign('vendor_id')->references('id')->on('vendors')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
