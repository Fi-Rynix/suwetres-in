<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Analisis extends Model
{
    protected $table = 'analisis';

    protected $fillable = [
        'nilai_fatigue',
        'status',
        'fer_stress_score',
        'fer_status',
        'final_score',
        'final_status',
        'fer_detected',
        'total_frames_analyzed',
    ];

    protected $casts = [
        'nilai_fatigue'           => 'float',
        'fer_stress_score'        => 'float',
        'final_score'             => 'float',
        'fer_detected'            => 'boolean',
        'total_frames_analyzed'   => 'integer',
    ];

    // ─── Relasi 1:1 ke child tables ───

    public function aktivitasHarian(): HasOne
    {
        return $this->hasOne(AktivitasHarian::class, 'id');
    }

    public function psikologisKlinis(): HasOne
    {
        return $this->hasOne(PsikologisKlinis::class, 'id');
    }

    public function ferScanner(): HasOne
    {
        return $this->hasOne(FerScanner::class, 'id');
    }
}
