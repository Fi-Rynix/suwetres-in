<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilAnalisis;

class FatigueController extends Controller
{
    public function landing() {
        return view('landing');
    }

    public function kuisioner() {
        return view('kuisioner');
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

    public function scan() {
        if (!session('data_kuisioner')) return redirect()->route('kuisioner');
        return view('scan');
    }

    public function loading() {
        return view('loading');
    }

    public function processFuzzy() {
        $data = session('data_kuisioner');
        if (!$data) return redirect()->route('kuisioner');

        $jam_tidur = $data['jam_tidur'];
        $jumlah_tugas = $data['jumlah_tugas'];
        $aktivitas = $data['aktivitas_organisasi'];
        $screen_time = $data['screen_time'];

        // Fuzzifikasi
        // Jam Tidur (Sedikit 0-5, Cukup 4-8, Banyak 7-12)
        $tidur_sedikit = $this->membershipTurun($jam_tidur, 4, 5);
        $tidur_cukup = $this->membershipSegitiga($jam_tidur, 4, 6, 8);
        $tidur_banyak = $this->membershipNaik($jam_tidur, 7, 8);

        // Jumlah Tugas (Sedikit 0-3, Sedang 2-6, Banyak 5-10)
        $tugas_sedikit = $this->membershipTurun($jumlah_tugas, 2, 3);
        $tugas_sedang = $this->membershipSegitiga($jumlah_tugas, 2, 4, 6);
        $tugas_banyak = $this->membershipNaik($jumlah_tugas, 5, 6);

        // Aktivitas Organisasi (Rendah 0-3, Sedang 2-6, Tinggi 5-10)
        $org_rendah = $this->membershipTurun($aktivitas, 2, 3);
        $org_sedang = $this->membershipSegitiga($aktivitas, 2, 4, 6);
        $org_tinggi = $this->membershipNaik($aktivitas, 5, 6);

        // Screen Time (Rendah 0-5, Sedang 4-9, Tinggi 8-15)
        $screen_rendah = $this->membershipTurun($screen_time, 4, 5);
        $screen_sedang = $this->membershipSegitiga($screen_time, 4, 6.5, 9);
        $screen_tinggi = $this->membershipNaik($screen_time, 8, 9);

        // Rules & Inferensi Sugeno Orde Nol
        $rules = [];
        
        // 1. IF Jam Tidur Sedikit AND Jumlah Tugas Banyak THEN Output = 80
        $rules[] = ['alpha' => min($tidur_sedikit, $tugas_banyak), 'z' => 80];
        
        // 2. IF Jam Tidur Cukup AND Aktivitas Organisasi Rendah THEN Output = 25
        $rules[] = ['alpha' => min($tidur_cukup, $org_rendah), 'z' => 25];

        // 3. IF Screen Time Tinggi AND Jumlah Tugas Sedang THEN Output = 50
        $rules[] = ['alpha' => min($screen_tinggi, $tugas_sedang), 'z' => 50];

        // 4. IF Jam Tidur Sedikit AND Aktivitas Organisasi Tinggi THEN Output = 80
        $rules[] = ['alpha' => min($tidur_sedikit, $org_tinggi), 'z' => 80];

        // 5. IF Jam Tidur Banyak AND Jumlah Tugas Sedikit THEN Output = 25
        $rules[] = ['alpha' => min($tidur_banyak, $tugas_sedikit), 'z' => 25];

        // Defuzzifikasi Weighted Average
        $pembilang = 0;
        $penyebut = 0;
        foreach($rules as $r) {
            $pembilang += ($r['alpha'] * $r['z']);
            $penyebut += $r['alpha'];
        }

        $nilai_fatigue = $penyebut == 0 ? 0 : round($pembilang / $penyebut, 2);

        // Interpretasi Output
        if ($nilai_fatigue <= 40) {
            $status = "Kelelahan Ringan";
        } elseif ($nilai_fatigue <= 70) {
            $status = "Kelelahan Sedang";
        } else {
            $status = "Kelelahan Tinggi";
        }

        // Simpan ke Database
        $hasil = HasilAnalisis::create([
            'jam_tidur' => $jam_tidur,
            'jumlah_tugas' => $jumlah_tugas,
            'aktivitas_organisasi' => $aktivitas,
            'screen_time' => $screen_time,
            'nilai_fatigue' => $nilai_fatigue,
            'status' => $status
        ]);

        session(['hasil_id' => $hasil->id]);
        return redirect()->route('result');
    }

    public function result() {
        $hasil = HasilAnalisis::find(session('hasil_id'));
        if (!$hasil) return redirect()->route('landing');
        return view('result', compact('hasil'));
    }

    public function recommendation() {
        $hasil = HasilAnalisis::find(session('hasil_id'));
        if (!$hasil) return redirect()->route('landing');
        return view('recommendation', compact('hasil'));
    }

    // --- Helper Functions Himpunan Fuzzy ---
    private function membershipTurun($x, $a, $b) {
        if ($x <= $a) return 1;
        if ($x >= $b) return 0;
        return ($b - $x) / ($b - $a);
    }

    private function membershipNaik($x, $a, $b) {
        if ($x <= $a) return 0;
        if ($x >= $b) return 1;
        return ($x - $a) / ($b - $a);
    }

    private function membershipSegitiga($x, $a, $b, $c) {
        if ($x <= $a || $x >= $c) return 0;
        if ($x > $a && $x <= $b) return ($x - $a) / ($b - $a);
        if ($x > $b && $x < $c) return ($c - $x) / ($c - $b);
        return 0;
    }
}
