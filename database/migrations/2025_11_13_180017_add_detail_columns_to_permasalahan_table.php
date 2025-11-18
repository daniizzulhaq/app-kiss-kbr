<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permasalahan', function (Blueprint $table) {
            if (!Schema::hasColumn('permasalahan', 'sarpras')) {
                $table->string('sarpras')->nullable();
            }
            if (!Schema::hasColumn('permasalahan', 'bibit')) {
                $table->string('bibit')->nullable();
            }
            if (!Schema::hasColumn('permasalahan', 'lokasi_tanam')) {
                $table->string('lokasi_tanam')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('permasalahan', function (Blueprint $table) {
            if (Schema::hasColumn('permasalahan', 'sarpras')) {
                $table->dropColumn('sarpras');
            }
            if (Schema::hasColumn('permasalahan', 'bibit')) {
                $table->dropColumn('bibit');
            }
            if (Schema::hasColumn('permasalahan', 'lokasi_tanam')) {
                $table->dropColumn('lokasi_tanam');
            }
        });
    }
};
