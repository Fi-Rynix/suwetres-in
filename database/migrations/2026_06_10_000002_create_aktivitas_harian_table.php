<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Child 1/3 — Input aktivitas harian (Bagian A kuisioner).
 * Relasi 1:1 dengan `analisis` via PK-to-PK + ON DELETE CASCADE.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aktivitas_harian', function (Blueprint $table) {
            // PK = FK ke analisis.id
            $table->foreignId('id')
                  ->primary()
                  ->constrained('analisis')
                  ->cascadeOnDelete();

            $table->unsignedTinyInteger('jam_tidur');     // 0-24
            $table->unsignedTinyInteger('screen_time');   // 0-24

            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aktivitas_harian');
    }
};
