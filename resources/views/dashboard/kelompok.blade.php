
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kelompok - Sistem KBR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .slide-in {
            animation: slideIn 0.5s ease-out forwards;
        }
        .leaf-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2322c55e' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="flex min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-lime-50 leaf-pattern">
        <!-- Sidebar -->
        <aside class="w-72 bg-gradient-to-b from-green-800 to-green-900 text-white flex flex-col shadow-2xl relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-green-700 rounded-full opacity-20 -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-green-600 rounded-full opacity-20 -ml-16 -mb-16"></div>
            
            <div class="px-6 py-6 border-b border-green-700 relative z-10">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center text-2xl shadow-lg">
                        👥
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">Kelompok Tani</h2>
                        <p class="text-xs text-green-200">Panel Kelompok</p>
                    </div>
                </div>
                <p class="text-sm text-green-300 mt-2 font-medium">Sistem KISS KBR</p>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1 relative z-10 overflow-y-auto">
                <a href="#" class="group flex items-center px-4 py-3 rounded-xl bg-green-700 shadow-lg">
                    <span class="text-2xl">🏠</span>
                    <span class="ml-3 font-medium">Dashboard</span>
                </a>
                
                <a href="#" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 hover:shadow-lg hover:translate-x-1">
                    <span class="text-2xl group-hover:scale-110 transition-transform">⚠️</span>
                    <span class="ml-3 font-medium">Permasalahan</span>
                </a>
                
                <a href="#" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 hover:shadow-lg hover:translate-x-1">
                    <span class="text-2xl group-hover:scale-110 transition-transform">📍</span>
                    <span class="ml-3 font-medium">Calon Lokasi</span>
                </a>
                
                <a href="#" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 hover:shadow-lg hover:translate-x-1">
                    <span class="text-2xl group-hover:scale-110 transition-transform">👥</span>
                    <span class="ml-3 font-medium">Kelompok</span>
                </a>
                
                <a href="#" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 hover:shadow-lg hover:translate-x-1">
                    <span class="text-2xl group-hover:scale-110 transition-transform">🌱</span>
                    <span class="ml-3 font-medium">Rencana Bibit</span>
                </a>
                
                <a href="#" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 hover:shadow-lg hover:translate-x-1">
                    <span class="text-2xl group-hover:scale-110 transition-transform">🌳</span>
                    <span class="ml-3 font-medium">Realisasi Bibit</span>
                </a>
            </nav>

            <div class="px-4 py-4 border-t border-green-700 relative z-10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center px-4 py-3 bg-green-700 rounded-xl hover:bg-green-600 transition-all duration-300 font-medium shadow-lg hover:shadow-xl">
                        <span class="text-xl mr-2">🚪</span>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 overflow-y-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-green-800 mb-2">Dashboard Kelompok</h1>
                        <p class="text-gray-600">Kelola data dan laporan kelompok tani Anda</p>
                    </div>
                    <div class="text-6xl float-animation">🌾</div>
                </div>
            </div>

            <!-- Welcome Card -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 shadow-2xl rounded-2xl p-8 mb-8 text-white relative overflow-hidden slide-in">
                <div class="absolute top-0 right-0 text-9xl opacity-10">👥</div>
                <div class="relative z-10">
                    <h2 class="text-3xl font-bold mb-3">Selamat Datang! 👋</h2>
                    <p class="text-xl mb-2">
                        Halo, <span class="font-bold text-green-100">{{ Auth::user()->name }}</span>
                    </p>
                    <p class="text-green-100">
                        Anda login sebagai <span class="font-semibold bg-green-500 px-3 py-1 rounded-full">Kelompok Tani</span>
                    </p>
                </div>
            </div>

            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Tugas Aktif -->
                <div class="bg-white p-6 rounded-2xl border-2 border-purple-200 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 relative overflow-hidden group slide-in">
                    <div class="absolute top-0 right-0 text-7xl opacity-5 group-hover:opacity-10 transition-opacity">📋</div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-bold text-lg text-gray-700">Tugas Aktif</h4>
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-2xl">📋</div>
                        </div>
                        <p class="text-5xl font-bold text-purple-700 mb-2">0</p>
                        <p class="text-sm text-gray-500">Tugas yang harus diselesaikan</p>
                    </div>
                </div>

                <!-- Laporan Terkirim -->
                <div class="bg-white p-6 rounded-2xl border-2 border-indigo-200 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 relative overflow-hidden group slide-in" style="animation-delay: 0.1s">
                    <div class="absolute top-0 right-0 text-7xl opacity-5 group-hover:opacity-10 transition-opacity">📊</div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-bold text-lg text-gray-700">Laporan Terkirim</h4>
                            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-2xl">📊</div>
                        </div>
                        <p class="text-5xl font-bold text-indigo-700 mb-2">0</p>
                        <p class="text-sm text-gray-500">Total laporan yang sudah dikirim</p>
                    </div>
                </div>

                <!-- Anggota -->
                <div class="bg-white p-6 rounded-2xl border-2 border-pink-200 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 relative overflow-hidden group slide-in" style="animation-delay: 0.2s">
                    <div class="absolute top-0 right-0 text-7xl opacity-5 group-hover:opacity-10 transition-opacity">👨‍🌾</div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-bold text-lg text-gray-700">Anggota</h4>
                            <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center text-2xl">👨‍🌾</div>
                        </div>
                        <p class="text-5xl font-bold text-pink-700 mb-2">0</p>
                        <p class="text-sm text-gray-500">Total anggota kelompok</p>
                    </div>
                </div>
            </div>

            <!-- Menu Fitur Utama -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 mb-8 slide-in" style="animation-delay: 0.3s">
                <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <span class="text-3xl mr-3">🎯</span>
                    Menu Utama
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Permasalahan -->
                    <a href="#" class="group bg-gradient-to-br from-red-50 to-red-100 hover:from-red-100 hover:to-red-200 p-6 rounded-xl transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border-2 border-red-200">
                        <div class="flex items-start space-x-4">
                            <div class="text-4xl group-hover:scale-110 transition-transform">⚠️</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg text-gray-800 mb-2">Permasalahan</h4>
                                <p class="text-sm text-gray-600">Laporkan permasalahan di lapangan</p>
                            </div>
                        </div>
                    </a>

                    <!-- Calon Lokasi -->
                    <a href="#" class="group bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 p-6 rounded-xl transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border-2 border-blue-200">
                        <div class="flex items-start space-x-4">
                            <div class="text-4xl group-hover:scale-110 transition-transform">📍</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg text-gray-800 mb-2">Calon Lokasi</h4>
                                <p class="text-sm text-gray-600">Tentukan lokasi untuk penanaman</p>
                            </div>
                        </div>
                    </a>

                    <!-- Kelompok -->
                    <a href="#" class="group bg-gradient-to-br from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 p-6 rounded-xl transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border-2 border-purple-200">
                        <div class="flex items-start space-x-4">
                            <div class="text-4xl group-hover:scale-110 transition-transform">👥</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg text-gray-800 mb-2">Kelompok</h4>
                                <p class="text-sm text-gray-600">Kelola data anggota kelompok</p>
                            </div>
                        </div>
                    </a>

                    <!-- Rencana Bibit -->
                    <a href="#" class="group bg-gradient-to-br from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 p-6 rounded-xl transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border-2 border-green-200">
                        <div class="flex items-start space-x-4">
                            <div class="text-4xl group-hover:scale-110 transition-transform">🌱</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg text-gray-800 mb-2">Rencana Bibit</h4>
                                <p class="text-sm text-gray-600">Rencanakan kebutuhan bibit</p>
                            </div>
                        </div>
                    </a>

                    <!-- Realisasi Bibit -->
                    <a href="#" class="group bg-gradient-to-br from-emerald-50 to-emerald-100 hover:from-emerald-100 hover:to-emerald-200 p-6 rounded-xl transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border-2 border-emerald-200">
                        <div class="flex items-start space-x-4">
                            <div class="text-4xl group-hover:scale-110 transition-transform">🌳</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg text-gray-800 mb-2">Realisasi Bibit</h4>
                                <p class="text-sm text-gray-600">Catat realisasi penanaman bibit</p>
                            </div>
                        </div>
                    </a>

                    <!-- Laporan -->
                    <a href="#" class="group bg-gradient-to-br from-yellow-50 to-yellow-100 hover:from-yellow-100 hover:to-yellow-200 p-6 rounded-xl transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border-2 border-yellow-200">
                        <div class="flex items-start space-x-4">
                            <div class="text-4xl group-hover:scale-110 transition-transform">📝</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg text-gray-800 mb-2">Laporan</h4>
                                <p class="text-sm text-gray-600">Lihat semua laporan kegiatan</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Info & Activity -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Tips & Info -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 slide-in" style="animation-delay: 0.4s">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <span class="text-2xl mr-2">💡</span>
                        Tips & Informasi
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-xl border border-green-200">
                            <div class="text-2xl">🌱</div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">Pastikan data bibit selalu update</p>
                                <p class="text-xs text-gray-500">Update rutin membantu perencanaan</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 p-3 bg-blue-50 rounded-xl border border-blue-200">
                            <div class="text-2xl">📍</div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">Laporkan lokasi dengan tepat</p>
                                <p class="text-xs text-gray-500">Gunakan GPS untuk akurasi lokasi</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 p-3 bg-yellow-50 rounded-xl border border-yellow-200">
                            <div class="text-2xl">⚠️</div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">Segera laporkan masalah</p>
                                <p class="text-xs text-gray-500">Deteksi dini mencegah kerugian</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 slide-in" style="animation-delay: 0.5s">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <span class="text-2xl mr-2">🕐</span>
                        Aktivitas Terbaru
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-xl">
                            <div class="text-2xl">🎉</div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">Selamat datang di sistem!</p>
                                <p class="text-xs text-gray-500">Mulai kelola data kelompok Anda</p>
                            </div>
                        </div>
                        <div class="text-center py-4 text-gray-400">
                            <p class="text-sm">Belum ada aktivitas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 slide-in" style="animation-delay: 0.6s">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="text-4xl">🌾</div>
                        <div>
                            <p class="text-sm text-gray-600">Sistem KISS KBR</p>
                            <p class="text-lg font-bold text-green-700">Versi 1.0.0</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Konservasi Berbasis Rakyat</p>
                        <p class="text-xs text-gray-500">Balai Pengelolaan DAS</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>