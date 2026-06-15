<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Analisis;
use App\Models\AktivitasHarian;
use App\Models\PsikologisKlinis;
use App\Models\FerScanner;

class FuzzyController extends Controller
{
    private const FUZZY_WEIGHT = 0.8;
    private const FER_WEIGHT   = 0.2;

    public function processFuzzy() {
        $data = session('data_kuisioner');
        if (!$data) return redirect()->route('kuisioner');

        // === Daily Activities ===
        $jam_tidur   = $data['jam_tidur'];
        $screen_time = $data['screen_time'];

        // === 12 Clinical Psychological Inputs ===
        // Positive variables (high score = good condition)
        $kualitas_tidur  = $data['kualitas_tidur'];
        $kepuasan_hidup  = $data['kepuasan_hidup'];
        $regulasi_emosi  = $data['regulasi_emosi'];

        // Negative variables (high score = bad condition)
        $kelelahan_mental      = $data['kelelahan_mental'];
        $gangguan_konsentrasi  = $data['gangguan_konsentrasi'];
        $mood_rendah           = $data['mood_rendah'];
        $kecemasan             = $data['kecemasan'];
        $kewalahan             = $data['kewalahan'];
        $dampak_screen_time    = $data['dampak_screen_time'];
        $kehilangan_motivasi   = $data['kehilangan_motivasi'];
        $dampak_emosi          = $data['dampak_emosi'];
        $beban_mental          = $data['beban_mental'];
        $overthinking          = $data['overthinking'];
        $sulit_rileks          = $data['sulit_rileks'];
        $gejala_fisik_stres    = $data['gejala_fisik_stres'];

        // Process FER data first so we can use it in Fuzzy rules
        $fer = session('fer_result');
        $ferData = $this->processFERData($fer);

        // === Fuzzy Rules ===
        $tidur_buruk        = 11 - $kualitas_tidur;     // High = poor sleep quality
        $ketidakpuasan      = 11 - $kepuasan_hidup;     // High = life dissatisfaction
        $disregulasi_emosi  = 11 - $regulasi_emosi;     // High = poor emotion regulation

        // --- Kelelahan Mental ---
        $km_rendah = $this->membershipTurun($kelelahan_mental, 3, 5);
        $km_sedang = $this->membershipSegitiga($kelelahan_mental, 3, 5.5, 8);
        $km_tinggi = $this->membershipNaik($kelelahan_mental, 6, 8);

        // --- Gangguan Konsentrasi ---
        $gk_rendah = $this->membershipTurun($gangguan_konsentrasi, 3, 5);
        $gk_sedang = $this->membershipSegitiga($gangguan_konsentrasi, 3, 5.5, 8);
        $gk_tinggi = $this->membershipNaik($gangguan_konsentrasi, 6, 8);

        // --- Mood Rendah ---
        $mr_rendah = $this->membershipTurun($mood_rendah, 3, 5);
        $mr_sedang = $this->membershipSegitiga($mood_rendah, 3, 5.5, 8);
        $mr_tinggi = $this->membershipNaik($mood_rendah, 6, 8);

        // --- Kecemasan ---
        $kc_rendah = $this->membershipTurun($kecemasan, 3, 5);
        $kc_sedang = $this->membershipSegitiga($kecemasan, 3, 5.5, 8);
        $kc_tinggi = $this->membershipNaik($kecemasan, 6, 8);

        // --- Kewalahan ---
        $kw_rendah = $this->membershipTurun($kewalahan, 3, 5);
        $kw_sedang = $this->membershipSegitiga($kewalahan, 3, 5.5, 8);
        $kw_tinggi = $this->membershipNaik($kewalahan, 6, 8);

        // --- Kehilangan Motivasi ---
        $kmot_rendah = $this->membershipTurun($kehilangan_motivasi, 3, 5);
        $kmot_sedang = $this->membershipSegitiga($kehilangan_motivasi, 3, 5.5, 8);
        $kmot_tinggi = $this->membershipNaik($kehilangan_motivasi, 6, 8);

        // --- Dampak Emosi ---
        $de_rendah = $this->membershipTurun($dampak_emosi, 3, 5);
        $de_sedang = $this->membershipSegitiga($dampak_emosi, 3, 5.5, 8);
        $de_tinggi = $this->membershipNaik($dampak_emosi, 6, 8);

        // --- Beban Mental ---
        $bm_rendah = $this->membershipTurun($beban_mental, 3, 5);
        $bm_sedang = $this->membershipSegitiga($beban_mental, 3, 5.5, 8);
        $bm_tinggi = $this->membershipNaik($beban_mental, 6, 8);

        // --- Overthinking ---
        $ot_rendah = $this->membershipTurun($overthinking, 3, 5);
        $ot_sedang = $this->membershipSegitiga($overthinking, 3, 5.5, 8);
        $ot_tinggi = $this->membershipNaik($overthinking, 6, 8);

        // --- Sulit Rileks ---
        $sr_rendah = $this->membershipTurun($sulit_rileks, 3, 5);
        $sr_sedang = $this->membershipSegitiga($sulit_rileks, 3, 5.5, 8);
        $sr_tinggi = $this->membershipNaik($sulit_rileks, 6, 8);

        // --- Gejala Fisik Stres ---
        $gfs_rendah = $this->membershipTurun($gejala_fisik_stres, 3, 5);
        $gfs_sedang = $this->membershipSegitiga($gejala_fisik_stres, 3, 5.5, 8);
        $gfs_tinggi = $this->membershipNaik($gejala_fisik_stres, 6, 8);

        // --- Tidur Buruk (inverted from kualitas_tidur) ---
        $tb_rendah = $this->membershipTurun($tidur_buruk, 3, 5);
        $tb_sedang = $this->membershipSegitiga($tidur_buruk, 3, 5.5, 8);
        $tb_tinggi = $this->membershipNaik($tidur_buruk, 6, 8);

        // --- Ketidakpuasan Hidup (inverted from kepuasan_hidup) ---
        $kp_rendah = $this->membershipTurun($ketidakpuasan, 3, 5);
        $kp_sedang = $this->membershipSegitiga($ketidakpuasan, 3, 5.5, 8);
        $kp_tinggi = $this->membershipNaik($ketidakpuasan, 6, 8);

        // --- Disregulasi Emosi (inverted from regulasi_emosi) ---
        $dre_rendah = $this->membershipTurun($disregulasi_emosi, 3, 5);
        $dre_sedang = $this->membershipSegitiga($disregulasi_emosi, 3, 5.5, 8);
        $dre_tinggi = $this->membershipNaik($disregulasi_emosi, 6, 8);

        // --- Jam Tidur (Sedikit 0-5, Cukup 4-8, Banyak 7-12) ---
        $tidur_sedikit = $this->membershipTurun($jam_tidur, 4, 5);
        $tidur_cukup   = $this->membershipSegitiga($jam_tidur, 4, 6, 8);
        $tidur_banyak  = $this->membershipNaik($jam_tidur, 7, 8);

        // --- Screen Time (Rendah 0-5, Sedang 4-9, Tinggi 8-15) ---
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
            // Fallback ke proxy psikologis: mood_rendah + kecemasan (max 20, bagi 20)
            $mood_buruk = min(1.0, ($mood_rendah + $kecemasan) / 20);
        }

        // ============================================================
        // 3. Rules & Inferensi Sugeno Orde Nol (12 Rules)
        // ============================================================
        $rules = [];

        // === HIGH FATIGUE RULES (z >= 75) ===

        // Rule 1: IF Kelelahan Mental Tinggi AND Mood Rendah Tinggi THEN Fatigue = 92
        // Rationale: PHQ-9 core depression-fatigue comorbidity
        $rules[] = ['alpha' => min($km_tinggi, $mr_tinggi), 'z' => 92];

        // Rule 2: IF Kecemasan Tinggi AND Kewalahan Tinggi THEN Fatigue = 88
        // Rationale: GAD-7 anxiety + DASS-21 stress overwhelm
        $rules[] = ['alpha' => min($kc_tinggi, $kw_tinggi), 'z' => 88];

        // Rule 3: IF Tidur Buruk Tinggi AND Gangguan Konsentrasi Tinggi THEN Fatigue = 85
        // Rationale: Sleep-cognitive performance link (PSQI ↔ PHQ-9 item 7)
        $rules[] = ['alpha' => min($tb_tinggi, $gk_tinggi), 'z' => 85];

        // Rule 4: IF Kehilangan Motivasi Tinggi AND Beban Mental Tinggi THEN Fatigue = 82
        // Rationale: Burnout syndrome (amotivation + mental overload)
        $rules[] = ['alpha' => min($kmot_tinggi, $bm_tinggi), 'z' => 82];

        // Rule 5: IF Mood Buruk (FER) AND Dampak Emosi Tinggi THEN Fatigue = 78
        // Rationale: Emotional exhaustion validated by facial expression
        $rules[] = ['alpha' => min($mood_buruk, $de_tinggi), 'z' => 78];

        // Rule 6: IF Disregulasi Emosi Tinggi AND Kecemasan Tinggi THEN Fatigue = 75
        // Rationale: DERS + GAD link — poor regulation amplifies anxiety
        $rules[] = ['alpha' => min($dre_tinggi, $kc_tinggi), 'z' => 75];

        // === MID FATIGUE RULES (z 45-60) ===

        // Rule 7: IF Kelelahan Mental Sedang AND Kewalahan Sedang THEN Fatigue = 55
        // Rationale: Mid-range fatigue accumulation
        $rules[] = ['alpha' => min($km_sedang, $kw_sedang), 'z' => 55];

        // Rule 8: IF Gangguan Konsentrasi Sedang AND Beban Mental Sedang THEN Fatigue = 50
        // Rationale: Mid-range cognitive burden
        $rules[] = ['alpha' => min($gk_sedang, $bm_sedang), 'z' => 50];

        // === LOW FATIGUE RULES (z <= 30) ===

        // Rule 9: IF Tidur Buruk Rendah AND Kelelahan Mental Rendah THEN Fatigue = 25
        // Rationale: Good sleep + mental freshness = good recovery
        $rules[] = ['alpha' => min($tb_rendah, $km_rendah), 'z' => 25];

        // Rule 10: IF Mood Baik (1 - Mood Buruk) AND Ketidakpuasan Rendah THEN Fatigue = 20
        // Rationale: Positive emotional outlook + life satisfaction
        $rules[] = ['alpha' => min(1 - $mood_buruk, $kp_rendah), 'z' => 20];

        // Rule 11: IF Kecemasan Rendah AND Disregulasi Emosi Rendah THEN Fatigue = 15
        // Rationale: Emotional stability and calm state
        $rules[] = ['alpha' => min($kc_rendah, $dre_rendah), 'z' => 15];

        // Rule 12: IF Tidur Buruk Rendah AND Beban Mental Rendah THEN Fatigue = 10
        // Rationale: Fully rested and unburdened
        $rules[] = ['alpha' => min($tb_rendah, $bm_rendah), 'z' => 10];

        // Rule 13: IF Overthinking Tinggi AND Sulit_Rileks Tinggi THEN Fatigue = 85
        $rules[] = ['alpha' => min($ot_tinggi, $sr_tinggi), 'z' => 85];

        // Rule 14: IF Gejala_Fisik_Stres Tinggi AND Kecemasan Tinggi THEN Fatigue = 90
        $rules[] = ['alpha' => min($gfs_tinggi, $kc_tinggi), 'z' => 90];

        // 4. Defuzzifikasi Weighted Average
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
        // BAGIAN 2: FER STRESS SCORING (SUPPORTING - 20%)
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

        // Simpan ke 4 tabel dalam 1 transaction (atomicity)
        $analisisId = DB::transaction(function () use (
            $jam_tidur, $screen_time,
            $kualitas_tidur, $kepuasan_hidup, $regulasi_emosi,
            $kelelahan_mental, $gangguan_konsentrasi, $mood_rendah,
            $kecemasan, $kewalahan, $dampak_screen_time,
            $kehilangan_motivasi, $dampak_emosi, $beban_mental,
            $overthinking, $sulit_rileks, $gejala_fisik_stres,
            $nilai_fatigue, $status,
            $ferData, $finalScore, $finalStatus
        ) {
            // 1. Parent (analisis) — output + meta
            $analisis = Analisis::create([
                'nilai_fatigue'        => $nilai_fatigue,
                'status'               => $status,
                'fer_stress_score'     => $ferData['fer_stress_score'],
                'fer_status'           => $ferData['fer_status'],
                'final_score'          => $finalScore,
                'final_status'         => $finalStatus,
                'fer_detected'         => $ferData['fer_detected'],
                'total_frames_analyzed'=> $ferData['total_frames_analyzed'],
            ]);

            // 2. Child 1/3 — aktivitas_harian
            AktivitasHarian::create([
                'id'          => $analisis->id,
                'jam_tidur'   => $jam_tidur,
                'screen_time' => $screen_time,
            ]);

            // 3. Child 2/3 — psikologis_klinis
            PsikologisKlinis::create([
                'id'                   => $analisis->id,
                'kualitas_tidur'       => $kualitas_tidur,
                'kepuasan_hidup'       => $kepuasan_hidup,
                'regulasi_emosi'       => $regulasi_emosi,
                'kelelahan_mental'     => $kelelahan_mental,
                'gangguan_konsentrasi' => $gangguan_konsentrasi,
                'mood_rendah'          => $mood_rendah,
                'kecemasan'            => $kecemasan,
                'kewalahan'            => $kewalahan,
                'dampak_screen_time'   => $dampak_screen_time,
                'kehilangan_motivasi'  => $kehilangan_motivasi,
                'dampak_emosi'         => $dampak_emosi,
                'beban_mental'         => $beban_mental,
                'overthinking'         => $overthinking,
                'sulit_rileks'         => $sulit_rileks,
                'gejala_fisik_stres'   => $gejala_fisik_stres,
            ]);

            // 4. Child 3/3 — fer_scanner
            FerScanner::create([
                'id'                       => $analisis->id,
                'dominant_emotion'         => $ferData['dominant_emotion'],
                'dominant_emotion_score'   => $ferData['dominant_emotion_score'],
                'emotion_neutral'          => $ferData['emotions']['neutral'],
                'emotion_happy'            => $ferData['emotions']['happy'],
                'emotion_sad'              => $ferData['emotions']['sad'],
                'emotion_angry'            => $ferData['emotions']['angry'],
                'emotion_fearful'          => $ferData['emotions']['fearful'],
                'emotion_disgusted'        => $ferData['emotions']['disgusted'],
                'emotion_surprised'        => $ferData['emotions']['surprised'],
                'emotion_variance'         => $ferData['emotion_variance'],
                'negative_emotion_duration'=> $ferData['negative_emotion_duration'],
            ]);

            return $analisis->id;
        });

        session(['hasil_id' => $analisisId]);
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
