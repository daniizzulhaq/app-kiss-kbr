@extends('layouts.dashboard')

@section('title', 'Detail Progress Fisik - Sistem KBR')
@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Detail Progress Fisik: {{ $kelompok->nama_kelompok }}</h1>
                <p class="text-sm text-gray-600 mt-1">Ketua: {{ $kelompok->nama_ketua }}</p>
            </div>
            <a href="{{ route('bpdas.progress-fisik.monitoring') }}" 
               class="text-gray-600 hover:text-gray-900 font-medium">
                ← Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600">Total Kegiatan</p>
            <p class="text-2xl font-bold text-blue-600">{{ $statistikKegiatan['total'] }}</p>
        </div>
        <div class="bg-green-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600">Selesai</p>
            <p class="text-2xl font-bold text-green-600">{{ $statistikKegiatan['selesai'] }}</p>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600">Pending</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $statistikKegiatan['pending'] }}</p>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600">Disetujui</p>
            <p class="text-2xl font-bold text-purple-600">{{ $statistikKegiatan['disetujui'] }}</p>
        </div>
        <div class="bg-red-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600">Ditolak</p>
            <p class="text-2xl font-bold text-red-600">{{ $statistikKegiatan['ditolak'] }}</p>
        </div>
    </div>

    <!-- Anggaran -->
    @if($anggaran)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Anggaran Tahun {{ $anggaran->tahun }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600">Total Anggaran</p>
                        <p class="text-lg font-bold text-gray-800">
                            Rp {{ number_format($anggaran->total_anggaran, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Realisasi</p>
                        <p class="text-lg font-bold text-green-600">
                            Rp {{ number_format($anggaran->realisasi_anggaran, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Sisa</p>
                        <p class="text-lg font-bold text-blue-600">
                            Rp {{ number_format($anggaran->sisa_anggaran, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Progress Anggaran</p>
                        <p class="text-lg font-bold text-purple-600">
                            {{ number_format($anggaran->persentase_realisasi, 1) }}%
                        </p>
                    </div>
                </div>

                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-gradient-to-r from-green-500 to-blue-500 h-4 rounded-full" 
                         style="width: {{ $anggaran->persentase_realisasi }}%"></div>
                </div>
            </div>
        </div>
    @endif

    <!-- Progress Fisik -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Total Progress Fisik</h3>
                <span class="text-2xl font-bold text-green-600">
                    {{ number_format($totalProgress, 1) }}%
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div class="bg-green-500 h-4 rounded-full" 
                     style="width: {{ $totalProgress }}%"></div>
            </div>
        </div>
    </div>

    <!-- Daftar Kegiatan per Kategori -->
    @foreach($progressByKategori as $kategori => $progressItems)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h4 class="text-md font-semibold mb-4 text-gray-800 border-b pb-2">
                    {{ $kategori }}
                </h4>

                <div class="space-y-4">
                    @foreach($progressItems as $progress)
                        <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h5 class="font-semibold text-gray-900">
                                        {{ $progress->masterKegiatan->nama_kegiatan }}
                                    </h5>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Satuan: {{ $progress->masterKegiatan->satuan }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    @if($progress->status_verifikasi == 'pending')
                                        <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                            ⏳ Pending
                                        </span>
                                    @elseif($progress->status_verifikasi == 'disetujui')
                                        <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                            ✅ Disetujui
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                            ❌ Ditolak
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3 text-sm">
                                <div>
                                    <p class="text-gray-600">Target</p>
                                    <p class="font-semibold">{{ number_format($progress->volume_target, 2) }} {{ $progress->masterKegiatan->satuan }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Realisasi</p>
                                    <p class="font-semibold {{ $progress->volume_realisasi > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                        {{ number_format($progress->volume_realisasi, 2) }} {{ $progress->masterKegiatan->satuan }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Total Biaya</p>
                                    <p class="font-semibold">Rp {{ number_format($progress->total_biaya, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Biaya Realisasi</p>
                                    <p class="font-semibold {{ $progress->biaya_realisasi > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                        Rp {{ number_format($progress->biaya_realisasi, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-3">
                                <div class="flex justify-between mb-1">
                                    <span class="text-xs text-gray-600">Progress Fisik</span>
                                    <span class="text-xs font-semibold">{{ number_format($progress->persentase_fisik, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full {{ $progress->persentase_fisik >= 100 ? 'bg-green-500' : 
                                           ($progress->persentase_fisik >= 50 ? 'bg-blue-500' : 'bg-yellow-500') }}" 
                                         style="width: {{ $progress->persentase_fisik }}%"></div>
                                </div>
                            </div>

                            <!-- Dokumentasi -->
                            @if($progress->dokumentasi->count() > 0)
                                <div class="mb-3">
                                    <p class="text-sm font-medium text-gray-700 mb-2">📸 Dokumentasi:</p>
                                    <div class="flex gap-2 flex-wrap">
                                        @foreach($progress->dokumentasi->take(4) as $dok)
                                            <img src="{{ Storage::url($dok->foto) }}" 
                                                 alt="Dokumentasi" 
                                                 class="w-20 h-20 object-cover rounded cursor-pointer hover:opacity-80 border-2 border-gray-200"
                                                 onclick="window.open('{{ Storage::url($dok->foto) }}', '_blank')">
                                        @endforeach
                                        @if($progress->dokumentasi->count() > 4)
                                            <div class="w-20 h-20 bg-gray-100 rounded flex items-center justify-center text-gray-600 text-sm border-2 border-gray-200">
                                                +{{ $progress->dokumentasi->count() - 4 }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Keterangan -->
                            @if($progress->keterangan)
                                <div class="mb-3 text-sm p-3 bg-gray-50 rounded">
                                    <p class="text-gray-600 font-medium">📝 Keterangan:</p>
                                    <p class="text-gray-800 mt-1">{{ $progress->keterangan }}</p>
                                </div>
                            @endif

                            <!-- Form Verifikasi BPDAS -->
                            @if($progress->status_verifikasi == 'pending')
                                <form action="{{ route('bpdas.progress-fisik.verifikasi', $progress) }}" 
                                      method="POST" 
                                      class="mt-4 p-4 bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-300 rounded-lg"
                                      id="form-verifikasi-{{ $progress->id }}">
                                    @csrf
                                    @method('PUT')
                                    
                                    <h6 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                        <span class="text-lg">🔍</span>
                                        Form Verifikasi BPDAS
                                    </h6>

                                    <!-- Input Volume Realisasi (MUNCUL SAAT KLIK APPROVE) -->
                                    <div id="volume-container-{{ $progress->id }}" style="display: none;" class="mb-4 p-3 bg-white rounded-lg border-2 border-green-300">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            📊 Volume Realisasi <span class="text-red-500">*</span>
                                        </label>
                                        <div class="flex gap-2 items-center">
                                            <input type="number" 
                                                   name="volume_realisasi" 
                                                   id="volume-input-{{ $progress->id }}"
                                                   step="0.01"
                                                   min="0"
                                                   max="{{ $progress->volume_target }}"
                                                   value="{{ old('volume_realisasi', $progress->volume_target) }}"
                                                   class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 font-semibold"
                                                   oninput="hitungPreview{{ $progress->id }}()">
                                            <span class="px-4 py-2 bg-gray-100 border-2 border-gray-300 rounded-lg text-gray-700 font-semibold">
                                                {{ $progress->masterKegiatan->satuan }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            ℹ️ Maksimal: {{ number_format($progress->volume_target, 2) }} {{ $progress->masterKegiatan->satuan }}
                                        </p>
                                        
                                        <!-- Preview Perhitungan Real-time -->
                                        <div class="mt-3 p-3 bg-blue-50 border-l-4 border-blue-500 rounded">
                                            <p class="text-xs font-semibold text-blue-900 mb-2">📈 Preview Perhitungan:</p>
                                            <div class="grid grid-cols-2 gap-3 text-sm">
                                                <div>
                                                    <span class="text-blue-700">Biaya Realisasi:</span>
                                                    <p class="font-bold text-blue-900" id="preview-biaya-{{ $progress->id }}">Rp 0</p>
                                                </div>
                                                <div>
                                                    <span class="text-blue-700">Progress Fisik:</span>
                                                    <p class="font-bold text-blue-900" id="preview-progress-{{ $progress->id }}">0.0%</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Catatan Verifikasi -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            💬 Catatan Verifikasi 
                                            <span id="required-label-{{ $progress->id }}" style="display: none;" class="text-red-500">*</span>
                                        </label>
                                        <textarea name="catatan" 
                                                  id="catatan-{{ $progress->id }}"
                                                  rows="3"
                                                  class="w-full border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 px-4 py-2"
                                                  placeholder="Tambahkan catatan verifikasi..."></textarea>
                                        <p class="text-xs text-gray-500 mt-1">
                                            ℹ️ Catatan akan dilihat oleh kelompok
                                        </p>
                                    </div>

                                    <!-- Tombol Aksi -->
                                    <div class="flex gap-3">
                                        <button type="button" 
                                                id="btn-approve-{{ $progress->id }}"
                                                onclick="prepareApprove{{ $progress->id }}()"
                                                class="flex-1 px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition duration-300 font-semibold flex items-center justify-center gap-2 shadow-md">
                                            <span class="text-lg">✅</span>
                                            <span>Setujui</span>
                                        </button>
                                        <button type="button" 
                                                onclick="prepareReject{{ $progress->id }}()"
                                                class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-300 font-semibold flex items-center justify-center gap-2 shadow-md">
                                            <span class="text-lg">❌</span>
                                            <span>Tolak</span>
                                        </button>
                                    </div>

                                    <input type="hidden" name="status" id="status-{{ $progress->id }}" value="">
                                </form>

                                <script>
                                function hitungPreview{{ $progress->id }}() {
                                    const volumeTarget = {{ $progress->volume_target }};
                                    const biayaSatuan = {{ $progress->biaya_satuan }};
                                    const volumeRealisasi = parseFloat(document.getElementById('volume-input-{{ $progress->id }}').value) || 0;
                                    
                                    // Hitung biaya realisasi
                                    const biayaRealisasi = volumeRealisasi * biayaSatuan;
                                    document.getElementById('preview-biaya-{{ $progress->id }}').textContent = 
                                        'Rp ' + biayaRealisasi.toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0});
                                    
                                    // Hitung persentase
                                    const persentase = volumeTarget > 0 ? (volumeRealisasi / volumeTarget) * 100 : 0;
                                    document.getElementById('preview-progress-{{ $progress->id }}').textContent = 
                                        persentase.toFixed(1) + '%';
                                }

                                function prepareApprove{{ $progress->id }}() {
                                    const container = document.getElementById('volume-container-{{ $progress->id }}');
                                    const volumeInput = document.getElementById('volume-input-{{ $progress->id }}');
                                    const catatanInput = document.getElementById('catatan-{{ $progress->id }}');
                                    const btnApprove = document.getElementById('btn-approve-{{ $progress->id }}');
                                    
                                    if (container.style.display === 'none') {
                                        // Tampilkan input volume realisasi
                                        container.style.display = 'block';
                                        volumeInput.setAttribute('required', 'required');
                                        
                                        // Catatan opsional untuk approve
                                        catatanInput.removeAttribute('required');
                                        document.getElementById('required-label-{{ $progress->id }}').style.display = 'none';
                                        
                                        // Fokus ke input volume
                                        volumeInput.focus();
                                        
                                        // Hitung preview
                                        hitungPreview{{ $progress->id }}();
                                        
                                        // Ubah tombol jadi submit
                                        btnApprove.innerHTML = '<span class="text-lg">✅</span><span>Konfirmasi Persetujuan</span>';
                                        btnApprove.classList.add('ring-4', 'ring-green-300');
                                    } else {
                                        // Validasi
                                        const volumeRealisasi = parseFloat(volumeInput.value);
                                        if (!volumeRealisasi || volumeRealisasi <= 0) {
                                            alert('❌ Volume realisasi harus diisi dan lebih dari 0!');
                                            volumeInput.focus();
                                            return false;
                                        }
                                        
                                        if (volumeRealisasi > {{ $progress->volume_target }}) {
                                            alert('❌ Volume realisasi tidak boleh melebihi target!\n\nTarget: {{ number_format($progress->volume_target, 2) }} {{ $progress->masterKegiatan->satuan }}\nAnda input: ' + volumeRealisasi.toFixed(2) + ' {{ $progress->masterKegiatan->satuan }}');
                                            volumeInput.focus();
                                            return false;
                                        }
                                        
                                        // Konfirmasi
                                        const biayaRealisasi = volumeRealisasi * {{ $progress->biaya_satuan }};
                                        const persentase = (volumeRealisasi / {{ $progress->volume_target }}) * 100;
                                        
                                        if (confirm('✅ KONFIRMASI PERSETUJUAN\n\n' +
                                            'Kegiatan: {{ $progress->masterKegiatan->nama_kegiatan }}\n' +
                                            'Volume Realisasi: ' + volumeRealisasi.toFixed(2) + ' {{ $progress->masterKegiatan->satuan }}\n' +
                                            'Biaya Realisasi: Rp ' + biayaRealisasi.toLocaleString('id-ID') + '\n' +
                                            'Progress: ' + persentase.toFixed(1) + '%\n\n' +
                                            'Lanjutkan persetujuan?')) {
                                            
                                            document.getElementById('status-{{ $progress->id }}').value = 'disetujui';
                                            document.getElementById('form-verifikasi-{{ $progress->id }}').submit();
                                        }
                                    }
                                }

                                function prepareReject{{ $progress->id }}() {
                                    const catatanInput = document.getElementById('catatan-{{ $progress->id }}');
                                    const container = document.getElementById('volume-container-{{ $progress->id }}');
                                    const volumeInput = document.getElementById('volume-input-{{ $progress->id }}');
                                    
                                    // Sembunyikan input volume realisasi
                                    container.style.display = 'none';
                                    volumeInput.removeAttribute('required');
                                    
                                    // Catatan wajib diisi saat reject
                                    catatanInput.setAttribute('required', 'required');
                                    document.getElementById('required-label-{{ $progress->id }}').style.display = 'inline';
                                    
                                    const catatan = catatanInput.value.trim();
                                    if (!catatan) {
                                        alert('❌ Catatan penolakan WAJIB diisi!');
                                        catatanInput.focus();
                                        return false;
                                    }
                                    
                                    if (!confirm('⚠️ KONFIRMASI PENOLAKAN\n\n' +
                                        'Kegiatan: {{ $progress->masterKegiatan->nama_kegiatan }}\n' +
                                        'Catatan: ' + catatan + '\n\n' +
                                        'Yakin ingin MENOLAK kegiatan ini?')) {
                                        return false;
                                    }
                                    
                                    document.getElementById('status-{{ $progress->id }}').value = 'ditolak';
                                    document.getElementById('form-verifikasi-{{ $progress->id }}').submit();
                                }
                                </script>

                            @elseif($progress->catatan_verifikasi || $progress->status_verifikasi != 'pending')
                                <div class="mt-3 p-4 rounded-lg {{ $progress->status_verifikasi == 'disetujui' ? 'bg-green-50 border-2 border-green-300' : 'bg-red-50 border-2 border-red-300' }}">
                                    <p class="text-sm font-semibold {{ $progress->status_verifikasi == 'disetujui' ? 'text-green-900' : 'text-red-900' }} mb-2">
                                        {{ $progress->status_verifikasi == 'disetujui' ? '✅ Status: Disetujui' : '❌ Status: Ditolak' }}
                                    </p>
                                    
                                    @if($progress->status_verifikasi == 'disetujui')
                                        <div class="mb-3 grid grid-cols-3 gap-2 text-xs">
                                            <div class="bg-white p-2 rounded">
                                                <p class="text-gray-600">Volume Realisasi:</p>
                                                <p class="font-bold text-green-700">{{ number_format($progress->volume_realisasi, 2) }} {{ $progress->masterKegiatan->satuan }}</p>
                                            </div>
                                            <div class="bg-white p-2 rounded">
                                                <p class="text-gray-600">Biaya Realisasi:</p>
                                                <p class="font-bold text-green-700">Rp {{ number_format($progress->biaya_realisasi, 0, ',', '.') }}</p>
                                            </div>
                                            <div class="bg-white p-2 rounded">
                                                <p class="text-gray-600">Progress:</p>
                                                <p class="font-bold text-green-700">{{ number_format($progress->persentase_fisik, 1) }}%</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($progress->catatan_verifikasi)
                                        <div class="bg-white bg-opacity-50 p-2 rounded mb-2">
                                            <p class="text-sm font-medium {{ $progress->status_verifikasi == 'disetujui' ? 'text-green-900' : 'text-red-900' }}">
                                                💬 Catatan Verifikasi:
                                            </p>
                                            <p class="text-sm {{ $progress->status_verifikasi == 'disetujui' ? 'text-green-800' : 'text-red-800' }} mt-1">
                                                {{ $progress->catatan_verifikasi }}
                                            </p>
                                        </div>
                                    @endif
                                    
                                    <p class="text-xs text-gray-600">
                                        👤 Diverifikasi oleh: <strong>{{ $progress->verifier->name }}</strong> • 
                                        📅 {{ $progress->verified_at->format('d M Y, H:i') }} WIB
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

    <!-- Tombol Export -->
    <div class="flex justify-end gap-3">
        <a href="{{ route('bpdas.progress-fisik.export.pdf', $kelompok) }}" 
           class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-300 font-semibold flex items-center gap-2 shadow-lg">
            <span class="text-lg">📄</span>
            <span>Export PDF</span>
        </a>
    </div>
</div>
@endsection