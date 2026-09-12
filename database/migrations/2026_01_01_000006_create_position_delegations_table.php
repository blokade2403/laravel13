<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('position_delegations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('position_id');
            $table->uuid('from_assignment_id');
            $table->uuid('to_assignment_id');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('nomor_sk', 100)->nullable();
            $table->text('alasan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreign('position_id')->references('id')->on('positions')->restrictOnDelete();
            $table->foreign('from_assignment_id')->references('id')->on('position_assignments')->restrictOnDelete();
            $table->foreign('to_assignment_id')->references('id')->on('position_assignments')->restrictOnDelete();
            $table->index(['position_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('position_delegations');
    }
};
