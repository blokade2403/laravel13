<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('budget_allocation_id');
            $table->string('document_type', 100);
            $table->uuid('document_id');
            $table->string('transaction_type', 30); // RESERVASI/REALISASI/PEMBATALAN/REVISI
            $table->decimal('nilai', 18, 2)->default(0);
            $table->timestamp('transaction_at');
            $table->text('keterangan')->nullable();
            $table->uuid('created_by');
            $table->timestamps();
            $table->foreign('budget_allocation_id')->references('id')->on('budget_allocations')->restrictOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->restrictOnDelete();
            $table->index(['document_type', 'document_id']);
            $table->index(['budget_allocation_id', 'transaction_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_transactions');
    }
};
