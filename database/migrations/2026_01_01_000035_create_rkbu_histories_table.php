<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rkbu_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('rkbu_id');
            $table->uuid('approval_id')->nullable();
            $table->uuid('user_id');
            $table->string('aksi', 50);
            $table->json('data_sebelum')->nullable();
            $table->json('data_sesudah')->nullable();
            $table->text('catatan')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
            $table->foreign('rkbu_id')->references('id')->on('rkbus')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->index(['rkbu_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rkbu_histories');
    }
};
