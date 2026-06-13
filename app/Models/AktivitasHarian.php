<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AktivitasHarian extends Model
{
    protected $table = 'aktivitas_harian';
    public $incrementing = false;   // PK = FK, tidak auto-increment
    protected $keyType = 'int';
    public $timestamps = false;     // hanya created_at, di-handle manual

    protected $fillable = [
        'id',                // = analisis.id
        'jam_tidur',
        'screen_time',
    ];

    protected $casts = [
        'jam_tidur'   => 'integer',
        'screen_time' => 'integer',
        'created_at'  => 'datetime',
    ];

    public function analisis(): BelongsTo
    {
        return $this->belongsTo(Analisis::class, 'id');
    }
}
