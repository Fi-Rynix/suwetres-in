<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Parent table — hasil akhir analisis Fuzzy Sugeno + FER.
 * Berisi SEMUA output (skor & klasifikasi) + meta scan.
 * Kolom INPUT (kuisioner & FER detail) disimpan di 3 tabel child.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analisis', function (Blueprint $table) {
            $table->id();

            // === Output Fuzzy Sugeno (70%) ===
            $table->decimal('nilai_fatigue', 5, 2);      // 0.00 - 100.00
            $table->string('status', 32);                // "Kelelahan Ringan/Sedang/Tinggi"

            // === Output FER (30%) ===
            $table->decimal('fer_stress_score', 5, 2);   // 0.00 - 100.00
            $table->string('fer_status', 32);            // "Relaxed" / "Mild Pressure" / ...

            // === Output Final (gabungan 70:30) ===
            $table->decimal('final_score', 5, 2);
            $table->string('final_status', 32);

            // === Meta scan FER (untuk gating logic & ringkasan) ===
            $table->boolean('fer_detected')->default(false);
            $table->unsignedSmallInteger('total_frames_analyzed')->default(0);

            $table->timestamps();

            // Index untuk query time-series
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analisis');
    }
};
