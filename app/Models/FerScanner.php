<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FerScanner extends Model
{
    protected $table = 'fer_scanner';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id',   // = analisis.id
        'dominant_emotion',
        'dominant_emotion_score',
        'emotion_neutral',
        'emotion_happy',
        'emotion_sad',
        'emotion_angry',
        'emotion_fearful',
        'emotion_disgusted',
        'emotion_surprised',
        'emotion_variance',
        'negative_emotion_duration',
    ];

    protected $casts = [
        'dominant_emotion_score'    => 'float',
        'emotion_neutral'           => 'float',
        'emotion_happy'             => 'float',
        'emotion_sad'               => 'float',
        'emotion_angry'             => 'float',
        'emotion_fearful'           => 'float',
        'emotion_disgusted'         => 'float',
        'emotion_surprised'         => 'float',
        'emotion_variance'          => 'float',
        'negative_emotion_duration' => 'float',
        'created_at'                => 'datetime',
    ];

    public function analisis(): BelongsTo
    {
        return $this->belongsTo(Analisis::class, 'id');
    }
}
