<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Sistem Pertanian</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .farm-gradient {
            background: linear-gradient(135deg, #0f766e 0%, #059669 50%, #10b981 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        .farm-input:focus {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }
        .farm-button {
            position: relative;
            overflow: hidden;
        }
        .farm-button::before {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            width: 0; height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.25);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        .farm-button:hover::before {
            width: 300px;
            height: 300px;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .floating {
            animation: float 3s ease-in-out infinite;
        }
        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
    </style>
</head>

<body class="antialiased">

<div class="farm-gradient min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">

    <!-- Dekorasi Kiri -->
    <div class="absolute top-20 left-10 opacity-20 floating">
        <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"/>
        </svg>
    </div>

    <!-- Dekorasi Kanan -->
    <div class="absolute bottom-20 right-10 opacity-20 floating" style="animation-delay: 1s;">
        <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z"/>
        </svg>
    </div>

    <div class="max-w-md w-full space-y-8 relative z-10">

        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-20 w-20 bg-white rounded-3xl shadow-2xl flex items-center justify-center mb-6 transform hover:scale-110 transition">
                <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <h2 class="text-4xl font-extrabold text-white tracking-tight">
                🌱 Daftar Akun Baru
            </h2>
            <p class="text-green-100 text-sm">Bergabung dengan Sistem Pertanian Modern</p>
        </div>

        <div class="glass-effect rounded-3xl shadow-2xl p-8 space-y-6">

            <!-- FORM REGISTER -->
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">👤 Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="farm-input w-full px-4 py-3.5 border border-green-300 rounded-xl bg-green-50/50 focus:ring-2 focus:ring-green-500">
                    @error('name') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">📧 Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="farm-input w-full px-4 py-3.5 border border-green-300 rounded-xl bg-green-50/50 focus:ring-2 focus:ring-green-500">
                    @error('email') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">🔒 Kata Sandi</label>
                    <input type="password" name="password" required
                           class="farm-input w-full px-4 py-3.5 border border-green-300 rounded-xl bg-green-50/50 focus:ring-2 focus:ring-green-500">
                    @error('password') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">🔁 Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" required
                           class="farm-input w-full px-4 py-3.5 border border-green-300 rounded-xl bg-green-50/50 focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">🧩 Daftar Sebagai</label>
                    <select name="role" required
                            class="farm-input w-full px-4 py-3.5 border border-green-300 rounded-xl bg-green-50/50 focus:ring-2 focus:ring-green-500">
                        <option value="kelompok" {{ old('role')=='kelompok'?'selected':'' }}>Kelompok</option>
                        <option value="bpdas" {{ old('role')=='bpdas'?'selected':'' }}>BPDAS</option>
                    </select>
                    @error('role') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <!-- Tombol -->
                <button class="farm-button w-full py-3.5 text-center bg-green-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:bg-green-700 transition">
                    BUAT AKUN BARU
                </button>

            </form>

            <div class="pt-4 border-t border-green-200 text-center">
                <p class="text-sm text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-green-600 font-semibold hover:text-green-500 underline">
                        Masuk Sekarang
                    </a>
                </p>
            </div>

        </div>

        <p class="text-center text-xs text-green-100">
            © 2024 Sistem Pertanian. Semua hak dilindungi.
        </p>

    </div>

</div>

</body>
</html>
