@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/result.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/result.js') }}"></script>
@endsection

@section('content')
@php
    // === Helper: warna untuk fatigue (3-tier) ===
    $fatigueBg = 'var(--green)';
    $fatigueColor = 'var(--dark)';
    if ($hasil->status == 'Kelelahan Sedang') {
        $fatigueBg = 'var(--yellow)';
    } elseif ($hasil->status == 'Kelelahan Tinggi') {
        $fatigueBg = 'var(--primary)';
        $fatigueColor = 'var(--white)';
    }

    // === Helper: warna untuk stress (5-tier) ===
    $stressMap = [
        'Relaxed'          => ['bg' => 'var(--green)',     'color' => 'var(--dark)',  'emoji' => '😊'],
        'Mild Pressure'    => ['bg' => 'var(--secondary)', 'color' => 'var(--dark)',  'emoji' => '😐'],
        'Moderate Stress'  => ['bg' => 'var(--yellow)',    'color' => 'var(--dark)',  'emoji' => '😟'],
        'High Stress'      => ['bg' => 'var(--primary)',   'color' => 'var(--white)', 'emoji' => '😰'],
        'Severe Stress'    => ['bg' => 'var(--purple)',    'color' => 'var(--white)', 'emoji' => '😵'],
        'Tidak Terdeteksi' => ['bg' => '#999999',          'color' => 'var(--white)', 'emoji' => '❓'],
    ];
    $ferStyle = $stressMap[$hasil->fer_status ?? 'Tidak Terdeteksi'] ?? $stressMap['Tidak Terdeteksi'];
    $finalStyle = $stressMap[$hasil->final_status ?? 'Tidak Terdeteksi'] ?? $stressMap['Tidak Terdeteksi'];

    // === Helper: emoji untuk emosi dominan ===
    $emotionEmoji = [
        'neutral'   => '😐',
        'happy'     => '😊',
        'sad'       => '😢',
        'angry'     => '😠',
        'fearful'   => '😨',
        'disgusted' => '🤢',
        'surprised' => '😲',
    ];

    // === List 7 emosi untuk loop ===
    $emotionList = [
        ['key' => 'happy',     'label' => 'HAPPY',     'value' => $hasil->emotion_happy,     'color' => 'var(--green)'],
        ['key' => 'neutral',   'label' => 'NEUTRAL',   'value' => $hasil->emotion_neutral,   'color' => '#AAAAAA'],
        ['key' => 'surprised', 'label' => 'SURPRISED', 'value' => $hasil->emotion_surprised, 'color' => 'var(--secondary)'],
        ['key' => 'sad',       'label' => 'SAD',       'value' => $hasil->emotion_sad,       'color' => '#3366FF'],
        ['key' => 'fearful',   'label' => 'FEARFUL',   'value' => $hasil->emotion_fearful,   'color' => 'var(--purple)'],
        ['key' => 'disgusted', 'label' => 'DISGUSTED', 'value' => $hasil->emotion_disgusted, 'color' => '#FF8800'],
        ['key' => 'angry',     'label' => 'ANGRY',     'value' => $hasil->emotion_angry,     'color' => 'var(--primary)'],
    ];
@endphp

<div style="margin-top: 1rem;">
    <div style="text-align: center; margin-bottom: 3rem;">
        <div class="neo-badge" style="background-color: var(--green); font-size: 1.1rem; padding: 0.5rem 1.5rem;">
            HASIL DIAGNOSIS STRESS DIHITUNG!
        </div>
        <h1 style="font-size: 2.8rem; margin-top: 1rem;">DASHBOARD STRESS SUWETRES.IN</h1>
    </div>

    <!-- ========================================================== -->
    <!-- HERO: FINAL SCORE (Fuzzy 70% + FER 30%) -->
    <!-- ========================================================== -->
    <div class="neo-box" style="background-color: {{ $finalStyle['bg'] }}; color: {{ $finalStyle['color'] }}; text-align: center; margin-bottom: 3rem; padding: 2.5rem;">
        <div style="font-size: 0.9rem; font-weight: 700; letter-spacing: 2px; opacity: 0.8;">
            FINAL STRESS SCORE (FUZZY 70% + FER 30%)
        </div>
        <h1 style="font-size: 6rem; margin: 0.5rem 0; line-height: 1; text-shadow: 4px 4px 0px var(--dark);">
            {{ number_format($hasil->final_score ?? $hasil->nilai_fatigue, 1) }}%
        </h1>
        <div style="background-color: var(--white); color: var(--dark); border: var(--border-width) solid var(--dark); box-shadow: 4px 4px 0 var(--dark); padding: 0.6rem 1.5rem; font-size: 1.3rem; font-weight: 700; text-transform: uppercase; transform: rotate(-1deg); display: inline-block; margin-top: 0.5rem;">
            {{ $finalStyle['emoji'] }} {{ $hasil->final_status ?? $hasil->status }}
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- DUA KOLOM: FATIGUE (Primary) vs FER (Supporting) -->
    <!-- ========================================================== -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: stretch; margin-bottom: 3rem;" class="result-grid">

        <!-- ============= BOX FATIGUE (Primary 70%) ============= -->
        <div class="neo-box" style="background-color: var(--white); padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 4px solid var(--dark); padding-bottom: 0.8rem; margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.4rem; margin: 0;">📊 FATIGUE ANALYSIS</h3>
                <span style="background: var(--yellow); border: 2px solid var(--dark); padding: 0.2rem 0.6rem; font-size: 0.75rem; font-weight: 700;">
                    PRIMARY 70%
                </span>
            </div>

            <!-- Score & Status -->
            <div style="background-color: {{ $fatigueBg }}; color: {{ $fatigueColor }}; border: 3px solid var(--dark); box-shadow: 4px 4px 0 var(--dark); padding: 1.5rem; text-align: center; margin-bottom: 1.5rem;">
                <div style="font-size: 0.85rem; font-weight: 700; letter-spacing: 1px;">FUZZY SUGENO SCORE</div>
                <div style="font-size: 3.5rem; font-weight: 700; line-height: 1; margin: 0.3rem 0;">
                    {{ number_format($hasil->nilai_fatigue, 1) }}%
                </div>
                <div style="background: var(--white); color: var(--dark); border: 2px solid var(--dark); padding: 0.3rem 0.8rem; display: inline-block; font-weight: 700; font-size: 0.95rem; text-transform: uppercase;">
                    {{ $hasil->status }}
                </div>
            </div>

            <!-- Detail Parameter -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.8rem;">
                <div style="background: #FFFDE5; border: 2px solid var(--dark); padding: 0.7rem; box-shadow: 3px 3px 0 var(--dark);">
                    <div style="font-size: 0.75rem; font-weight: 700; color: #555;">JAM TIDUR</div>
                    <div style="font-size: 1.3rem; font-weight: 700;">{{ $hasil->jam_tidur }} Jam</div>
                </div>
                <div style="background: #F0F8FF; border: 2px solid var(--dark); padding: 0.7rem; box-shadow: 3px 3px 0 var(--dark);">
                    <div style="font-size: 0.75rem; font-weight: 700; color: #555;">JUMLAH TUGAS</div>
                    <div style="font-size: 1.3rem; font-weight: 700;">{{ $hasil->jumlah_tugas }} Buah</div>
                </div>
                <div style="background: #FFF0F5; border: 2px solid var(--dark); padding: 0.7rem; box-shadow: 3px 3px 0 var(--dark);">
                    <div style="font-size: 0.75rem; font-weight: 700; color: #555;">ORGANISASI</div>
                    <div style="font-size: 1.3rem; font-weight: 700;">{{ $hasil->aktivitas_organisasi }} Jam</div>
                </div>
                <div style="background: #F5FFFA; border: 2px solid var(--dark); padding: 0.7rem; box-shadow: 3px 3px 0 var(--dark);">
                    <div style="font-size: 0.75rem; font-weight: 700; color: #555;">SCREEN TIME</div>
                    <div style="font-size: 1.3rem; font-weight: 700;">{{ $hasil->screen_time }} Jam</div>
                </div>
            </div>
        </div>

        <!-- ============= BOX FER (Supporting 30%) ============= -->
        <div class="neo-box" style="background-color: var(--white); padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 4px solid var(--dark); padding-bottom: 0.8rem; margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.4rem; margin: 0;">😟 FER STRESS ANALYSIS</h3>
                <span style="background: var(--secondary); border: 2px solid var(--dark); padding: 0.2rem 0.6rem; font-size: 0.75rem; font-weight: 700;">
                    SUPPORTING 30%
                </span>
            </div>

            @if ($hasil->fer_detected)
                <!-- Score & Status -->
                <div style="background-color: {{ $ferStyle['bg'] }}; color: {{ $ferStyle['color'] }}; border: 3px solid var(--dark); box-shadow: 4px 4px 0 var(--dark); padding: 1.5rem; text-align: center; margin-bottom: 1.5rem;">
                    <div style="font-size: 0.85rem; font-weight: 700; letter-spacing: 1px;">FACE-API STRESS SCORE</div>
                    <div style="font-size: 3.5rem; font-weight: 700; line-height: 1; margin: 0.3rem 0;">
                        {{ number_format($hasil->fer_stress_score, 1) }}%
                    </div>
                    <div style="background: var(--white); color: var(--dark); border: 2px solid var(--dark); padding: 0.3rem 0.8rem; display: inline-block; font-weight: 700; font-size: 0.95rem; text-transform: uppercase;">
                        {{ $ferStyle['emoji'] }} {{ $hasil->fer_status }}
                    </div>
                </div>

                <!-- Dominant Emotion -->
                <div style="background: #FFFDE5; border: 2px solid var(--dark); padding: 0.8rem; box-shadow: 3px 3px 0 var(--dark); margin-bottom: 1rem; text-align: center;">
                    <div style="font-size: 0.75rem; font-weight: 700; color: #555;">EMOSI DOMINAN</div>
                    <div style="font-size: 1.3rem; font-weight: 700;">
                        {{ $emotionEmoji[$hasil->dominant_emotion] ?? '❓' }}
                        {{ strtoupper($hasil->dominant_emotion ?? '-') }}
                        ({{ number_format(($hasil->dominant_emotion_score ?? 0) * 100, 1) }}%)
                    </div>
                </div>

                <!-- Indicator Cards -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.8rem;">
                    <div style="background: #F0F8FF; border: 2px solid var(--dark); padding: 0.7rem; box-shadow: 3px 3px 0 var(--dark);">
                        <div style="font-size: 0.75rem; font-weight: 700; color: #555;">STABILITAS</div>
                        <div style="font-size: 1.1rem; font-weight: 700;">
                            {{ ($hasil->emotion_variance ?? 0) > 0.3 ? '⚠️ Unstable' : '✅ Stable' }}
                        </div>
                    </div>
                    <div style="background: #FFF0F5; border: 2px solid var(--dark); padding: 0.7rem; box-shadow: 3px 3px 0 var(--dark);">
                        <div style="font-size: 0.75rem; font-weight: 700; color: #555;">DURASI NEGATIF</div>
                        <div style="font-size: 1.1rem; font-weight: 700;">
                            {{ number_format($hasil->negative_emotion_duration ?? 0, 1) }}s / 5s
                        </div>
                    </div>
                    <div style="background: #F5FFFA; border: 2px solid var(--dark); padding: 0.7rem; box-shadow: 3px 3px 0 var(--dark);">
                        <div style="font-size: 0.75rem; font-weight: 700; color: #555;">FRAMES</div>
                        <div style="font-size: 1.1rem; font-weight: 700;">{{ $hasil->total_frames_analyzed ?? 0 }}</div>
                    </div>
                    <div style="background: #FFFDE5; border: 2px solid var(--dark); padding: 0.7rem; box-shadow: 3px 3px 0 var(--dark);">
                        <div style="font-size: 0.75rem; font-weight: 700; color: #555;">MODEL</div>
                        <div style="font-size: 1.1rem; font-weight: 700;">Face-API.js</div>
                    </div>
                </div>
            @else
                <!-- FER Tidak Terdeteksi -->
                <div style="background-color: #999999; color: var(--white); border: 3px solid var(--dark); box-shadow: 4px 4px 0 var(--dark); padding: 2rem; text-align: center; margin-bottom: 1rem;">
                    <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">❓</div>
                    <div style="font-size: 1.1rem; font-weight: 700;">WAJAH TIDAK TERDETEKSI</div>
                    <div style="font-size: 0.85rem; margin-top: 0.5rem; opacity: 0.9;">
                        Skor akhir 100% dari Fuzzy Sugeno
                    </div>
                </div>
                <p style="font-size: 0.9rem; color: #555; font-weight: 600;">
                    Kamera tidak aktif atau wajah tidak ter-scan. Coba lagi dengan pencahayaan yang lebih baik untuk mendapatkan analisis FER yang akurat.
                </p>
            @endif
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- DETAIL EMOSI BREAKDOWN -->
    <!-- ========================================================== -->
    @if ($hasil->fer_detected)
        <div class="neo-box" style="background-color: var(--white); padding: 2rem; margin-bottom: 3rem;">
            <h3 style="font-size: 1.4rem; border-bottom: 4px solid var(--dark); padding-bottom: 0.8rem; margin-bottom: 1.5rem;">
                🎭 BREAKDOWN 7 EMOSI (RATA-RATA SELAMA SCAN)
            </h3>
            <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                @foreach ($emotionList as $emo)
                    @php $pct = ($emo['value'] ?? 0) * 100; @endphp
                    <div style="display: grid; grid-template-columns: 140px 1fr 70px; gap: 1rem; align-items: center;">
                        <span style="font-weight: 700; font-size: 0.9rem;">
                            {{ $emotionEmoji[$emo['key']] }} {{ $emo['label'] }}
                        </span>
                        <div style="height: 22px; background: #EEE; border: 2px solid var(--dark); box-shadow: 2px 2px 0 var(--dark);">
                            <div style="width: {{ $pct }}%; height: 100%; background: {{ $emo['color'] }}; transition: width 0.5s;"></div>
                        </div>
                        <span style="font-weight: 700; text-align: right; font-size: 0.9rem;">{{ number_format($pct, 1) }}%</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- ========================================================== -->
    <!-- DUMMY ANALYTICS / RULES INFO -->
    <!-- ========================================================== -->
    <h3 style="font-size: 1.6rem; margin-bottom: 1.5rem; text-align: left;">DETAIL PERHITUNGAN & DB TRACKING</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-bottom: 4rem;">

        <div class="neo-box" style="background-color: var(--white); margin: 0; padding: 1.5rem;">
            <h4 style="font-size: 1.1rem; border-bottom: 2px solid var(--dark); padding-bottom: 0.5rem; margin-bottom: 1rem;">
                FORMULA FINAL SCORE
            </h4>
            <p style="font-size: 0.9rem; margin-bottom: 0.5rem; font-weight: 600;">
                • Fuzzy Score: <b>{{ number_format($hasil->nilai_fatigue, 1) }}% × 0.7</b>
            </p>
            <p style="font-size: 0.9rem; margin-bottom: 0.5rem; font-weight: 600;">
                • FER Score: <b>{{ number_format($hasil->fer_stress_score ?? 0, 1) }}% × 0.3</b>
            </p>
            <p style="font-size: 0.9rem; margin: 0; font-weight: 600;">
                • <b>= Final: {{ number_format($hasil->final_score ?? $hasil->nilai_fatigue, 1) }}%</b>
            </p>
        </div>

        <div class="neo-box" style="background-color: var(--white); margin: 0; padding: 1.5rem;">
            <h4 style="font-size: 1.1rem; border-bottom: 2px solid var(--dark); padding-bottom: 0.5rem; margin-bottom: 1rem;">
                ATURAN YANG DIEVALUASI
            </h4>
            <p style="font-size: 0.9rem; margin-bottom: 0.5rem; font-weight: 600;">
                • Fuzzy Rules: <b>5 Aturan Sugeno</b>
            </p>
            <p style="font-size: 0.9rem; margin-bottom: 0.5rem; font-weight: 600;">
                • FER Model: <b>Face-API.js (TinyFaceDetector)</b>
            </p>
            <p style="font-size: 0.9rem; margin: 0; font-weight: 600;">
                • Emotion Network: <b>FaceExpressionNet</b>
            </p>
        </div>

        <div class="neo-box" style="background-color: var(--white); margin: 0; padding: 1.5rem;">
            <h4 style="font-size: 1.1rem; border-bottom: 2px solid var(--dark); padding-bottom: 0.5rem; margin-bottom: 1rem;">
                DB RECORD TRACKING
            </h4>
            <p style="font-size: 0.9rem; margin-bottom: 0.5rem; font-weight: 600;">
                • Record ID: <b>#{{ $hasil->id }}</b>
            </p>
            <p style="font-size: 0.9rem; margin-bottom: 0.5rem; font-weight: 600;">
                • Tersimpan: <b>{{ $hasil->created_at->format('d M Y H:i:s') }}</b>
            </p>
            <p style="font-size: 0.9rem; margin: 0; font-weight: 600; color: var(--primary);">
                • Status: <b>SAVED TO MYSQL</b>
            </p>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div style="display: flex; gap: 2rem; justify-content: center; margin-top: 2rem; flex-wrap: wrap;">
        <a href="{{ route('kuisioner') }}" class="neo-btn neo-btn-secondary" style="background-color: var(--yellow);">
            COBA LAGI
        </a>
        <a href="{{ route('recommendation') }}" class="neo-btn" style="background-color: var(--green); display: inline-flex; align-items: center; gap: 0.75rem;">
            LIHAT SOLUSI ANTI-STRESS AI
            <svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 1.25rem; height: 1.25rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
            </svg>
        </a>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .result-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
