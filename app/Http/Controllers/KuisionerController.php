<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KuisionerController extends Controller
{
    public function kuisioner() {
        return view('pages.kuisioner.kuisioner');
    }

    public function postKuisioner(Request $request) {
        $validated = $request->validate([
            // === Aktivitas Harian (slider 0-24, integer) ===
            'jam_tidur' => 'required|integer|min:0|max:24',
            'screen_time' => 'required|integer|min:0|max:24',
            
            // === 12 Clinical Psychological Questions (Likert 1-10) ===
            // Positive variables (high = good)
            'kualitas_tidur' => 'required|integer|min:1|max:10',
            'kepuasan_hidup' => 'required|integer|min:1|max:10',
            'regulasi_emosi' => 'required|integer|min:1|max:10',

            // Negative variables (high = bad)
            'kelelahan_mental' => 'required|integer|min:1|max:10',
            'gangguan_konsentrasi' => 'required|integer|min:1|max:10',
            'mood_rendah' => 'required|integer|min:1|max:10',
            'kecemasan' => 'required|integer|min:1|max:10',
            'kewalahan' => 'required|integer|min:1|max:10',
            'dampak_screen_time' => 'required|integer|min:1|max:10',
            'kehilangan_motivasi' => 'required|integer|min:1|max:10',
            'dampak_emosi' => 'required|integer|min:1|max:10',
            'beban_mental' => 'required|integer|min:1|max:10',
            'overthinking' => 'required|integer|min:1|max:10',
            'sulit_rileks' => 'required|integer|min:1|max:10',
            'gejala_fisik_stres' => 'required|integer|min:1|max:10',
        ]);

        // Simpan sementara di session
        session(['data_kuisioner' => $validated]);
        return redirect()->route('scan');
    }
}
