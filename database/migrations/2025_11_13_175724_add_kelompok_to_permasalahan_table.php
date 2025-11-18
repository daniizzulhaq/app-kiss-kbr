<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permasalahan', function (Blueprint $table) {
            if (!Schema::hasColumn('permasalahan', 'kelompok')) {
                $table->string('kelompok')->after('kelompok_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('permasalahan', function (Blueprint $table) {
            if (Schema::hasColumn('permasalahan', 'kelompok')) {
                $table->dropColumn('kelompok');
            }
        });
    }
};
