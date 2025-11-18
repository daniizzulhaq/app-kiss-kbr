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
        Schema::table('permasalahan', function (Blueprint $table) {

            // Tambahkan kolom yang belum ada
            if (!Schema::hasColumn('permasalahan', 'kategori')) {
                $table->string('kategori')->nullable();
            }

            if (!Schema::hasColumn('permasalahan', 'tanggal')) {
                $table->date('tanggal')->nullable();
            }

            if (!Schema::hasColumn('permasalahan', 'status')) {
                $table->string('status')->nullable();
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permasalahan', function (Blueprint $table) {

            // Remove only if exists
            if (Schema::hasColumn('permasalahan', 'kategori')) {
                $table->dropColumn('kategori');
            }
            if (Schema::hasColumn('permasalahan', 'tanggal')) {
                $table->dropColumn('tanggal');
            }
            if (Schema::hasColumn('permasalahan', 'status')) {
                $table->dropColumn('status');
            }

        });
    }
};
