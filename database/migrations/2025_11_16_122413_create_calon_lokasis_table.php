<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calon_lokasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_kelompok_desa');
            $table->string('kecamatan');
            $table->string('kabupaten');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('koordinat_pdf_lokasi')->nullable(); // untuk file PDF
            $table->text('deskripsi')->nullable();
            $table->enum('status_verifikasi', ['pending', 'diverifikasi', 'ditolak'])->default('pending');
            $table->text('catatan_bpdas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calon_lokasis');
    }
};