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
            'jumlah_tugas' => 'required|numeric|min:0|max:10',
            'aktivitas_organisasi' => 'required|numeric|min:0|max:10',
            'screen_time' => 'required|numeric|min:0|max:15',
        ]);

        // Simpan sementara di session
        session(['data_kuisioner' => $validated]);
        return redirect()->route('scan');
    }
}
