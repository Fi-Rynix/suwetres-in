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

        // === Daily Activities ===
        $jam_tidur   = $data['jam_tidur'];
        $screen_time = $data['screen_time'];

        // === 10 Psychological inputs ===
        $fokus_belajar               = $data['fokus_belajar'];
        $kelelahan_setelah_istirahat = $data['kelelahan_setelah_istirahat'];
        $tekanan_tugas               = $data['tekanan_tugas'];
        $keseimbangan_hidup          = $data['keseimbangan_hidup'];
        $penurunan_produktivitas     = $data['penurunan_produktivitas'];
        $kecemasan_deadline          = $data['kecemasan_deadline'];
        $dampak_screen_time          = $data['dampak_screen_time'];
        $motivasi_kuliah             = $data['motivasi_kuliah'];
        $kelelahan_aktivitas         = $data['kelelahan_aktivitas'];
        $beban_mental                = $data['beban_mental'];

        // Optional legacy inputs
        $jumlah_tugas = $data['jumlah_tugas'] ?? null;
        $aktivitas    = $data['aktivitas_organisasi'] ?? null;

        // Process FER data first so we can use it in Fuzzy rules
        $fer = session('fer_result');
        $ferData = $this->processFERData($fer);

        // ============================================================
        // BAGIAN 1: FUZZY SUGENO (PRIMARY - 70%)
        // ============================================================

        // 1. Invert scales for Focus and Motivation
        // High difficulty focusing (Likert 10) -> Low focus (1)
        $fokus = 11 - $fokus_belajar; 
        // High lack of motivation (Likert 10) -> Low motivation (1)
        $motivasi = 11 - $motivasi_kuliah;

        $tekanan = $tekanan_tugas;
        $beban = $beban_mental;
        $kelelahan = $kelelahan_aktivitas;

        // 2. Fuzzifikasi
        // Fokus (Rendah 1-5, Sedang 3-8, Tinggi 6-10)
        $fokus_rendah = $this->membershipTurun($fokus, 3, 5);
        $fokus_sedang = $this->membershipSegitiga($fokus, 3, 5.5, 8);
        $fokus_tinggi = $this->membershipNaik($fokus, 6, 8);

        // Tekanan Akademik (Rendah 1-5, Sedang 3-8, Tinggi 6-10)
        $tekanan_rendah = $this->membershipTurun($tekanan, 3, 5);
        $tekanan_sedang = $this->membershipSegitiga($tekanan, 3, 5.5, 8);
        $tekanan_tinggi = $this->membershipNaik($tekanan, 6, 8);

        // Motivasi (Rendah 1-5, Sedang 3-8, Tinggi 6-10)
        $motivasi_rendah = $this->membershipTurun($motivasi, 3, 5);
        $motivasi_sedang = $this->membershipSegitiga($motivasi, 3, 5.5, 8);
        $motivasi_tinggi = $this->membershipNaik($motivasi, 6, 8);

        // Beban Mental (Rendah 1-5, Sedang 3-8, Tinggi 6-10)
        $beban_rendah = $this->membershipTurun($beban, 3, 5);
        $beban_sedang = $this->membershipSegitiga($beban, 3, 5.5, 8);
        $beban_tinggi = $this->membershipNaik($beban, 6, 8);

        // Jam Tidur (Sedikit 0-5, Cukup 4-8, Banyak 7-12)
        $tidur_sedikit = $this->membershipTurun($jam_tidur, 4, 5);
        $tidur_cukup   = $this->membershipSegitiga($jam_tidur, 4, 6, 8);
        $tidur_banyak  = $this->membershipNaik($jam_tidur, 7, 8);

        // Kelelahan Aktivitas (Rendah 1-5, Sedang 3-8, Tinggi 6-10)
        $kelelahan_rendah = $this->membershipTurun($kelelahan, 3, 5);
        $kelelahan_sedang = $this->membershipSegitiga($kelelahan, 3, 5.5, 8);
        $kelelahan_tinggi = $this->membershipNaik($kelelahan, 6, 8);

        // Screen Time (Rendah 0-5, Sedang 4-9, Tinggi 8-15)
        $screen_rendah = $this->membershipTurun($screen_time, 4, 5);
        $screen_sedang = $this->membershipSegitiga($screen_time, 4, 6.5, 9);
        $screen_tinggi = $this->membershipNaik($screen_time, 8, 9);

        // Mood Buruk (AI Facial Emotion integrated)
        if ($ferData['fer_detected']) {
            // Kombinasi emosi negatif: sad, angry, fearful, disgusted
            $mood_buruk = min(1.0, 
                $ferData['emotions']['sad'] + 
                $ferData['emotions']['angry'] + 
                $ferData['emotions']['fearful'] + 
                $ferData['emotions']['disgusted']
            );
        } else {
            // Fallback ke proxy psikologis: beban mental + kecemasan deadline (max 20, bagi 20)
            $mood_buruk = min(1.0, ($beban_mental + $kecemasan_deadline) / 20);
        }

        // Rules & Inferensi Sugeno Orde Nol
        $rules = [];

        // Rule 1: IF Fokus Rendah AND Tekanan Akademik Tinggi THEN Fatigue = 90
        $rules[] = ['alpha' => min($fokus_rendah, $tekanan_tinggi), 'z' => 90];

        // Rule 2: IF Motivasi Rendah AND Beban Mental Tinggi THEN Fatigue = 85
        $rules[] = ['alpha' => min($motivasi_rendah, $beban_tinggi), 'z' => 85];

        // Rule 3: IF Tidur Sedikit AND Kelelahan Aktivitas Tinggi THEN Fatigue = 80
        $rules[] = ['alpha' => min($tidur_sedikit, $kelelahan_tinggi), 'z' => 80];

        // Rule 4: IF Mood Buruk AND Screen Time Tinggi THEN Fatigue = 75
        $rules[] = ['alpha' => min($mood_buruk, $screen_tinggi), 'z' => 75];

        // Rule 5: IF Fokus Tinggi AND Motivasi Tinggi THEN Fatigue = 20
        $rules[] = ['alpha' => min($fokus_tinggi, $motivasi_tinggi), 'z' => 20];

        // Rule 6 (Complementary): IF Fokus Sedang AND Tekanan Akademik Sedang THEN Fatigue = 50
        $rules[] = ['alpha' => min($fokus_sedang, $tekanan_sedang), 'z' => 50];

        // Rule 7 (Complementary): IF Motivasi Sedang AND Beban Mental Sedang THEN Fatigue = 55
        $rules[] = ['alpha' => min($motivasi_sedang, $beban_sedang), 'z' => 55];

        // Rule 8 (Complementary): IF Tidur Cukup AND Kelelahan Aktivitas Rendah THEN Fatigue = 30
        $rules[] = ['alpha' => min($tidur_cukup, $kelelahan_rendah), 'z' => 30];

        // Rule 9 (Complementary): IF Mood Baik (1 - Mood Buruk) AND Screen Time Rendah THEN Fatigue = 15
        $rules[] = ['alpha' => min(1 - $mood_buruk, $screen_rendah), 'z' => 15];

        // Rule 10 (Complementary): IF Tidur Banyak AND Beban Mental Rendah THEN Fatigue = 10
        $rules[] = ['alpha' => min($tidur_banyak, $beban_rendah), 'z' => 10];

        // Defuzzifikasi Weighted Average
        $pembilang = 0;
        $penyebut  = 0;
        foreach($rules as $r) {
            $pembilang += ($r['alpha'] * $r['z']);
            $penyebut  += $r['alpha'];
        }

        $nilai_fatigue = $penyebut == 0 ? 0 : round($pembilang / $penyebut, 2);

        // Interpretasi Output Fatigue
        $status = $this->classifyFatigue($nilai_fatigue);

        // ============================================================
        // BAGIAN 2: FER STRESS SCORING (SUPPORTING - 30%)
        // ============================================================

        // Sudah diproses di awal $ferData

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
            'jam_tidur'   => $jam_tidur,
            'screen_time' => $screen_time,

            // Pertanyaan Psikologis Likert
            'fokus_belajar'               => $fokus_belajar,
            'kelelahan_setelah_istirahat' => $kelelahan_setelah_istirahat,
            'tekanan_tugas'               => $tekanan_tugas,
            'keseimbangan_hidup'          => $keseimbangan_hidup,
            'penurunan_produktivitas'     => $penurunan_produktivitas,
            'kecemasan_deadline'          => $kecemasan_deadline,
            'dampak_screen_time'          => $dampak_screen_time,
            'motivasi_kuliah'             => $motivasi_kuliah,
            'kelelahan_aktivitas'         => $kelelahan_aktivitas,
            'beban_mental'                => $beban_mental,

            // Backward compatibility
            'jumlah_tugas'         => $jumlah_tugas,
            'aktivitas_organisasi' => $aktivitas,

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
