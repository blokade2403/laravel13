<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workflow_instances', function (Blueprint $table) {
            $table->uuid('source_assignment_id')
                ->nullable()
                ->after('id');

            $table->foreign('source_assignment_id')
                ->references('id')
                ->on('position_assignments')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('workflow_instances', function (Blueprint $table) {
            $table->dropForeign([
                'source_assignment_id',
            ]);

            $table->dropColumn('source_assignment_id');
        });
    }
};
