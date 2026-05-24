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
            'jam_tidur' => 'required|numeric|min:0|max:12',
            'screen_time' => 'required|numeric|min:0|max:15',
            
            // 10 Pertanyaan Psikologis Likert 1-10
            'fokus_belajar' => 'required|integer|min:1|max:10',
            'kelelahan_setelah_istirahat' => 'required|integer|min:1|max:10',
            'tekanan_tugas' => 'required|integer|min:1|max:10',
            'keseimbangan_hidup' => 'required|integer|min:1|max:10',
            'penurunan_produktivitas' => 'required|integer|min:1|max:10',
            'kecemasan_deadline' => 'required|integer|min:1|max:10',
            'dampak_screen_time' => 'required|integer|min:1|max:10',
            'motivasi_kuliah' => 'required|integer|min:1|max:10',
            'kelelahan_aktivitas' => 'required|integer|min:1|max:10',
            'beban_mental' => 'required|integer|min:1|max:10',

            // Opsional (backward compatibility)
            'jumlah_tugas' => 'nullable|numeric|min:0|max:10',
            'aktivitas_organisasi' => 'nullable|numeric|min:0|max:10',
        ]);

        // Simpan sementara di session
        session(['data_kuisioner' => $validated]);
        return redirect()->route('scan');
    }
}
