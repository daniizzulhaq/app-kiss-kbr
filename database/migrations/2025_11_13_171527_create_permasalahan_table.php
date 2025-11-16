<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permasalahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelompok_id')->constrained('users')->cascadeOnDelete();
            $table->string('kelompok'); // Nama kelompok
            $table->string('sarpras'); // Sarana prasarana
            $table->string('bibit'); // Jenis bibit
            $table->string('lokasi_tanam'); // Lokasi penanaman
            $table->text('permasalahan'); // Deskripsi permasalahan
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi'])->default('sedang');
            $table->enum('status', ['pending', 'diterima', 'diproses', 'selesai', 'ditolak'])->default('pending');
            $table->text('solusi')->nullable(); // Solusi dari BPDAS
            $table->foreignId('ditangani_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('ditangani_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permasalahan');
    }
};