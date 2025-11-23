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
        Schema::create('anggaran_kelompok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelompok_id')->constrained('kelompoks')->cascadeOnDelete();
            $table->decimal('total_anggaran', 15, 2)->default(100000000); // 100 juta
            $table->decimal('realisasi_anggaran', 15, 2)->default(0);
            $table->decimal('sisa_anggaran', 15, 2)->default(100000000);
            $table->year('tahun');
            $table->timestamps();

            // Index untuk performa query
            $table->index(['kelompok_id', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggaran_kelompok');
    }
};