<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilAnalisis extends Model
{
    protected $table = 'hasil_analisis';
    protected $fillable = [
        // === Input Kuisioner ===
        'jam_tidur',
        'jumlah_tugas',
        'aktivitas_organisasi',
        'screen_time',

        // === Psychological Fatigue Assessment ===
        'fokus_belajar',
        'kelelahan_setelah_istirahat',
        'tekanan_tugas',
        'keseimbangan_hidup',
        'penurunan_produktivitas',
        'kecemasan_deadline',
        'dampak_screen_time',
        'motivasi_kuliah',
        'kelelahan_aktivitas',
        'beban_mental',

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

        // === Psychological inputs ===
        'fokus_belajar' => 'integer',
        'kelelahan_setelah_istirahat' => 'integer',
        'tekanan_tugas' => 'integer',
        'keseimbangan_hidup' => 'integer',
        'penurunan_produktivitas' => 'integer',
        'kecemasan_deadline' => 'integer',
        'dampak_screen_time' => 'integer',
        'motivasi_kuliah' => 'integer',
        'kelelahan_aktivitas' => 'integer',
        'beban_mental' => 'integer',
    ];
}
