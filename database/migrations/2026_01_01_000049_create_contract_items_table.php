<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contract_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('contract_id');
            $table->uuid('procurement_package_item_id')->nullable();
            $table->string('nama');
            $table->text('spesifikasi')->nullable();
            $table->decimal('volume', 18, 2)->default(0);
            $table->string('satuan', 100)->nullable();
            $table->decimal('harga_satuan', 18, 2)->default(0);
            $table->decimal('nilai', 18, 2)->default(0);
            $table->timestamps();

            // Contract
            $table->foreign('contract_id')
                ->references('id')
                ->on('contracts')
                ->cascadeOnDelete();

            // Procurement Package Item
            $table->foreign('procurement_package_item_id')
                ->references('id')
                ->on('procurement_package_items')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_items');
    }
};
