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
        Schema::create('real_bibit', function (Blueprint $table) {
            $table->id('id_bibit');

            // Foreign key ke tabel kelompoks
            $table->unsignedBigInteger('id_kelompok');
            $table->foreign('id_kelompok')
                  ->references('id')
                  ->on('kelompoks')
                  ->onDelete('cascade');

            $table->string('jenis_bibit', 100);
            $table->string('golongan', 50)->nullable();
            $table->integer('jumlah_btg')->default(0);
            $table->decimal('tinggi', 10, 2)->nullable()->comment('Tinggi dalam cm');
            $table->string('sertifikat', 100)->nullable();
            
            $table->timestamps();
            
            // Index untuk performa query
            $table->index('id_kelompok');
            $table->index('jenis_bibit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_bibit');
    }
};