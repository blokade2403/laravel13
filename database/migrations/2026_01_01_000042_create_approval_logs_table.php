<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('approval_id')->nullable();
            $table->uuid('workflow_instance_id');
            $table->uuid('user_id')->nullable();
            $table->uuid('position_id')->nullable();
            $table->uuid('position_assignment_id')->nullable();
            $table->uuid('official_role_id')->nullable();
            $table->string('aksi', 30);
            $table->text('catatan')->nullable();
            $table->json('data_snapshot')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('aksi_at');
            $table->timestamps();
            $table->foreign('approval_id')->references('id')->on('approvals')->nullOnDelete();
            $table->foreign('workflow_instance_id')->references('id')->on('workflow_instances')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('position_id')->references('id')->on('positions')->nullOnDelete();
            $table->foreign('position_assignment_id')->references('id')->on('position_assignments')->nullOnDelete();
            $table->foreign('official_role_id')->references('id')->on('official_roles')->nullOnDelete();
            $table->index(['workflow_instance_id', 'aksi_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_logs');
    }
};
