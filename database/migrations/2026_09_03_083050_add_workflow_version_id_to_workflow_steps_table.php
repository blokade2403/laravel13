<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workflow_steps', function (Blueprint $table) {
            $table->uuid('workflow_version_id')
                ->nullable()
                ->after('workflow_id');

            $table->unsignedInteger('hierarchy_depth')
                ->nullable()
                ->after('step_order');

            $table->string('target_scope_type')
                ->default('DOCUMENT_UNIT')
                ->after('approval_type');

            $table->uuid('target_unit_id')
                ->nullable()
                ->after('target_scope_type');

            $table->foreign('workflow_version_id')
                ->references('id')
                ->on('workflow_versions')
                ->nullOnDelete();

            $table->foreign('target_unit_id')
                ->references('id')
                ->on('units')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('workflow_steps', function (Blueprint $table) {
            $table->dropForeign([
                'workflow_version_id',
            ]);

            $table->dropForeign([
                'target_unit_id',
            ]);

            $table->dropColumn([
                'workflow_version_id',
                'hierarchy_depth',
                'target_scope_type',
                'target_unit_id',
            ]);
        });
    }
};
