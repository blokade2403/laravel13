<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goods_receipts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('contract_id');
            $table->string('nomor_penerimaan', 100)->unique();
            $table->date('tanggal_penerimaan');
            $table->uuid('diterima_oleh')->nullable();
            $table->string('status', 30)->default('draft');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->foreign('contract_id')->references('id')->on('contracts')->restrictOnDelete();
            $table->foreign('diterima_oleh')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goods_receipts');
    }
};
