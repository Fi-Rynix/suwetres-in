<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilAnalisis;

class RecommendationController extends Controller
{
    public function recommendation() {
        $hasil = HasilAnalisis::find(session('hasil_id'));
        if (!$hasil) return redirect()->route('landing');
        return view('pages.recommendation.recommendation', compact('hasil'));
    }
}
