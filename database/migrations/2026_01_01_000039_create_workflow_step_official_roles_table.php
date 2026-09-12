<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_step_official_roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('workflow_step_id');
            $table->uuid('official_role_id');
            $table->timestamps();
            $table->foreign('workflow_step_id')->references('id')->on('workflow_steps')->cascadeOnDelete();
            $table->foreign('official_role_id')->references('id')->on('official_roles')->restrictOnDelete();
            $table->unique(['workflow_step_id', 'official_role_id'], 'workflow_step_id_official_role_id_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_step_official_roles');
    }
};
