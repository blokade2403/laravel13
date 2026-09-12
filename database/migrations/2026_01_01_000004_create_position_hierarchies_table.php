<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('position_hierarchies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('unit_id');
            // Jabatan yang berada di bawah
            $table->uuid('position_id');
            // Jabatan atasannya
            $table->uuid('parent_position_id');
            $table->string('jenis_hubungan', 50)
                ->default('atasan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->restrictOnDelete();
            $table->foreign('position_id')
                ->references('id')
                ->on('positions')
                ->restrictOnDelete();
            $table->foreign('parent_position_id')
                ->references('id')
                ->on('positions')
                ->restrictOnDelete();
            $table->index([
                'unit_id',
                'position_id',
                'is_active',
            ]);
            $table->index([
                'unit_id',
                'parent_position_id',
                'is_active',
            ]);

            $table->unique(
                [
                    'unit_id',
                    'position_id',
                    'parent_position_id',
                    'tanggal_mulai',
                ],
                'position_hierarchy_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('position_hierarchies');
    }
};
