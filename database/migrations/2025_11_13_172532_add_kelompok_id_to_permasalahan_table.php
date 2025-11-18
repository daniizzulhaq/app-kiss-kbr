<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('permasalahan', function (Blueprint $table) {
        if (!Schema::hasColumn('permasalahan', 'kelompok_id')) {
            $table->foreignId('kelompok_id')
                ->constrained('kelompok', 'id_kelompok')
                ->onDelete('cascade');
        }
    });
}

public function down(): void
{
    Schema::table('permasalahan', function (Blueprint $table) {
        if (Schema::hasColumn('permasalahan', 'kelompok_id')) {
            $table->dropForeign(['kelompok_id']);
            $table->dropColumn('kelompok_id');
        }
    });
}

};