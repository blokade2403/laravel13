<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('document_id');
            $table->string('document_type', 100);
            $table->string('kategori', 50);
            $table->string('nama_file');
            $table->string('file_path');
            $table->string('mime_type', 150)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->uuid('uploaded_by');
            $table->timestamps();
            $table->foreign('uploaded_by')->references('id')->on('users')->restrictOnDelete();
            $table->index(['document_type', 'document_id', 'kategori']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_files');
    }
};
