<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilAnalisis;

class ResultController extends Controller
{
    public function result() {
        $hasil = HasilAnalisis::find(session('hasil_id'));
        if (!$hasil) return redirect()->route('landing');
        return view('pages.result.result', compact('hasil'));
    }
}
