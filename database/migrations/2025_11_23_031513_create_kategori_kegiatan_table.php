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
        Schema::create('master_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_kegiatan')->cascadeOnDelete();
            $table->string('nomor'); // 1, 2, 3 dst
            $table->text('nama_kegiatan');
            $table->string('satuan', 50); // Buah, Unit, HOK, Kg, dll
            $table->boolean('is_honor')->default(false); // Apakah termasuk honor/upah
            $table->integer('urutan')->default(0);
            $table->timestamps();

            // Index untuk performa query
            $table->index(['kategori_id', 'urutan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_kegiatan');
    }
};