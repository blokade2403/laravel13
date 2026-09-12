<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goods_receipt_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('goods_receipt_id');
            $table->uuid('contract_item_id');
            $table->decimal('jumlah_diterima', 18, 2)->default(0);
            $table->decimal('jumlah_diterima_baik', 18, 2)->default(0);
            $table->decimal('jumlah_diterima_rusak', 18, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->foreign('goods_receipt_id')->references('id')->on('goods_receipts')->cascadeOnDelete();
            $table->foreign('contract_item_id')->references('id')->on('contract_items')->cascadeOnDelete();
            $table->unique(['goods_receipt_id', 'contract_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goods_receipt_items');
    }
};
