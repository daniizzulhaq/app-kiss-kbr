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
        Schema::create('dokumentasi_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('progress_fisik_id')->constrained('progress_fisik')->cascadeOnDelete();
            $table->string('foto');
            $table->text('keterangan')->nullable();
            $table->date('tanggal_foto');
            $table->timestamps();

            // Index untuk performa query
            $table->index('progress_fisik_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumentasi_progress');
    }
};