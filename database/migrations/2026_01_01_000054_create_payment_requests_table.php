<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('invoice_id');
            $table->string('nomor_pengajuan', 100)->unique();
            $table->date('tanggal_pengajuan');
            $table->decimal('nilai_pengajuan', 18, 2)->default(0);
            $table->uuid('workflow_instance_id')->nullable();
            $table->uuid('created_by');
            $table->string('status', 30)->default('draft')->index();
            $table->timestamps();
            $table->foreign('invoice_id')->references('id')->on('invoices')->restrictOnDelete();
            $table->foreign('workflow_instance_id')->references('id')->on('workflow_instances')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_requests');
    }
};
