<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilAnalisis extends Model
{
    protected $table = 'hasil_analisis';
    protected $fillable = [
        // === Input Kuisioner (Daily Activities) ===
        'jam_tidur',
        'screen_time',

        // === DEPRECATED: Legacy Psychological Fields ===
        // Kept for backward compatibility with existing data.
        // DO NOT USE in new code. Will be removed in future cleanup migration.
        'jumlah_tugas',
        'aktivitas_organisasi',
        'fokus_belajar',
        'kelelahan_setelah_istirahat',
        'tekanan_tugas',
        'keseimbangan_hidup',
        'penurunan_produktivitas',
        'kecemasan_deadline',
        // 'dampak_screen_time',   — reused in new schema (same column name, new semantics)
        // 'motivasi_kuliah',      — deprecated, replaced by kehilangan_motivasi
        'motivasi_kuliah',
        // 'kelelahan_aktivitas',  — deprecated, replaced by kelelahan_mental
        'kelelahan_aktivitas',
        // 'beban_mental',         — reused in new schema (same column name, new semantics)

        // === NEW: Clinical Psychological Assessment (PHQ-9/GAD-7/DASS-21 adapted) ===
        // Positive variables (high score = good condition)
        'kualitas_tidur',           // Sleep satisfaction (PSQI). 1-10
        'kepuasan_hidup',           // Life satisfaction (WHO-5). 1-10
        'regulasi_emosi',           // Emotion regulation (DERS). 1-10

        // Negative variables (high score = bad condition)
        'kelelahan_mental',         // Mental fatigue (DASS-21). 1-10
        'gangguan_konsentrasi',     // Concentration (PHQ-9). 1-10
        'mood_rendah',              // Depressed mood (PHQ-2/PHQ-9). 1-10
        'kecemasan',                // Anxiety (GAD-7). 1-10
        'kewalahan',                // Overwhelm (DASS-21 Stress). 1-10
        'dampak_screen_time',       // Digital impact (custom). 1-10
        'kehilangan_motivasi',      // Amotivation (Burnout). 1-10
        'dampak_emosi',             // Emotional impact (PHQ-9 item 10). 1-10
        'beban_mental',             // Mental load (DASS-21). 1-10
        'overthinking',             // Overthinking. 1-10. NEGATIVE.
        'sulit_rileks',             // Difficulty relaxing. 1-10. NEGATIVE.
        'gejala_fisik_stres',       // Physical stress symptoms. 1-10. NEGATIVE.

        // === Hasil Fuzzy Sugeno (Primary - 70%) ===
        'nilai_fatigue',
        'status',

        // === Hasil FER (Supporting - 30%) ===
        'fer_stress_score',
        'fer_status',

        // === Final Combined Score ===
        'final_score',
        'final_status',

        // === Detail FER ===
        'dominant_emotion',
        'dominant_emotion_score',
        'emotion_neutral',
        'emotion_happy',
        'emotion_sad',
        'emotion_angry',
        'emotion_fearful',
        'emotion_disgusted',
        'emotion_surprised',
        'emotion_variance',
        'negative_emotion_duration',
        'total_frames_analyzed',
        'fer_detected',
    ];

    protected $casts = [
        'fer_detected' => 'boolean',
        'nilai_fatigue' => 'float',
        'fer_stress_score' => 'float',
        'final_score' => 'float',
        'dominant_emotion_score' => 'float',
        'emotion_neutral' => 'float',
        'emotion_happy' => 'float',
        'emotion_sad' => 'float',
        'emotion_angry' => 'float',
        'emotion_fearful' => 'float',
        'emotion_disgusted' => 'float',
        'emotion_surprised' => 'float',
        'emotion_variance' => 'float',
        'negative_emotion_duration' => 'float',

        // === DEPRECATED: Legacy psychological casts ===
        'fokus_belajar' => 'integer',
        'kelelahan_setelah_istirahat' => 'integer',
        'tekanan_tugas' => 'integer',
        'keseimbangan_hidup' => 'integer',
        'penurunan_produktivitas' => 'integer',
        'kecemasan_deadline' => 'integer',
        'motivasi_kuliah' => 'integer',
        'kelelahan_aktivitas' => 'integer',

        // === NEW: Clinical psychological casts ===
        'kualitas_tidur' => 'integer',
        'kepuasan_hidup' => 'integer',
        'regulasi_emosi' => 'integer',
        'kelelahan_mental' => 'integer',
        'gangguan_konsentrasi' => 'integer',
        'mood_rendah' => 'integer',
        'kecemasan' => 'integer',
        'kewalahan' => 'integer',
        'dampak_screen_time' => 'integer',
        'kehilangan_motivasi' => 'integer',
        'dampak_emosi' => 'integer',
        'beban_mental' => 'integer',
        'overthinking' => 'integer',
        'sulit_rileks' => 'integer',
        'gejala_fisik_stres' => 'integer',
    ];
}
