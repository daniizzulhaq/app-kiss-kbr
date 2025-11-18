<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permasalahan', function (Blueprint $table) {
            if (!Schema::hasColumn('permasalahan', 'permasalahan')) {
                $table->text('permasalahan')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('permasalahan', function (Blueprint $table) {
            if (Schema::hasColumn('permasalahan', 'permasalahan')) {
                $table->dropColumn('permasalahan');
            }
        });
    }
};
