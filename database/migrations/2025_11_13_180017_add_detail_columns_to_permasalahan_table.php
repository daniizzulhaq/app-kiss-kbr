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
        $table->string('sarpras')->nullable();
        $table->string('bibit')->nullable();
        $table->string('lokasi_tanam')->nullable();
    });
}

public function down(): void
{
    Schema::table('permasalahan', function (Blueprint $table) {
        $table->dropColumn(['sarpras', 'bibit', 'lokasi_tanam']);
    });
}

};
