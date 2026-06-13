<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PsikologisKlinis extends Model
{
    protected $table = 'psikologis_klinis';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id',   // = analisis.id
        // Positif
        'kualitas_tidur',
        'kepuasan_hidup',
        'regulasi_emosi',
        // Negatif
        'kelelahan_mental',
        'gangguan_konsentrasi',
        'mood_rendah',
        'kecemasan',
        'kewalahan',
        'dampak_screen_time',
        'kehilangan_motivasi',
        'dampak_emosi',
        'beban_mental',
        'overthinking',
        'sulit_rileks',
        'gejala_fisik_stres',
    ];

    protected $casts = [
        'kualitas_tidur'       => 'integer',
        'kepuasan_hidup'       => 'integer',
        'regulasi_emosi'       => 'integer',
        'kelelahan_mental'     => 'integer',
        'gangguan_konsentrasi' => 'integer',
        'mood_rendah'          => 'integer',
        'kecemasan'            => 'integer',
        'kewalahan'            => 'integer',
        'dampak_screen_time'   => 'integer',
        'kehilangan_motivasi'  => 'integer',
        'dampak_emosi'         => 'integer',
        'beban_mental'         => 'integer',
        'overthinking'         => 'integer',
        'sulit_rileks'         => 'integer',
        'gejala_fisik_stres'   => 'integer',
        'created_at'           => 'datetime',
    ];

    public function analisis(): BelongsTo
    {
        return $this->belongsTo(Analisis::class, 'id');
    }
}
