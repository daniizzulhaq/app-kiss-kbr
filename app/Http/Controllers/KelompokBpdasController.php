<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use Illuminate\Http\Request;

class KelompokBpdasController extends Controller
{
    public function index()
    {
        $kelompoks = Kelompok::with('user')->latest()->paginate(15);
        return view('bpdas.kelompok.index', compact('kelompoks'));
    }

    public function show(Kelompok $kelompok)
    {
        $kelompok->load('user');
        return view('bpdas.kelompok.show', compact('kelompok'));
    }
} 