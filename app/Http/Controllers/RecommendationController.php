<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Analisis;

class RecommendationController extends Controller
{
    public function recommendation() {
        $hasil = Analisis::with(['aktivitasHarian', 'psikologisKlinis', 'ferScanner'])
                           ->find(session('hasil_id'));
        if (!$hasil) return redirect()->route('landing');
        return view('pages.recommendation.recommendation', compact('hasil'));
    }
}
