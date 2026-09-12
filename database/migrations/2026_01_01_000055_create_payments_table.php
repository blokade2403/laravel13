<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('payment_request_id');
            $table->uuid('rekening_belanja_id')->nullable();
            $table->string('nomor_bukti', 100)->unique();
            $table->date('tanggal_bayar');
            $table->decimal('nilai_bayar', 18, 2)->default(0);
            $table->string('status', 30)->default('draft')->index();
            $table->timestamps();
            $table->foreign('payment_request_id')->references('id')->on('payment_requests')->restrictOnDelete();
            $table->foreign('rekening_belanja_id')->references('id')->on('rekening_belanjas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
