<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calon_lokasis', function (Blueprint $table) {
            // Hapus kolom lama (optional, jika ingin migrasi ulang)
            // $table->dropColumn(['latitude', 'longitude', 'koordinat_pdf_lokasi']);
            
            // Tambah 5 kolom untuk PDF
            $table->string('pdf_dokumen_1')->nullable()->after('kabupaten');
            $table->string('pdf_dokumen_2')->nullable()->after('pdf_dokumen_1');
            $table->string('pdf_dokumen_3')->nullable()->after('pdf_dokumen_2');
            $table->string('pdf_dokumen_4')->nullable()->after('pdf_dokumen_3');
            $table->string('pdf_dokumen_5')->nullable()->after('pdf_dokumen_4');
            
            // Tambah kolom untuk menyimpan polygon coordinates (GeoJSON format)
            $table->text('polygon_coordinates')->nullable()->after('pdf_dokumen_5');
            
            // Tetap simpan latitude/longitude untuk center point
            $table->decimal('center_latitude', 10, 8)->nullable()->after('polygon_coordinates');
            $table->decimal('center_longitude', 11, 8)->nullable()->after('center_latitude');
        });
    }

    public function down(): void
    {
        Schema::table('calon_lokasis', function (Blueprint $table) {
            $table->dropColumn([
                'pdf_dokumen_1',
                'pdf_dokumen_2',
                'pdf_dokumen_3',
                'pdf_dokumen_4',
                'pdf_dokumen_5',
                'polygon_coordinates',
                'center_latitude',
                'center_longitude'
            ]);
        });
    }
};