<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('workflow_id');
            $table->unsignedInteger('step_order');
            $table->string('kode_step', 100);
            $table->string('nama_step');
            $table->string('approval_type', 30); // HIERARCHY/POSITION/OFFICIAL_ROLE/ROLE
            $table->uuid('position_id')->nullable();
            $table->uuid('official_role_id')->nullable();
            $table->uuid('role_id')->nullable();
            $table->boolean('is_required')->default(true);
            $table->boolean('allow_skip_same_user')->default(false);
            $table->boolean('can_delegate')->default(false);
            $table->string('condition_mode', 20)->default('ALL');
            $table->timestamps();
            $table->foreign('workflow_id')->references('id')->on('workflows')->cascadeOnDelete();
            $table->foreign('position_id')->references('id')->on('positions')->nullOnDelete();
            $table->foreign('official_role_id')->references('id')->on('official_roles')->nullOnDelete();
            $table->foreign('role_id')->references('id')->on('roles')->nullOnDelete();
            $table->unique(['workflow_id', 'step_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_steps');
    }
};
