<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </style>
</head>
<body class="font-sans antialiased">
    <div class="flex min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-lime-50 leaf-pattern">

        <!-- Sidebar -->
<aside class="w-72 bg-gradient-to-b from-green-800 to-green-900 text-white flex flex-col shadow-2xl relative overflow-hidden">
    <div class="absolute top-0 right-0 w-40 h-40 bg-green-700 rounded-full opacity-20 -mr-20 -mt-20"></div>
    <div class="absolute bottom-0 left-0 w-32 h-32 bg-green-600 rounded-full opacity-20 -ml-16 -mb-16"></div>

    <!-- Header Sidebar -->
    <div class="px-6 py-6 border-b border-green-700 relative z-10">
        <div class="flex items-center space-x-3 mb-2">
            <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center text-2xl shadow-lg">
                👥
            </div>
            <div>
                @if(Auth::user()->role === 'bpdas')
                    <h2 class="text-2xl font-bold">BPDAS</h2>
                    <p class="text-xs text-green-200">Panel BPDAS</p>
                @else
                    <h2 class="text-2xl font-bold">Kelompok Tani</h2>
                    <p class="text-xs text-green-200">Panel Kelompok</p>
                @endif
            </div>
        </div>
        <p class="text-sm text-green-300 mt-2 font-medium">Sistem KISS KBR</p>
    </div>

    <!-- Menu Sidebar -->
    <nav class="flex-1 px-4 py-6 space-y-1 relative z-10 overflow-y-auto">
        @if(Auth::user()->role === 'bpdas')
            <!-- Menu BPDAS -->
            <a href="{{ route('bpdas.dashboard') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 {{ request()->routeIs('bpdas.dashboard') ? 'bg-green-700 shadow-lg' : '' }}">
                <span class="text-2xl">🏠</span>
                <span class="ml-3 font-medium">Dashboard</span>
            </a>
            <a href="{{ route('bpdas.permasalahan.index') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 {{ request()->routeIs('bpdas.permasalahan.*') ? 'bg-green-700 shadow-lg' : '' }}">
                <span class="text-2xl">⚠️</span>
                <span class="ml-3 font-medium">Terima Permasalahan</span>
            </a>
            <!-- Untuk User Kelompok -->
<!-- Untuk BPDAS -->
<a href="{{ route('bpdas.kelompok.index') }}" 
   class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 {{ request()->routeIs('bpdas.kelompok.*') ? 'bg-green-700' : '' }}">
    <span class="text-2xl">👥</span>
    <span class="ml-3 font-medium">Data Kelompok</span>
</a>
           <a href="{{ route('bpdas.geotagging.index') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 {{ request()->routeIs('bpdas.geotagging.*') ? 'bg-green-700 shadow-lg' : '' }}">
    <span class="text-2xl">📍</span>
    <span class="ml-3 font-medium">Geotagging</span>
</a>
            <a href="{{ route('bpdas.rencana-bibit.index') }}" 
   <a href="#" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300">
                <span class="text-2xl">🌳</span>
                <span class="ml-3 font-medium">Rencana Bibit Kelompok</span>
            </a>
            
            <a href="{{ route('bpdas.realisasi-bibit.index') }}" class="...">
    <span class="text-2xl">🌳</span>
    <span class="ml-3 font-medium">Realisasi Bibit Kelompok</span>
</a>
        @else
            <!-- Menu Kelompok -->
            <a href="{{ route('kelompok.dashboard') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 {{ request()->routeIs('kelompok.dashboard') ? 'bg-green-700 shadow-lg' : '' }}">
                <span class="text-2xl">🏠</span>
                <span class="ml-3 font-medium">Dashboard</span>
            </a>
            <a href="{{ route('kelompok.permasalahan.index') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 {{ request()->routeIs('kelompok.permasalahan.*') ? 'bg-green-700 shadow-lg' : '' }}">
                <span class="text-2xl">⚠️</span>
                <span class="ml-3 font-medium">Permasalahan</span>
            </a>
            <a href="{{ route('kelompok.calon-lokasi.index') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 {{ request()->routeIs('kelompok.calon-lokasi.*') ? 'bg-green-700 shadow-lg' : '' }}">
                <span class="text-2xl">📍</span>
                <span class="ml-3 font-medium">Calon Lokasi</span>
            </a>
        
<!-- Untuk User Kelompok -->
            <a href="{{ route('kelompok.data-kelompok.index') }}" 
            class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 {{ request()->routeIs('kelompok.data-kelompok.*') ? 'bg-green-700' : '' }}">
                <span class="text-2xl">👷‍♂️</span>
                <span class="ml-3 font-medium">Kelompok</span>
            </a>
            <a href="{{ route('kelompok.rencana-bibit.index') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300">
                <span class="text-2xl">🌱</span>
                <span class="ml-3 font-medium">Rencana Bibit</span>
            </a>
        <a href="{{ route('kelompok.realisasi-bibit.index') }}" class="group flex items-center px-4 py-3 rounded-xl hover:bg-green-700 transition-all duration-300">
            <span class="text-2xl">🌳</span>
            <span class="ml-3 font-medium">Realisasi Bibit</span>
         </a>
        @endif
    </nav>

    <!-- Logout -->
    <div class="px-4 py-4 border-t border-green-700 relative z-10">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-green-700 rounded-xl hover:bg-green-600 transition-all duration-300 font-medium shadow-lg hover:shadow-xl">
                <span class="text-xl mr-2">🚪</span>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>


        <!-- MAIN CONTENT -->
        <main class="flex-1 p-8 overflow-y-auto">
            @yield('content')
        </main>
    </div>
</body>
</html>
