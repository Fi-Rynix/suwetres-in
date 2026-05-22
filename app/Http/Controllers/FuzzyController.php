<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilAnalisis;

class FuzzyController extends Controller
{
    /**
     * Bobot kombinasi skor akhir.
     * Fuzzy Sugeno = primary (analisis kelelahan dari aktivitas)
     * FER          = supporting (analisis stress dari ekspresi wajah)
     */
    private const FUZZY_WEIGHT = 0.7;
    private const FER_WEIGHT   = 0.3;

    public function processFuzzy() {
        $data = session('data_kuisioner');
        if (!$data) return redirect()->route('kuisioner');

        $jam_tidur    = $data['jam_tidur'];
        $jumlah_tugas = $data['jumlah_tugas'];
        $aktivitas    = $data['aktivitas_organisasi'];
        $screen_time  = $data['screen_time'];

        // ============================================================
        // BAGIAN 1: FUZZY SUGENO (PRIMARY - 70%)
        // ============================================================

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

        // Interpretasi Output Fatigue
        $status = $this->classifyFatigue($nilai_fatigue);

        // ============================================================
        // BAGIAN 2: FER STRESS SCORING (SUPPORTING - 30%)
        // ============================================================

        $fer = session('fer_result');
        $ferData = $this->processFERData($fer);

        // ============================================================
        // BAGIAN 3: COMBINED FINAL SCORE
        // ============================================================

        // Jika FER tidak terdeteksi, gunakan 100% Fuzzy
        if (!$ferData['fer_detected']) {
            $finalScore = $nilai_fatigue;
        } else {
            $finalScore = round(
                ($nilai_fatigue * self::FUZZY_WEIGHT) +
                ($ferData['fer_stress_score'] * self::FER_WEIGHT),
                2
            );
        }

        $finalStatus = $this->classifyStress($finalScore);

        // ============================================================
        // BAGIAN 4: SIMPAN KE DATABASE
        // ============================================================

        $hasil = HasilAnalisis::create([
            // Input kuisioner
            'jam_tidur'            => $jam_tidur,
            'jumlah_tugas'         => $jumlah_tugas,
            'aktivitas_organisasi' => $aktivitas,
            'screen_time'          => $screen_time,

            // Hasil Fuzzy (primary)
            'nilai_fatigue' => $nilai_fatigue,
            'status'        => $status,

            // Hasil FER (supporting)
            'fer_stress_score' => $ferData['fer_stress_score'],
            'fer_status'       => $ferData['fer_status'],

            // Final combined
            'final_score'  => $finalScore,
            'final_status' => $finalStatus,

            // Detail FER
            'dominant_emotion'          => $ferData['dominant_emotion'],
            'dominant_emotion_score'    => $ferData['dominant_emotion_score'],
            'emotion_neutral'           => $ferData['emotions']['neutral'],
            'emotion_happy'             => $ferData['emotions']['happy'],
            'emotion_sad'               => $ferData['emotions']['sad'],
            'emotion_angry'             => $ferData['emotions']['angry'],
            'emotion_fearful'           => $ferData['emotions']['fearful'],
            'emotion_disgusted'         => $ferData['emotions']['disgusted'],
            'emotion_surprised'         => $ferData['emotions']['surprised'],
            'emotion_variance'          => $ferData['emotion_variance'],
            'negative_emotion_duration' => $ferData['negative_emotion_duration'],
            'total_frames_analyzed'     => $ferData['total_frames_analyzed'],
            'fer_detected'              => $ferData['fer_detected'],
        ]);

        session(['hasil_id' => $hasil->id]);
        session()->forget('fer_result'); // Bersihkan FER session

        return redirect()->route('result');
    }

    // ================================================================
    // FER STRESS CALCULATION
    // ================================================================

    /**
     * Menghitung skor stress dari hasil FER (Face-API.js).
     *
     * Formula komposit:
     *   - Bobot emosi negatif (angry, fearful, sad, disgusted)
     *   - Penalti untuk neutral tinggi (flat affect = burnout)
     *   - Bonus untuk happy (mengurangi stress)
     *   - Bonus dari emotion variance (instabilitas = stress)
     *   - Bonus dari durasi emosi negatif
     *
     * Returns: array berisi semua data FER siap simpan ke DB.
     */
    private function processFERData($fer): array {
        // Default kalau FER tidak terdeteksi
        $default = [
            'fer_detected'              => false,
            'fer_stress_score'          => 0,
            'fer_status'                => 'Tidak Terdeteksi',
            'dominant_emotion'          => null,
            'dominant_emotion_score'    => 0,
            'emotions'                  => [
                'neutral'   => 0,
                'happy'     => 0,
                'sad'       => 0,
                'angry'     => 0,
                'fearful'   => 0,
                'disgusted' => 0,
                'surprised' => 0,
            ],
            'emotion_variance'          => 0,
            'negative_emotion_duration' => 0,
            'total_frames_analyzed'     => 0,
        ];

        if (!$fer || empty($fer['detected'])) {
            return $default;
        }

        $emotions = array_merge($default['emotions'], $fer['emotions'] ?? []);

        // Komponen 1: Bobot emosi (skala 0-100)
        // Emosi negatif menambah stress, happy menguranginya
        $emotionScore = (
            ($emotions['angry']     * 25) +   // Stress/frustasi tinggi
            ($emotions['fearful']   * 22) +   // Anxiety
            ($emotions['sad']       * 20) +   // Burnout/depresi
            ($emotions['disgusted'] * 12) +   // Ketidaknyamanan
            ($emotions['neutral']   * 10) +   // Flat affect (mild)
            ($emotions['surprised'] * 5)  +   // Shock/tegang ringan
            ($emotions['happy']     * -15)    // Pengurang stress
        );

        // Komponen 2: Variance emosi (instabilitas)
        // Variance 0-1, dikali 100 untuk skala stress
        $varianceScore = ($fer['emotion_variance'] ?? 0) * 30;

        // Komponen 3: Durasi emosi negatif (detik dari total scan)
        // Diasumsikan max scan 5 detik, jadi normalisasi /5 * 20
        $negativeDuration = $fer['negative_emotion_duration'] ?? 0;
        $negativeScore = min($negativeDuration / 5, 1) * 20;

        // Total stress score (dipotong di range 0-100)
        $ferStressScore = $emotionScore + $varianceScore + $negativeScore;
        $ferStressScore = max(0, min(100, $ferStressScore));
        $ferStressScore = round($ferStressScore, 2);

        return [
            'fer_detected'              => true,
            'fer_stress_score'          => $ferStressScore,
            'fer_status'                => $this->classifyStress($ferStressScore),
            'dominant_emotion'          => $fer['dominant_emotion'] ?? null,
            'dominant_emotion_score'    => $fer['dominant_emotion_score'] ?? 0,
            'emotions'                  => $emotions,
            'emotion_variance'          => $fer['emotion_variance'] ?? 0,
            'negative_emotion_duration' => $fer['negative_emotion_duration'] ?? 0,
            'total_frames_analyzed'     => $fer['total_frames_analyzed'] ?? 0,
        ];
    }

    /**
     * Klasifikasi 5-tier stress level (lebih granular dari fatigue).
     */
    private function classifyStress(float $score): string {
        if ($score <= 25)  return 'Relaxed';
        if ($score <= 40)  return 'Mild Pressure';
        if ($score <= 60)  return 'Moderate Stress';
        if ($score <= 75)  return 'High Stress';
        return 'Severe Stress';
    }

    /**
     * Klasifikasi 3-tier fatigue (sesuai original Fuzzy Sugeno).
     */
    private function classifyFatigue(float $score): string {
        if ($score <= 40) return 'Kelelahan Ringan';
        if ($score <= 70) return 'Kelelahan Sedang';
        return 'Kelelahan Tinggi';
    }

    // ================================================================
    // FUZZY MEMBERSHIP HELPERS
    // ================================================================

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
