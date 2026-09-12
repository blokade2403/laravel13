<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procurement_package_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('procurement_package_id');
            $table->uuid('rkbu_detail_id');
            $table->decimal('volume', 18, 2)->default(0);
            $table->decimal('harga_satuan', 18, 2)->default(0);
            $table->decimal('nilai', 18, 2)->default(0);
            $table->timestamps();
            $table->foreign('procurement_package_id')->references('id')->on('procurement_packages')->cascadeOnDelete();
            $table->foreign('rkbu_detail_id')->references('id')->on('rkbu_details')->restrictOnDelete();
            $table->unique(['procurement_package_id', 'rkbu_detail_id'], 'procurement_package_id_rkbu_detail_id_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procurement_package_items');
    }
};
