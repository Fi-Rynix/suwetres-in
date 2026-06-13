<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Child 3/3 — Detail hasil scan FER (Facial Expression Recognition).
 * Snapshot agregat 5 detik scan dari Face-API.js.
 * Relasi 1:1 dengan `analisis` via PK-to-PK + ON DELETE CASCADE.
 *
 * Note: `fer_detected` & `total_frames_analyzed` ada di parent (`analisis`)
 *       untuk akses cepat tanpa JOIN. Tabel ini hanya emosi & metadata variance.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fer_scanner', function (Blueprint $table) {
            // PK = FK ke analisis.id
            $table->foreignId('id')
                  ->primary()
                  ->constrained('analisis')
                  ->cascadeOnDelete();

            // Emosi dominan
            $table->string('dominant_emotion', 20)->nullable();
            $table->decimal('dominant_emotion_score', 4, 3)->nullable(); // 0.000 - 1.000

            // Rata-rata 7 probabilitas emosi selama 5 detik scan
            $table->decimal('emotion_neutral',   4, 3)->nullable();
            $table->decimal('emotion_happy',     4, 3)->nullable();
            $table->decimal('emotion_sad',       4, 3)->nullable();
            $table->decimal('emotion_angry',     4, 3)->nullable();
            $table->decimal('emotion_fearful',   4, 3)->nullable();
            $table->decimal('emotion_disgusted', 4, 3)->nullable();
            $table->decimal('emotion_surprised', 4, 3)->nullable();

            // Metadata temporal
            $table->decimal('emotion_variance', 4, 3)->nullable();           // 0.000 - 1.000
            $table->decimal('negative_emotion_duration', 4, 2)->nullable(); // detik (0.00 - 5.00)

            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fer_scanner');
    }
};
