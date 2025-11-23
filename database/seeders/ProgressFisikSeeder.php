<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgressFisikSeeder extends Seeder
{
    public function run(): void
    {
        // Kategori Kegiatan
        $kategoriA = DB::table('kategori_kegiatan')->insertGetId([
            'kode' => 'A',
            'nama' => 'PEMBUATAN SARANA PRASARANA',
            'deskripsi' => 'Bahan-Bahan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $kategoriB = DB::table('kategori_kegiatan')->insertGetId([
            'kode' => 'B',
            'nama' => 'PRODUKSI BIBIT',
            'deskripsi' => 'Bahan-Bahan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $kategoriC = DB::table('kategori_kegiatan')->insertGetId([
            'kode' => 'C',
            'nama' => 'PERTEMUAN KELOMPOK DAN PENDAMPINGAN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Master Kegiatan Kategori A
        $kegiatanA = [
            ['nomor' => '1', 'nama' => 'Pengadaan bahan papan nama kegiatan', 'satuan' => 'Buah', 'is_honor' => false],
            ['nomor' => '2', 'nama' => 'Pengadaan bahan papan mural/rencana kerja', 'satuan' => 'Unit', 'is_honor' => false],
            ['nomor' => '3', 'nama' => 'Pengadaan bahan bedengna tabur/kecambah', 'satuan' => 'Buah', 'is_honor' => false],
            ['nomor' => '4', 'nama' => 'Pengadaan bahan bedeng sapih', 'satuan' => 'Bedeng', 'is_honor' => false],
            ['nomor' => '5', 'nama' => 'Pengadaan bahan penyangga naungan (bambu/kayu)', 'satuan' => 'Batang', 'is_honor' => false],
            ['nomor' => '6', 'nama' => 'Pengadaan naungan', 'satuan' => 'Kegiatan', 'is_honor' => false],
            ['nomor' => '7', 'nama' => 'Pengadaan peralatan kerja', 'satuan' => 'Paket', 'is_honor' => false],
            ['nomor' => '8', 'nama' => 'Pengadaan pompa air dan instalasi pengairan', 'satuan' => 'Unit', 'is_honor' => false],
            ['nomor' => '9', 'nama' => 'Pengadaan bahan pondok kerja', 'satuan' => 'Unit', 'is_honor' => false],
        ];

        // Honor Kategori A
        $honorA = [
            ['nomor' => '1', 'nama' => 'Upah pembuatan papan dan pemasangan papan nama', 'satuan' => 'HOK', 'is_honor' => true],
            ['nomor' => '2', 'nama' => 'Upah pembuatan dan pemasangan papan nama, papan mural, papan rencana kerja', 'satuan' => 'HOK', 'is_honor' => true],
            ['nomor' => '3', 'nama' => 'Upah pembuatan bedeng tabur dan bedeng sapih', 'satuan' => 'HOK', 'is_honor' => true],
            ['nomor' => '4', 'nama' => 'Upah pemasangan instalasi air dan bak penampungan', 'satuan' => 'HOK', 'is_honor' => true],
            ['nomor' => '5', 'nama' => 'Upah pemasangan naungan', 'satuan' => 'HOK', 'is_honor' => true],
            ['nomor' => '6', 'nama' => 'Upah pembuatan pondok kerja', 'satuan' => 'HOK', 'is_honor' => true],
        ];

        foreach (array_merge($kegiatanA, $honorA) as $index => $kegiatan) {
            DB::table('master_kegiatan')->insert([
                'kategori_id' => $kategoriA,
                'nomor' => $kegiatan['nomor'],
                'nama_kegiatan' => $kegiatan['nama'],
                'satuan' => $kegiatan['satuan'],
                'is_honor' => $kegiatan['is_honor'],
                'urutan' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Master Kegiatan Kategori B
        $kegiatanB = [
            ['nomor' => '1', 'nama' => 'Pengadaan ATK dan buku administrasi kelompok tani', 'satuan' => 'Kegiatan', 'is_honor' => false],
            ['nomor' => '2', 'nama' => 'Pengadaan kantong plastik', 'satuan' => 'Lembar', 'is_honor' => false],
            ['nomor' => '3', 'nama' => 'Pengadaan tanah top soil', 'satuan' => 'M³', 'is_honor' => false],
            ['nomor' => '4', 'nama' => 'Pengadaan pupuk organik', 'satuan' => 'Kg', 'is_honor' => false],
            ['nomor' => '5', 'nama' => 'Pengadaan kompos/pupuk an organik lainnya', 'satuan' => 'Kg', 'is_honor' => false],
            ['nomor' => '6', 'nama' => 'Pengadaan benih', 'satuan' => 'Kg', 'is_honor' => false],
            ['nomor' => '7', 'nama' => 'Pengadaan benih', 'satuan' => 'Kg', 'is_honor' => false],
            ['nomor' => '8', 'nama' => 'Pengadaan obat-obatan', 'satuan' => 'Liter', 'is_honor' => false],
        ];

        $honorB = [
            ['nomor' => '1', 'nama' => 'Upah pembuatan/pencampuran media', 'satuan' => 'HOK', 'is_honor' => true],
            ['nomor' => '2', 'nama' => 'Upah pengisian kantong plastik', 'satuan' => 'HOK', 'is_honor' => true],
            ['nomor' => '3', 'nama' => 'Upah penaburan, penyapihan, penyiraman, penyiangan dll', 'satuan' => 'HOK', 'is_honor' => true],
            ['nomor' => '4', 'nama' => 'Upah pemeliharaan/perawatan', 'satuan' => 'HOK', 'is_honor' => true],
        ];

        foreach (array_merge($kegiatanB, $honorB) as $index => $kegiatan) {
            DB::table('master_kegiatan')->insert([
                'kategori_id' => $kategoriB,
                'nomor' => $kegiatan['nomor'],
                'nama_kegiatan' => $kegiatan['nama'],
                'satuan' => $kegiatan['satuan'],
                'is_honor' => $kegiatan['is_honor'],
                'urutan' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Master Kegiatan Kategori C
        $kegiatanC = [
            ['nomor' => '1', 'nama' => 'Pertemuan kelompok tani', 'satuan' => 'Kali', 'is_honor' => false],
            ['nomor' => '2', 'nama' => 'Foto copy dan dokumentasi', 'satuan' => 'Kegiatan', 'is_honor' => false],
        ];

        foreach ($kegiatanC as $index => $kegiatan) {
            DB::table('master_kegiatan')->insert([
                'kategori_id' => $kategoriC,
                'nomor' => $kegiatan['nomor'],
                'nama_kegiatan' => $kegiatan['nama'],
                'satuan' => $kegiatan['satuan'],
                'is_honor' => false,
                'urutan' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}