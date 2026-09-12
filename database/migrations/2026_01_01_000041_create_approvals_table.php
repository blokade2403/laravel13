<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('workflow_instance_id');
            $table->uuid('workflow_step_id');
            $table->uuid('target_position_id')->nullable();
            $table->uuid('target_assignment_id')->nullable();
            $table->uuid('target_official_role_id')->nullable();
            $table->uuid('assigned_user_id')->nullable();
            $table->string('status', 30)->default('pending')->index();
            $table->text('catatan')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->foreign('workflow_instance_id')->references('id')->on('workflow_instances')->cascadeOnDelete();
            $table->foreign('workflow_step_id')->references('id')->on('workflow_steps')->restrictOnDelete();
            $table->foreign('target_position_id')->references('id')->on('positions')->nullOnDelete();
            $table->foreign('target_assignment_id')->references('id')->on('position_assignments')->nullOnDelete();
            $table->foreign('target_official_role_id')->references('id')->on('official_roles')->nullOnDelete();
            $table->foreign('assigned_user_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['assigned_user_id', 'status']);
            $table->index(['target_position_id', 'status']);
            $table->index(['target_official_role_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
