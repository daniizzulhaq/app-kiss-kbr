<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Kelompok - Sistem KBR')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
        .float-animation { animation: float 3s ease-in-out infinite; }

        @keyframes slideIn { from{opacity:0;transform:translateY(20px);} to{opacity:1;transform:translateY(0);} }
        .slide-in { animation: slideIn 0.5s ease-out forwards; }

        .leaf-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2322c55e' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        /* Sidebar overlay untuk mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 40;
        }
        
        .sidebar-overlay.active {
            display: block;
        }

        /* Sidebar mobile */
        #sidebar {
            transition: transform 0.3s ease-in-out;
        }

        @media (max-width: 768px) {
            #sidebar {
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                z-index: 50;
                transform: translateX(-100%);
            }
            
            #sidebar.active {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="flex min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-lime-50 leaf-pattern">

        <!-- Overlay untuk Mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="w-72 md:w-72 bg-gradient-to-b from-green-800 to-green-900 text-white flex flex-col shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-40 h-40 bg-green-700 rounded-full opacity-20 -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-green-600 rounded-full opacity-20 -ml-16 -mb-16"></div>

            <!-- Header Sidebar -->
            <div class="px-6 py-6 border-b border-green-700 relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center text-2xl shadow-lg">
                            👥
                        </div>
                        <div>
    @if(Auth::user()->role === 'bpdas')
        <h2 class="text-xl md:text-2xl font-bold">BPDAS</h2>
        <p class="text-xs text-green-200">Panel BPDAS</p>
    @else
        <h2 class="text-xl md:text-2xl font-bold">{{ Auth::user()->name }}</h2>
        <p class="text-xs text-green-200">Pengelola Kelompok</p>
    @endif
</div>
                    </div>
                    <!-- Tombol Close untuk Mobile -->
                    <button onclick="toggleSidebar()" class="md:hidden text-white hover:bg-green-700 p-2 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <p class="text-sm text-green-300 font-medium">Mondigi KBR</p>
            </div>

            <!-- Menu Sidebar -->
            <nav class="flex-1 px-4 py-6 space-y-1 relative z-10 overflow-y-auto">
                @if(Auth::user()->role === 'bpdas')
                    <!-- Menu BPDAS -->
                    <a href="{{ route('bpdas.dashboard') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 {{ request()->routeIs('bpdas.dashboard') ? 'bg-green-700 shadow-lg' : '' }}">
                        <span class="text-2xl">🏠</span>
                        <span class="ml-3 font-medium text-sm md:text-base">Dashboard</span>
                    </a>
                    <a href="{{ route('bpdas.permasalahan.index') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 {{ request()->routeIs('bpdas.permasalahan.*') ? 'bg-green-700 shadow-lg' : '' }}">
                        <span class="text-2xl">⚠️</span>
                        <span class="ml-3 font-medium text-sm md:text-base">Terima Permasalahan</span>
                    </a>
                    <a href="{{ route('bpdas.kelompok.index') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 {{ request()->routeIs('bpdas.kelompok.*') ? 'bg-green-700' : '' }}">
                        <span class="text-2xl">👥</span>
                        <span class="ml-3 font-medium text-sm md:text-base">Data Kelompok</span>
                    </a>
                    <a href="{{ route('bpdas.geotagging.index') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 {{ request()->routeIs('bpdas.geotagging.*') ? 'bg-green-700 shadow-lg' : '' }}">
                        <span class="text-2xl">📍</span>
                        <span class="ml-3 font-medium text-sm md:text-base">Data Lokasi Persemaian</span>
                    </a>
                    <a href="{{ route('bpdas.rencana-bibit.index') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300">
                        <span class="text-2xl">🌳</span>
                        <span class="ml-3 font-medium text-sm md:text-base">Rencana Bibit Kelompok</span>
                    </a>
                    <a href="{{ route('bpdas.realisasi-bibit.index') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300">
                        <span class="text-2xl">🌳</span>
                        <span class="ml-3 font-medium text-sm md:text-base">Realisasi Bibit Kelompok</span>
                    </a>
                    <a href="{{ route('bpdas.progress-fisik.monitoring') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 {{ request()->routeIs('bpdas.progress-fisik.*') ? 'bg-green-700' : '' }}">
                        <span class="text-2xl">📊</span>
                        <span class="ml-3 font-medium text-sm md:text-base">Progres Fisik Kelompok</span>
                    </a>
                      <a href="{{ route('bpdas.peta-lokasi.index') }}" 
                        class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 {{ request()->routeIs('bpdas.peta-lokasi.*') ? 'bg-green-700' : '' }}">
                        <span class="text-2xl">📍</span>
                        <span class="ml-3 font-medium text-sm md:text-base">Verifikasi Peta Lokasi</span>
                    </a>
                    <a href="" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 {{ request()->routeIs('kelompok.progress-fisik.*') ? 'bg-green-700' : '' }}">
                        <span class="text-2xl"></span>
                        <span class="ml-3 font-medium text-sm md:text-base">Data Peta Geotagging Kelompok</span>
                    </a>
                @else
                    <!-- Menu Kelompok -->
                    <a href="{{ route('kelompok.dashboard') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 {{ request()->routeIs('kelompok.dashboard') ? 'bg-green-700 shadow-lg' : '' }}">
                        <span class="text-2xl">🏠</span>
                        <span class="ml-3 font-medium text-sm md:text-base">Dashboard</span>
                    </a>
                    <a href="{{ route('kelompok.permasalahan.index') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 {{ request()->routeIs('kelompok.permasalahan.*') ? 'bg-green-700 shadow-lg' : '' }}">
                        <span class="text-2xl">⚠️</span>
                        <span class="ml-3 font-medium text-sm md:text-base">Permasalahan</span>
                    </a>
                    <a href="{{ route('kelompok.calon-lokasi.index') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 {{ request()->routeIs('kelompok.calon-lokasi.*') ? 'bg-green-700 shadow-lg' : '' }}">
                        <span class="text-2xl">📍</span>
                        <span class="ml-3 font-medium text-sm md:text-base">Calon Lokasi Persemaian</span>
                    </a>
                    <a href="{{ route('kelompok.data-kelompok.index') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 {{ request()->routeIs('kelompok.data-kelompok.*') ? 'bg-green-700' : '' }}">
                        <span class="text-2xl">👷‍♂️</span>
                        <span class="ml-3 font-medium text-sm md:text-base">Kelompok</span>
                    </a>
                    <a href="{{ route('kelompok.rencana-bibit.index') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300">
                        <span class="text-2xl">🌱</span>
                        <span class="ml-3 font-medium text-sm md:text-base">Rencana Bibit</span>
                    </a>
                    <a href="{{ route('kelompok.realisasi-bibit.index') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300">
                        <span class="text-2xl">🌳</span>
                        <span class="ml-3 font-medium text-sm md:text-base">Realisasi Bibit</span>
                    </a>
                    <a href="{{ route('kelompok.progress-fisik.index') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 {{ request()->routeIs('kelompok.progress-fisik.*') ? 'bg-green-700' : '' }}">
                        <span class="text-2xl">📈</span>
                        <span class="ml-3 font-medium text-sm md:text-base">Progres Fisik</span>
                    </a>
                     <a href="{{ route('kelompok.peta-lokasi.index') }}" 
   class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 {{ request()->routeIs('kelompok.peta-lokasi.*') ? 'bg-green-700' : '' }}">
    <span class="text-2xl">📍</span>
    <span class="ml-3 font-medium text-sm md:text-base"> Peta Lokasi Kelompok</span>
</a>
                     <a href="" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 {{ request()->routeIs('kelompok.progress-fisik.*') ? 'bg-green-700' : '' }}">
                        <span class="text-2xl"></span>
                        <span class="ml-3 font-medium text-sm md:text-base">Upload Peta Geotagging</span>
                    </a>
                @endif
            </nav>

            <!-- Logout -->
            <div class="px-4 py-4 border-t border-green-700 relative z-10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-green-700 rounded-xl hover:bg-green-600 transition-all duration-300 font-medium shadow-lg hover:shadow-xl text-sm md:text-base">
                        <span class="text-xl mr-2">🚪</span>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 flex flex-col overflow-hidden">
            <!-- Mobile Header dengan Hamburger -->
            <header class="md:hidden bg-white shadow-md px-4 py-3 flex items-center justify-between sticky top-0 z-30">
                <button onclick="toggleSidebar()" class="text-green-800 hover:bg-green-50 p-2 rounded-lg transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-lg font-bold text-green-800">
                    @if(Auth::user()->role === 'bpdas')
                        BPDAS Dashboard
                    @else
                        Kelompok Dashboard
                    @endif
                </h1>
                <div class="w-10"></div> <!-- Spacer untuk balance -->
            </header>

            <!-- Content Area -->
            <div class="flex-1 p-4 md:p-8 overflow-y-auto">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- JavaScript untuk Toggle Sidebar -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            
            // Prevent body scroll when sidebar is open on mobile
            if (sidebar.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        // Close sidebar when clicking a link on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('#sidebar a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        toggleSidebar();
                    }
                });
            });
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    </script>
</body>
</html>