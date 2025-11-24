<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - Sistem Pertanian</title>

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
        .success-message {
            color: #059669;
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
            <path d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H7V7a3 3 0 015.905-.75 1 1 0 001.937-.5A5.002 5.002 0 0010 2z"/>
        </svg>
    </div>

    <!-- Dekorasi Kanan -->
    <div class="absolute bottom-20 right-10 opacity-20 floating" style="animation-delay: 1s;">
        <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z"/>
        </svg>
    </div>

    <div class="max-w-md w-full space-y-8 relative z-10">

        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-20 w-20 bg-white rounded-3xl shadow-2xl flex items-center justify-center mb-6 transform hover:scale-110 transition">
                <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>

            <h2 class="text-4xl font-extrabold text-white tracking-tight">
                🔑 Reset Password
            </h2>
            <p class="text-green-100 text-sm mt-2">Atur ulang kata sandi akun Anda</p>
        </div>

        <div class="glass-effect rounded-3xl shadow-2xl p-8 space-y-6">

            <!-- Alert Success -->
            @if(session('status'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <div class="flex items-center">
                    <span class="text-2xl mr-3">✅</span>
                    <p class="text-sm text-green-800 font-semibold">{{ session('status') }}</p>
                </div>
            </div>
            @endif

            <!-- Alert Error -->
            @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex items-start">
                    <span class="text-2xl mr-3">⚠️</span>
                    <div class="text-sm text-red-800">
                        @foreach($errors->all() as $error)
                            <p class="font-semibold">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
                <div class="flex items-start">
                    <span class="text-xl mr-3">ℹ️</span>
                    <div class="text-xs text-blue-800">
                        <p class="font-semibold mb-1">Informasi:</p>
                        <p>Masukkan email/username Anda dan password baru untuk mereset kata sandi.</p>
                    </div>
                </div>
            </div>

            <!-- FORM RESET PASSWORD -->
            <form method="POST" action="{{ route('password.update.direct') }}" class="space-y-5">
                @csrf

                <!-- Email / Username -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        📧 Email atau Username
                    </label>
                    <input type="text" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required
                           autofocus
                           placeholder="Masukkan email atau username Anda"
                           class="farm-input w-full px-4 py-3.5 border border-green-300 rounded-xl bg-green-50/50 focus:ring-2 focus:ring-green-500">
                    @error('email') 
                        <p class="error-message">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Password Baru -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        🔒 Password Baru
                    </label>
                    <input type="password" 
                           name="password" 
                           required
                           placeholder="Masukkan password baru"
                           class="farm-input w-full px-4 py-3.5 border border-green-300 rounded-xl bg-green-50/50 focus:ring-2 focus:ring-green-500">
                    @error('password') 
                        <p class="error-message">{{ $message }}</p> 
                    @enderror
                    <p class="text-xs text-gray-500 mt-2">Minimal 8 karakter</p>
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        🔁 Konfirmasi Password Baru
                    </label>
                    <input type="password" 
                           name="password_confirmation" 
                           required
                           placeholder="Ketik ulang password baru"
                           class="farm-input w-full px-4 py-3.5 border border-green-300 rounded-xl bg-green-50/50 focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Tombol Reset -->
                <button type="submit" 
                        class="farm-button w-full py-3.5 text-center bg-green-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:bg-green-700 transition">
                    🔄 RESET PASSWORD
                </button>

            </form>

            <!-- Link ke Login -->
            <div class="pt-4 border-t border-green-200 text-center space-y-2">
                <p class="text-sm text-gray-600">
                    Ingat password Anda?
                    <a href="{{ route('login') }}" class="text-green-600 font-semibold hover:text-green-500 underline">
                        Kembali ke Login
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