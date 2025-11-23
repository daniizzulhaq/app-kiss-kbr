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
        Schema::table('anggaran_kelompok', function (Blueprint $table) {
            $table->decimal('anggaran_dialokasikan', 15, 2)->default(0)->after('total_anggaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggaran_kelompok', function (Blueprint $table) {
            $table->dropColumn('anggaran_dialokasikan');
        });
    }
};