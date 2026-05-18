<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilAnalisis extends Model
{
    protected $table = 'hasil_analisis';
    protected $fillable = [
        'jam_tidur',
        'jumlah_tugas',
        'aktivitas_organisasi',
        'screen_time',
        'nilai_fatigue',
        'status',
    ];
}
