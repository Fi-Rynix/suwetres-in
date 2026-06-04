<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ADDITIVE migration — adds 12 new clinical psychological fields
 * adapted from PHQ-9, GAD-7, DASS-21, PSQI, DERS, WHO-5.
 *
 * Old 10 psychological fields are KEPT (deprecated) for backward
 * compatibility. They will be removed in a future cleanup migration
 * once the new questionnaire is finalized.
 *
 * Deprecated columns (DO NOT USE in new code):
 *   fokus_belajar, kelelahan_setelah_istirahat, tekanan_tugas,
 *   keseimbangan_hidup, penurunan_produktivitas, kecemasan_deadline,
 *   motivasi_kuliah, kelelahan_aktivitas
 *
 * Reused columns (semantics changed — anchor scale updated):
 *   dampak_screen_time  — was "screen time affects rest quality"
 *                         now "screen time affects mental/emotional state"
 *   beban_mental        — was "mental burden lately"
 *                         now "mental load in last 7 days" (1=very light, 10=very heavy)
 *
 * New columns added:
 *   kualitas_tidur, kelelahan_mental, gangguan_konsentrasi,
 *   mood_rendah, kecemasan, kewalahan, kehilangan_motivasi,
 *   dampak_emosi, kepuasan_hidup, regulasi_emosi
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hasil_analisis', function (Blueprint $table) {
            // === NEW CLINICAL PSYCHOLOGICAL FIELDS ===
            // Grouped by polarity for clarity

            // --- Positive variables (high score = good condition) ---
            $table->integer('kualitas_tidur')->nullable()->comment('Sleep satisfaction (PSQI-adapted). 1=very dissatisfied, 10=very satisfied. POSITIVE.');
            $table->integer('kepuasan_hidup')->nullable()->comment('Life satisfaction (WHO-5). 1=very dissatisfied, 10=very satisfied. POSITIVE.');
            $table->integer('regulasi_emosi')->nullable()->comment('Emotion regulation (DERS-adapted). 1=very difficult, 10=very easy. POSITIVE.');

            // --- Negative variables (high score = bad condition) ---
            $table->integer('kelelahan_mental')->nullable()->comment('Mental fatigue (DASS-21). 1=not at all, 10=almost all the time. NEGATIVE.');
            $table->integer('gangguan_konsentrasi')->nullable()->comment('Concentration difficulty (PHQ-9 item 7). 1=not disturbed, 10=very disturbed. NEGATIVE.');
            $table->integer('mood_rendah')->nullable()->comment('Depressed mood (PHQ-2/PHQ-9). 1=not at all, 10=almost all the time. NEGATIVE.');
            $table->integer('kecemasan')->nullable()->comment('Anxiety (GAD-7). 1=not at all, 10=very often. NEGATIVE.');
            $table->integer('kewalahan')->nullable()->comment('Overwhelm (DASS-21 Stress). 1=not overwhelmed, 10=very overwhelmed. NEGATIVE.');
            $table->integer('kehilangan_motivasi')->nullable()->comment('Amotivation (Burnout). 1=never, 10=almost all the time. NEGATIVE.');
            $table->integer('dampak_emosi')->nullable()->comment('Emotional impact on productivity (PHQ-9 item 10). 1=no impact, 10=very impactful. NEGATIVE.');
        });
    }

    public function down(): void
    {
        Schema::table('hasil_analisis', function (Blueprint $table) {
            $table->dropColumn([
                'kualitas_tidur',
                'kepuasan_hidup',
                'regulasi_emosi',
                'kelelahan_mental',
                'gangguan_konsentrasi',
                'mood_rendah',
                'kecemasan',
                'kewalahan',
                'kehilangan_motivasi',
                'dampak_emosi',
            ]);
        });
    }
};
