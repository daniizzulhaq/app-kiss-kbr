<?php

namespace App\Http\Controllers;

use App\Models\Permasalahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermasalahanBpdasController extends Controller
{
    public function index()
    {
        $permasalahan = Permasalahan::with('kelompokUser')
            ->latest()
            ->paginate(10);

        return view('bpdas.permasalahan.index', compact('permasalahan'));
    }

    public function show(Permasalahan $permasalahan)
    {
        $permasalahan->load('kelompokUser', 'penangananBpdas');
        return view('bpdas.permasalahan.show', compact('permasalahan'));
    }

    public function terima(Permasalahan $permasalahan)
    {
        if ($permasalahan->status !== 'pending') {
            return redirect()->back()->with('error', 'Permasalahan sudah ditangani!');
        }

        $permasalahan->update([
            'status' => 'diterima',
            'ditangani_oleh' => Auth::id(),
            'ditangani_pada' => now(),
        ]);

        return redirect()->route('bpdas.permasalahan.show', $permasalahan)
            ->with('success', 'Permasalahan berhasil diterima!');
    }

    public function updateSolusi(Request $request, Permasalahan $permasalahan)
    {
        $validated = $request->validate([
            'solusi' => 'required|string',
            'status' => 'required|in:diterima,diproses,selesai,ditolak',
        ]);

        $permasalahan->update([
            'solusi' => $validated['solusi'],
            'status' => $validated['status'],
            'ditangani_oleh' => Auth::id(),
            'ditangani_pada' => now(),
        ]);

        return redirect()->route('bpdas.permasalahan.show', $permasalahan)
            ->with('success', 'Solusi berhasil disimpan!');
    }
}