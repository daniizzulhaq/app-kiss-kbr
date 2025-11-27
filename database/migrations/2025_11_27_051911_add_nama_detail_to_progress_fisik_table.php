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
        Schema::table('progress_fisik', function (Blueprint $table) {
            // Tambah kolom nama_detail setelah master_kegiatan_id
            $table->string('nama_detail')->nullable()->after('master_kegiatan_id')
                  ->comment('Detail spesifik kegiatan, contoh: Pengadaan Benih Mahoni');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('progress_fisik', function (Blueprint $table) {
            $table->dropColumn('nama_detail');
        });
    }
};