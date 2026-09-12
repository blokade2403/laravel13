<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('position_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('position_id');
            $table->uuid('user_id');
            $table->uuid('unit_id');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('assignment_type', 30)->default('DEFINITIF'); // DEFINITIF/PLT/PLH/PENGGANTI
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('nomor_sk', 100)->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'is_active']);
            $table->index(['position_id', 'unit_id', 'is_active']);
            $table->index(['tanggal_mulai', 'tanggal_selesai']);
            $table->foreign('position_id')->references('id')->on('positions')->restrictOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->foreign('unit_id')->references('id')->on('units')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('position_assignments');
    }
};
