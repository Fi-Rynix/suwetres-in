<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hasil_analisis', function (Blueprint $table) {
            $table->integer('overthinking')->nullable()->comment('Overthinking frequency (1=not at all, 10=almost all the time). NEGATIVE.');
            $table->integer('sulit_rileks')->nullable()->comment('Difficulty relaxing (1=not at all, 10=almost all the time). NEGATIVE.');
            $table->integer('gejala_fisik_stres')->nullable()->comment('Physical stress symptoms (1=not at all, 10=very often). NEGATIVE.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_analisis', function (Blueprint $table) {
            $table->dropColumn(['overthinking', 'sulit_rileks', 'gejala_fisik_stres']);
        });
    }
};
