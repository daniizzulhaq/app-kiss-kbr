<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the direct password reset form.
     */
    public function create(): View
    {
        return view('auth.reset-password-direct');
    }

    /**
     * Handle the direct password reset request (without email).
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input
        $validated = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'confirmed', 'min:8'],
        ], [
            'email.required' => 'Email atau username harus diisi',
            'password.required' => 'Password baru harus diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min' => 'Password minimal 8 karakter',
        ]);

        // Cari user berdasarkan email atau name (username)
        $user = User::where('email', $validated['email'])
                    ->orWhere('name', $validated['email'])
                    ->first();

        // Jika user tidak ditemukan
        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau username tidak ditemukan dalam sistem.']);
        }

        // Update password user
        $user->password = Hash::make($validated['password']);
        $user->save();

        // Redirect ke login dengan pesan sukses
        return redirect()->route('login')
            ->with('status', 'Password berhasil direset! Silakan login dengan password baru Anda.');
    }
}