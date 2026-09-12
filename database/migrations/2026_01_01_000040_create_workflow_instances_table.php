<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_instances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('workflow_id');
            $table->uuid('document_id');
            $table->string('document_type', 100);
            $table->uuid('current_step_id')->nullable();
            $table->uuid('current_position_id')->nullable();
            $table->uuid('current_assignment_id')->nullable();
            $table->uuid('created_by');
            $table->string('status', 30)->default('proses')->index();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->foreign('workflow_id')->references('id')->on('workflows')->restrictOnDelete();
            $table->foreign('current_step_id')->references('id')->on('workflow_steps')->nullOnDelete();
            $table->foreign('current_position_id')->references('id')->on('positions')->nullOnDelete();
            $table->foreign('current_assignment_id')->references('id')->on('position_assignments')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->restrictOnDelete();
            $table->unique(['document_type', 'document_id']);
            $table->index(['workflow_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_instances');
    }
};
