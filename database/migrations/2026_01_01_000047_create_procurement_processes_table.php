<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procurement_processes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('procurement_package_id');
            $table->string('tahapan', 50);
            $table->string('nomor', 100)->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('status', 30)->default('draft');
            $table->uuid('workflow_instance_id')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->foreign('procurement_package_id')->references('id')->on('procurement_packages')->cascadeOnDelete();
            $table->foreign('workflow_instance_id')->references('id')->on('workflow_instances')->nullOnDelete();
            $table->index(['procurement_package_id', 'tahapan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procurement_processes');
    }
};
