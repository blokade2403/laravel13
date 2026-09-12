<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('contract_id');
            $table->uuid('vendor_id');
            $table->string('nomor_invoice', 100);
            $table->date('tanggal_invoice');
            $table->decimal('nilai_invoice', 18, 2)->default(0);
            $table->decimal('ppn', 18, 2)->default(0);
            $table->decimal('total', 18, 2)->default(0);
            $table->string('status', 30)->default('draft')->index();
            $table->timestamps();
            $table->foreign('contract_id')->references('id')->on('contracts')->restrictOnDelete();
            $table->foreign('vendor_id')->references('id')->on('vendors')->restrictOnDelete();
            $table->unique(['vendor_id', 'nomor_invoice']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
