<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_step_conditions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('workflow_step_id');
            $table->uuid('sub_kategori_rkbu_id')->nullable();
            $table->uuid('unit_id')->nullable();
            $table->uuid('fase_id')->nullable();
            $table->decimal('minimal_nominal', 18, 2)->nullable();
            $table->decimal('maksimal_nominal', 18, 2)->nullable();
            $table->string('field_name', 100)->nullable();
            $table->string('operator', 20)->nullable();
            $table->string('field_value')->nullable();
            $table->timestamps();
            $table->foreign('workflow_step_id')->references('id')->on('workflow_steps')->cascadeOnDelete();
            $table->foreign('sub_kategori_rkbu_id')->references('id')->on('sub_kategori_rkbus')->nullOnDelete();
            $table->foreign('unit_id')->references('id')->on('units')->nullOnDelete();
            $table->foreign('fase_id')->references('id')->on('fases')->nullOnDelete();
            $table->index(['workflow_step_id', 'unit_id', 'fase_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_step_conditions');
    }
};
