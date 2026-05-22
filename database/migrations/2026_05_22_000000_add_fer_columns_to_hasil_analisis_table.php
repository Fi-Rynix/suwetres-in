<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambah kolom-kolom hasil Facial Expression Recognition (FER)
     * sebagai data pendukung perhitungan stress mahasiswa.
     * Bobot FER: 30% (pendukung) | Bobot Fuzzy: 70% (utama)
     */
    public function up(): void
    {
        Schema::table('hasil_analisis', function (Blueprint $table) {
            // === FER Score & Klasifikasi ===
            $table->float('fer_stress_score')->nullable()->after('nilai_fatigue');
            $table->string('fer_status')->nullable()->after('fer_stress_score');

            // === Final Combined Score (Fuzzy 70% + FER 30%) ===
            $table->float('final_score')->nullable()->after('fer_status');
            $table->string('final_status')->nullable()->after('final_score');

            // === Detail Emosi Dominan ===
            $table->string('dominant_emotion')->nullable()->after('final_status');
            $table->float('dominant_emotion_score')->nullable()->after('dominant_emotion');

            // === Rincian 7 Probabilitas Emosi (rata-rata selama 5 detik scan) ===
            $table->float('emotion_neutral')->nullable()->after('dominant_emotion_score');
            $table->float('emotion_happy')->nullable()->after('emotion_neutral');
            $table->float('emotion_sad')->nullable()->after('emotion_happy');
            $table->float('emotion_angry')->nullable()->after('emotion_sad');
            $table->float('emotion_fearful')->nullable()->after('emotion_angry');
            $table->float('emotion_disgusted')->nullable()->after('emotion_fearful');
            $table->float('emotion_surprised')->nullable()->after('emotion_disgusted');

            // === Indikator Tambahan ===
            $table->float('emotion_variance')->nullable()->after('emotion_surprised'); // Stabilitas emosi
            $table->float('negative_emotion_duration')->nullable()->after('emotion_variance'); // Durasi emosi negatif (detik)
            $table->integer('total_frames_analyzed')->nullable()->after('negative_emotion_duration');

            // === Status scan (apakah berhasil deteksi wajah) ===
            $table->boolean('fer_detected')->default(false)->after('total_frames_analyzed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_analisis', function (Blueprint $table) {
            $table->dropColumn([
                'fer_stress_score',
                'fer_status',
                'final_score',
                'final_status',
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
            ]);
        });
    }
};
