<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permasalahan', function (Blueprint $table) {
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi'])->default('rendah')->after('permasalahan');
            $table->enum('status', ['pending', 'diproses', 'selesai'])->default('pending')->after('prioritas');
            $table->text('solusi')->nullable()->after('status');
            $table->string('ditangani_oleh')->nullable()->after('solusi');
            $table->timestamp('ditangani_pada')->nullable()->after('ditangani_oleh');
        });
    }

    public function down(): void
    {
        Schema::table('permasalahan', function (Blueprint $table) {
            $table->dropColumn(['prioritas', 'status', 'solusi', 'ditangani_oleh', 'ditangani_pada']);
        });
    }
};
