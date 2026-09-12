<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_kategori_rkbu_responsibles', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('sub_kategori_rkbu_id');
            $table->uuid('position_assignment_id');
            $table->uuid('official_role_id')->nullable();
            $table->uuid('unit_id')->nullable();

            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();

            $table->boolean('is_active')->default(true);

            $table->string('nomor_sk', 100)->nullable();
            $table->date('tanggal_sk')->nullable();

            $table->text('keterangan')->nullable();

            $table->timestamps();

            /*
             * Foreign Key
             */
            $table->foreign('sub_kategori_rkbu_id')
                ->references('id')
                ->on('sub_kategori_rkbus')
                ->restrictOnDelete();

            $table->foreign('position_assignment_id')
                ->references('id')
                ->on('position_assignments')
                ->restrictOnDelete();

            $table->foreign('official_role_id')
                ->references('id')
                ->on('official_roles')
                ->restrictOnDelete();

            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->restrictOnDelete();

            /*
             * Index
             */
            $table->index(
                ['sub_kategori_rkbu_id', 'is_active'],
                'skr_responsible_subkategori_active_idx'
            );

            $table->index(
                ['position_assignment_id', 'is_active'],
                'skr_responsible_assignment_active_idx'
            );

            $table->index(
                ['official_role_id', 'is_active'],
                'skr_responsible_role_active_idx'
            );

            $table->index(
                ['unit_id', 'is_active'],
                'skr_responsible_unit_active_idx'
            );

            $table->index(
                ['tanggal_mulai', 'tanggal_selesai'],
                'skr_responsible_period_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_kategori_rkbu_responsibles');
    }
};
