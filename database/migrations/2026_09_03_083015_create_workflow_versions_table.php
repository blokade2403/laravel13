<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_versions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('workflow_id');
            $table->unsignedInteger('version_no');

            $table->string('name');
            $table->text('description')->nullable();

            $table->string('status')->default('DRAFT');
            // DRAFT, PUBLISHED, ARCHIVED

            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            $table->foreign('workflow_id')
                ->references('id')
                ->on('workflows')
                ->cascadeOnDelete();

            $table->unique([
                'workflow_id',
                'version_no',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_versions');
    }
};
