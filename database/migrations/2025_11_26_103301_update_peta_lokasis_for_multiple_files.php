<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Backup data lama ke temporary columns (jika ada data)
        if (Schema::hasColumn('peta_lokasis', 'file_path')) {
            Schema::table('peta_lokasis', function (Blueprint $table) {
                $table->string('old_file_path')->nullable()->after('file_path');
                $table->string('old_file_name')->nullable()->after('file_name');
            });

            // Copy data lama
            DB::table('peta_lokasis')->update([
                'old_file_path' => DB::raw('file_path'),
                'old_file_name' => DB::raw('file_name')
            ]);
        }

        // Drop kolom lama
        Schema::table('peta_lokasis', function (Blueprint $table) {
            $table->dropColumn(['file_path', 'file_name']);
        });

        // Tambah kolom baru
        Schema::table('peta_lokasis', function (Blueprint $table) {
            $table->json('files')->after('keterangan')->nullable();
            $table->integer('file_count')->default(0)->after('files');
        });

        // Migrate data lama ke format baru (jika ada)
        $oldRecords = DB::table('peta_lokasis')
            ->whereNotNull('old_file_path')
            ->get();

        foreach ($oldRecords as $record) {
            $filesArray = [[
                'path' => $record->old_file_path,
                'name' => $record->old_file_name,
                'size' => 0, // Tidak bisa detect size file lama
            ]];

            DB::table('peta_lokasis')
                ->where('id', $record->id)
                ->update([
                    'files' => json_encode($filesArray),
                    'file_count' => 1
                ]);
        }

        // Hapus temporary columns
        if (Schema::hasColumn('peta_lokasis', 'old_file_path')) {
            Schema::table('peta_lokasis', function (Blueprint $table) {
                $table->dropColumn(['old_file_path', 'old_file_name']);
            });
        }
    }

    public function down()
    {
        // Kembalikan ke struktur lama
        Schema::table('peta_lokasis', function (Blueprint $table) {
            $table->string('file_path')->nullable()->after('keterangan');
            $table->string('file_name')->nullable()->after('file_path');
        });

        // Migrate data kembali (ambil file pertama saja)
        $records = DB::table('peta_lokasis')->whereNotNull('files')->get();

        foreach ($records as $record) {
            $files = json_decode($record->files, true);
            if (!empty($files) && is_array($files)) {
                $firstFile = $files[0];
                DB::table('peta_lokasis')
                    ->where('id', $record->id)
                    ->update([
                        'file_path' => $firstFile['path'] ?? null,
                        'file_name' => $firstFile['name'] ?? null
                    ]);
            }
        }

        // Drop kolom baru
        Schema::table('peta_lokasis', function (Blueprint $table) {
            $table->dropColumn(['files', 'file_count']);
        });
    }
};