<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('progress_fisik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelompok_id')->constrained('kelompoks')->cascadeOnDelete();
            $table->foreignId('master_kegiatan_id')->constrained('master_kegiatan')->cascadeOnDelete();
            $table->decimal('volume_target', 10, 2)->nullable();
            $table->decimal('biaya_satuan', 15, 2)->nullable(); // Harga per satuan
            $table->decimal('total_biaya', 15, 2)->default(0); // volume * biaya_satuan
            $table->decimal('volume_realisasi', 10, 2)->default(0);
            $table->decimal('biaya_realisasi', 15, 2)->default(0);
            $table->decimal('persentase_fisik', 5, 2)->default(0); // 0-100
            $table->boolean('is_selesai')->default(false);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status_verifikasi', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('catatan_verifikasi')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            // Index untuk performa query
            $table->index(['kelompok_id', 'status_verifikasi']);
            $table->index('master_kegiatan_id');
            
            // Unique constraint: satu kelompok tidak bisa punya kegiatan yang sama lebih dari sekali
            $table->unique(['kelompok_id', 'master_kegiatan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_fisik');
    }
};