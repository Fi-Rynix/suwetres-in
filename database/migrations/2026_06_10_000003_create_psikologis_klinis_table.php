<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Child 2/3 — Input psikologis klinis (Bagian B kuisioner, 15 variabel Likert 1-10).
 * Adaptasi dari PHQ-9, GAD-7, DASS-21, PSQI, DERS, WHO-5.
 * Relasi 1:1 dengan `analisis` via PK-to-PK + ON DELETE CASCADE.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('psikologis_klinis', function (Blueprint $table) {
            // PK = FK ke analisis.id
            $table->foreignId('id')
                  ->primary()
                  ->constrained('analisis')
                  ->cascadeOnDelete();

            // ─── Variabel Positif (skor tinggi = kondisi baik) ───
            $table->unsignedTinyInteger('kualitas_tidur');    // PSQI
            $table->unsignedTinyInteger('kepuasan_hidup');    // WHO-5
            $table->unsignedTinyInteger('regulasi_emosi');    // DERS

            // ─── Variabel Negatif (skor tinggi = kondisi buruk) ───
            $table->unsignedTinyInteger('kelelahan_mental');     // DASS-21
            $table->unsignedTinyInteger('gangguan_konsentrasi'); // PHQ-9 item 7
            $table->unsignedTinyInteger('mood_rendah');          // PHQ-2/PHQ-9
            $table->unsignedTinyInteger('kecemasan');            // GAD-7
            $table->unsignedTinyInteger('kewalahan');            // DASS-21 Stress
            $table->unsignedTinyInteger('dampak_screen_time');   // Custom
            $table->unsignedTinyInteger('kehilangan_motivasi');  // Burnout
            $table->unsignedTinyInteger('dampak_emosi');         // PHQ-9 item 10
            $table->unsignedTinyInteger('beban_mental');         // DASS-21
            $table->unsignedTinyInteger('overthinking');         // Custom
            $table->unsignedTinyInteger('sulit_rileks');         // Custom
            $table->unsignedTinyInteger('gejala_fisik_stres');   // Custom

            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('psikologis_klinis');
    }
};
