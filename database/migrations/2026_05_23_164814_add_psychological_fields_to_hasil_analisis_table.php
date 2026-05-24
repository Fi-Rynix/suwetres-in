<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hasil_analisis', function (Blueprint $table) {
            $table->integer('jumlah_tugas')->nullable()->change();
            $table->integer('aktivitas_organisasi')->nullable()->change();

            $table->integer('fokus_belajar')->nullable();
            $table->integer('kelelahan_setelah_istirahat')->nullable();
            $table->integer('tekanan_tugas')->nullable();
            $table->integer('keseimbangan_hidup')->nullable();
            $table->integer('penurunan_produktivitas')->nullable();
            $table->integer('kecemasan_deadline')->nullable();
            $table->integer('dampak_screen_time')->nullable();
            $table->integer('motivasi_kuliah')->nullable();
            $table->integer('kelelahan_aktivitas')->nullable();
            $table->integer('beban_mental')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_analisis', function (Blueprint $table) {
            $table->integer('jumlah_tugas')->nullable(false)->change();
            $table->integer('aktivitas_organisasi')->nullable(false)->change();

            $table->dropColumn([
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
            ]);
        });
    }
};
