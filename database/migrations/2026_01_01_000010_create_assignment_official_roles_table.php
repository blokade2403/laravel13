<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignment_official_roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('position_assignment_id');
            $table->uuid('official_role_id');
            $table->uuid('unit_id')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('nomor_sk', 100)->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->foreign('position_assignment_id')->references('id')->on('position_assignments')->restrictOnDelete();
            $table->foreign('official_role_id')->references('id')->on('official_roles')->restrictOnDelete();
            $table->foreign('unit_id')->references('id')->on('units')->nullOnDelete();
            $table->index(['official_role_id', 'unit_id', 'is_active'], 'aor_role_unit_active_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_official_roles');
    }
};
