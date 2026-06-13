<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Analisis;

class ResultController extends Controller
{
    public function result() {
        $hasil = Analisis::with(['aktivitasHarian', 'psikologisKlinis', 'ferScanner'])
                           ->find(session('hasil_id'));
        if (!$hasil) return redirect()->route('landing');
        return view('pages.result.result', compact('hasil'));
    }
}
