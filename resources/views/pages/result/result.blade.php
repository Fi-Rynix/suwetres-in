@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/result.css') }}">
@endsection

@section('scripts')
    <script defer src="{{ asset('js/result.js') }}"></script>
@endsection

@section('content')
@php
    // === 1. Calculate Mood & Depressive Score ===
    // mood_rendah (negative) + inverted kepuasan_hidup (positive → negative)
    $moodDepressiveAvg = ($hasil->mood_rendah + (11 - $hasil->kepuasan_hidup)) / 2;
    $moodDepressivePct = (($moodDepressiveAvg - 1) / 9) * 100;
    
    // === 2. Calculate Anxiety & Stress Score ===
    $anxietyStressAvg = ($hasil->kecemasan + $hasil->kewalahan + $hasil->sulit_rileks + $hasil->gejala_fisik_stres) / 4;
    $anxietyStressPct = (($anxietyStressAvg - 1) / 9) * 100;

    // === 3. Calculate Cognitive Fatigue Score ===
    $cognitiveFatigueAvg = ($hasil->kelelahan_mental + $hasil->gangguan_konsentrasi + $hasil->overthinking) / 3;
    $cognitiveFatiguePct = (($cognitiveFatigueAvg - 1) / 9) * 100;

    // === 4. Calculate Burnout & Motivation Score ===
    $burnoutMotivationAvg = ($hasil->kehilangan_motivasi + $hasil->beban_mental) / 2;
    $burnoutMotivationPct = (($burnoutMotivationAvg - 1) / 9) * 100;

    // Final score
    $finalScore = $hasil->final_score ?? $hasil->nilai_fatigue;

    // === Helpers for Colors and Statuses ===
    $stressMap = [
        'Relaxed'          => ['bg' => 'var(--green)',     'color' => 'var(--dark)',  'emoji' => '😊', 'gauge' => 'var(--green)',   'desc' => 'Kondisi mental tenang dan prima. Keep it up!'],
        'Mild Pressure'    => ['bg' => 'var(--secondary)', 'color' => 'var(--dark)',  'emoji' => '😐', 'gauge' => 'var(--secondary)', 'desc' => 'Ada sedikit tekanan, tetapi masih dalam batas wajar.'],
        'Moderate Stress'  => ['bg' => 'var(--yellow)',    'color' => 'var(--dark)',  'emoji' => '😟', 'gauge' => 'var(--yellow)',    'desc' => 'Tekanan sedang terdeteksi. Segera atur prioritas!'],
        'High Stress'      => ['bg' => 'var(--primary)',   'color' => 'var(--white)', 'emoji' => '😰', 'gauge' => 'var(--primary)',   'desc' => 'Tingkat stres tinggi! Tubuh dan otak Anda menjerit minta istirahat.'],
        'Severe Stress'    => ['bg' => 'var(--purple)',    'color' => 'var(--white)', 'emoji' => '😵', 'gauge' => 'var(--purple)',    'desc' => 'APOCALYPSE BURNOUT! Segera lepas layar dan ambil jeda darurat.'],
        'Tidak Terdeteksi' => ['bg' => '#999999',          'color' => 'var(--white)', 'emoji' => '❓', 'gauge' => '#999999',         'desc' => 'Analisis gabungan diselesaikan menggunakan Fuzzy.'],
    ];

    $finalStatus = $hasil->final_status ?? $hasil->status;
    // Map status from fatigue tiers to stress tiers if necessary
    if ($finalStatus == 'Kelelahan Ringan') $finalStatus = 'Relaxed';
    if ($finalStatus == 'Kelelahan Sedang') $finalStatus = 'Moderate Stress';
    if ($finalStatus == 'Kelelahan Tinggi') $finalStatus = 'High Stress';

    $finalStyle = $stressMap[$finalStatus] ?? $stressMap['Moderate Stress'];

    // === Emoji representation for dominant emotion ===
    $emotionEmoji = [
        'neutral'   => '😐',
        'happy'     => '😊',
        'sad'       => '😢',
        'angry'     => '😠',
        'fearful'   => '😨',
        'disgusted' => '🤢',
        'surprised' => '😲',
    ];

    // === List 7 emotions for loop ===
    $emotionList = [
        ['key' => 'happy',     'label' => 'HAPPY',     'value' => $hasil->emotion_happy,     'color' => 'var(--green)'],
        ['key' => 'neutral',   'label' => 'NEUTRAL',   'value' => $hasil->emotion_neutral,   'color' => '#AAAAAA'],
        ['key' => 'surprised', 'label' => 'SURPRISED', 'value' => $hasil->emotion_surprised, 'color' => 'var(--secondary)'],
        ['key' => 'sad',       'label' => 'SAD',       'value' => $hasil->emotion_sad,       'color' => '#3366FF'],
        ['key' => 'fearful',   'label' => 'FEARFUL',   'value' => $hasil->emotion_fearful,   'color' => 'var(--purple)'],
        ['key' => 'disgusted', 'label' => 'DISGUSTED', 'value' => $hasil->emotion_disgusted, 'color' => '#FF8800'],
        ['key' => 'angry',     'label' => 'ANGRY',     'value' => $hasil->emotion_angry,     'color' => 'var(--primary)'],
    ];

    // === Helpers for positive variable display ===
    function getPositiveIndicator($value, $label) {
        if ($value >= 8) return ['label' => 'Sangat Baik', 'color' => 'var(--purple)', 'emoji' => '✨'];
        if ($value >= 6) return ['label' => 'Baik', 'color' => 'var(--green)', 'emoji' => '🟢'];
        if ($value >= 4) return ['label' => 'Cukup', 'color' => '#AA8800', 'emoji' => '🟡'];
        return ['label' => 'Rendah', 'color' => 'var(--primary)', 'emoji' => '🔴'];
    }
@endphp

<div class="result-container">
    
    <div class="result-header">
        <div class="neo-badge result-subtitle-badge">
            STUDENT BURNOUT & FATIGUE DIAGNOSIS
        </div>
        <h1 class="result-main-title">DASHBOARD ANALISIS STRES</h1>
    </div>

    <!-- ========================================================== -->
    <!-- HERO BOX: NEW SVG STRESS GAUGE & MENTAL STATUS INDICATOR -->
    <!-- ========================================================== -->
    <div class="result-hero-grid result-grid">
        
        <!-- Left: SVG Gauge -->
        <div class="neo-box gauge-box">
            <div class="gauge-container">
                <svg class="gauge" viewBox="0 0 260 130">
                    <!-- Semicircle arc background -->
                    <path class="gauge-bg" d="M 20 120 A 110 110 0 0 1 240 120" />
                    <!-- Animated indicator path -->
                    <path class="gauge-fill" d="M 20 120 A 110 110 0 0 1 240 120" data-score="{{ $finalScore }}" style="stroke: {{ $finalStyle['gauge'] }};" />
                </svg>
                <div class="gauge-value-text">{{ number_format($finalScore, 0) }}%</div>
            </div>
            <div class="gauge-label">
                Final Combined Stress Index
            </div>
        </div>

        <!-- Right: Status Indicator & Interpretation -->
        <div class="neo-box status-box" style="background-color: {{ $finalStyle['bg'] }}; color: {{ $finalStyle['color'] }};">
            <div class="status-label">
                Mental Headspace Status
            </div>
            <h1 class="status-title">
                {{ $finalStyle['emoji'] }} {{ $finalStatus }}
            </h1>
            <p class="status-desc">
                {{ $finalStyle['desc'] }}
            </p>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- ANALYTICS CARDS (DAILY ACTIVITIES & KEY INDICATORS) -->
    <!-- ========================================================== -->
    <div class="analytics-grid">
        <div class="analytics-card border-purple">
            <div class="analytics-card-label">Tidur Semalam</div>
            <div class="analytics-card-value">{{ $hasil->jam_tidur }} Jam</div>
            <div class="analytics-card-desc" style="color: {{ $hasil->jam_tidur < 5 ? 'var(--primary)' : 'var(--green)' }}; font-weight: 700;">
                {{ $hasil->jam_tidur < 5 ? '🔴 Kurang Tidur' : '🟢 Cukup Tidur' }}
            </div>
        </div>
        <div class="analytics-card border-secondary">
            <div class="analytics-card-label">Screen Time</div>
            <div class="analytics-card-value">{{ $hasil->screen_time }} Jam</div>
            <div class="analytics-card-desc" style="color: {{ $hasil->screen_time > 8 ? 'var(--primary)' : 'var(--green)' }}; font-weight: 700;">
                {{ $hasil->screen_time > 8 ? '🔴 Berlebih (Detox)' : '🟢 Normal' }}
            </div>
        </div>
        <div class="analytics-card border-yellow">
            <div class="analytics-card-label">Kualitas Tidur</div>
            <div class="analytics-card-value">{{ $hasil->kualitas_tidur }} / 10</div>
            @php $ktInd = getPositiveIndicator($hasil->kualitas_tidur, 'Kualitas Tidur'); @endphp
            <div class="analytics-card-desc" style="color: {{ $ktInd['color'] }}; font-weight: 700;">
                {{ $ktInd['emoji'] }} {{ $ktInd['label'] }}
            </div>
        </div>
        <div class="analytics-card border-green">
            <div class="analytics-card-label">Regulasi Emosi</div>
            <div class="analytics-card-value">{{ $hasil->regulasi_emosi }} / 10</div>
            @php $reInd = getPositiveIndicator($hasil->regulasi_emosi, 'Regulasi Emosi'); @endphp
            <div class="analytics-card-desc" style="color: {{ $reInd['color'] }}; font-weight: 700;">
                {{ $reInd['emoji'] }} {{ $reInd['label'] }}
            </div>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- DETAILED CLINICAL RISK BADGES (FOR DEMO/PRESENTATION) -->
    <!-- ========================================================== -->
    <div style="margin-top: 1rem; margin-bottom: 3rem;">
        <h3 class="system-info-title" style="margin-bottom: 1.25rem;">
            🚨 Indikator Risiko Klinis Utama
        </h3>
        <div class="clinical-badges-grid">
            
            <!-- Badge 1: Overthinking Level -->
            <div class="clinical-badge-card border-ot">
                <div class="clinical-badge-tag">OVERTHINKING LEVEL</div>
                <div class="clinical-badge-value">{{ $hasil->overthinking }}<span class="clinical-badge-max">/10</span></div>
                @php
                    $otScore = $hasil->overthinking;
                    if ($otScore >= 8) {
                        $otStatus = 'Kritis (High Loop)';
                        $otDesc = 'Pikiran berputar tanpa henti secara konstan';
                        $otClass = 'status-critical';
                    } elseif ($otScore >= 5) {
                        $otStatus = 'Sedang';
                        $otDesc = 'Kecenderungan memikirkan banyak hal sekaligus';
                        $otClass = 'status-warning';
                    } else {
                        $otStatus = 'Rendah (Stabil)';
                        $otDesc = 'Fokus mental stabil dan terkendali';
                        $otClass = 'status-healthy';
                    }
                @endphp
                <div class="clinical-badge-status {{ $otClass }}">{{ $otStatus }}</div>
                <div class="clinical-badge-desc">{{ $otDesc }}</div>
            </div>

            <!-- Badge 2: Anxiety Level -->
            <div class="clinical-badge-card border-ax">
                <div class="clinical-badge-tag">ANXIETY LEVEL</div>
                <div class="clinical-badge-value">{{ $hasil->kecemasan }}<span class="clinical-badge-max">/10</span></div>
                @php
                    $axScore = $hasil->kecemasan;
                    if ($axScore >= 8) {
                        $axStatus = 'Klinis (Tinggi)';
                        $axDesc = 'Kecemasan & kegelisahan tingkat klinis (GAD-7)';
                        $axClass = 'status-critical';
                    } elseif ($axScore >= 5) {
                        $axStatus = 'Moderat (Sering)';
                        $axDesc = 'Kekhawatiran harian yang cukup mengganggu';
                        $axClass = 'status-warning';
                    } else {
                        $axStatus = 'Tenang (Minimal)';
                        $axDesc = 'Tingkat kecemasan minimal dan terkendali';
                        $axClass = 'status-healthy';
                    }
                @endphp
                <div class="clinical-badge-status {{ $axClass }}">{{ $axStatus }}</div>
                <div class="clinical-badge-desc">{{ $axDesc }}</div>
            </div>

            <!-- Badge 3: Burnout Risk -->
            <div class="clinical-badge-card border-bo">
                <div class="clinical-badge-tag">BURNOUT RISK</div>
                @php
                    // Burnout risk is computed using fatigue, loss of motivation, and mental burden
                    $burnoutScore = ($hasil->nilai_fatigue + ($hasil->kehilangan_motivasi * 10) + ($hasil->beban_mental * 10)) / 3;
                    if ($burnoutScore >= 75) {
                        $boStatus = 'Risiko Tinggi';
                        $boDesc = 'Ambang batas kelelahan mental & amotivasi kronis';
                        $boClass = 'status-critical';
                    } elseif ($burnoutScore >= 45) {
                        $boStatus = 'Risiko Sedang';
                        $boDesc = 'Kelelahan harian mulai menggerus produktivitas';
                        $boClass = 'status-warning';
                    } else {
                        $boStatus = 'Aman';
                        $boDesc = 'Cadangan energi mental & motivasi berada di titik optimal';
                        $boClass = 'status-healthy';
                    }
                @endphp
                <div class="clinical-badge-value">{{ number_format($burnoutScore, 0) }}%</div>
                <div class="clinical-badge-status {{ $boClass }}">{{ $boStatus }}</div>
                <div class="clinical-badge-desc">{{ $boDesc }}</div>
            </div>

        </div>
    </div>

    <!-- ========================================================== -->
    <!-- TWO COLUMNS: PSYCHOLOGICAL FATIGUE CHART VS AI FACE EMOTION -->
    <!-- ========================================================== -->
    <div class="two-col-grid result-grid">

        <!-- Left Column: Psychological Fatigue Sub-Scores -->
        <div class="neo-box result-panel">
            <h3 class="panel-title">
                🧠 PSYCHOLOGICAL FATIGUE BREAKDOWN
            </h3>
            
            <p class="panel-desc">
                Sub-skor kelelahan psikologis dihitung dari 12 parameter klinis yang diadaptasi dari PHQ-9, GAD-7, DASS-21, PSQI, DERS, dan WHO-5:
            </p>

            <div class="flex-col">
                <!-- Row 1: Mood & Depressive -->
                <div class="breakdown-row">
                    <span style="font-weight: 700; font-size: 0.95rem;">Mood & Depressive</span>
                    <div class="breakdown-bar-outer">
                        <div class="breakdown-bar-inner" data-width="{{ $moodDepressivePct }}" style="background-color: #3366FF;"></div>
                    </div>
                    <span style="font-weight: 700; text-align: right; font-size: 0.95rem;">{{ number_format($moodDepressivePct, 0) }}%</span>
                </div>

                <!-- Row 2: Anxiety & Stress -->
                <div class="breakdown-row">
                    <span style="font-weight: 700; font-size: 0.95rem;">Anxiety & Stress</span>
                    <div class="breakdown-bar-outer">
                        <div class="breakdown-bar-inner" data-width="{{ $anxietyStressPct }}" style="background-color: var(--primary);"></div>
                    </div>
                    <span style="font-weight: 700; text-align: right; font-size: 0.95rem;">{{ number_format($anxietyStressPct, 0) }}%</span>
                </div>

                <!-- Row 3: Cognitive Fatigue -->
                <div class="breakdown-row">
                    <span style="font-weight: 700; font-size: 0.95rem;">Cognitive Fatigue</span>
                    <div class="breakdown-bar-outer">
                        <div class="breakdown-bar-inner" data-width="{{ $cognitiveFatiguePct }}" style="background-color: var(--yellow);"></div>
                    </div>
                    <span style="font-weight: 700; text-align: right; font-size: 0.95rem;">{{ number_format($cognitiveFatiguePct, 0) }}%</span>
                </div>

                <!-- Row 4: Burnout & Motivation -->
                <div class="breakdown-row">
                    <span style="font-weight: 700; font-size: 0.95rem;">Burnout & Motivation</span>
                    <div class="breakdown-bar-outer">
                        <div class="breakdown-bar-inner" data-width="{{ $burnoutMotivationPct }}" style="background-color: var(--purple);"></div>
                    </div>
                    <span style="font-weight: 700; text-align: right; font-size: 0.95rem;">{{ number_format($burnoutMotivationPct, 0) }}%</span>
                </div>
            </div>

            <!-- Recommendation Summary Alert Box -->
            <div class="action-tip-box">
                <div class="action-tip-title">
                    💡 AI RANGKUMAN TINDAKAN:
                </div>
                <div class="action-tip-desc">
                    @if ($finalScore >= 75)
                        Prioritaskan digital detox, tolak rapat organisasi tambahan, dan matikan HP jam 10 malam untuk tidur darurat 8-9 jam penuh.
                    @elseif ($finalScore >= 45)
                        Gunakan Pomodoro (25m belajar, 5m rileks), stretching bahu/leher, lakukan journaling perasaan di sela deadline.
                    @else
                        Kondisi mental prima. Pertahankan konsistensi tidur dan gaskeun tugas tersulit hari ini mumpung energi melimpah!
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: AI Facial Emotion Recognition (Supporting 30%) -->
        <div class="neo-box result-panel">
            <div class="panel-title-spaced">
                <h3 class="panel-title-inline">📷 AI FACIAL EXPRESSION SCAN</h3>
                <span class="supporting-badge">
                    SUPPORTING 30%
                </span>
            </div>

            @if ($hasil->fer_detected)
                <!-- Score & Status -->
                <div class="fer-summary-box">
                    <div>
                        <div class="fer-summary-label">Face-API.js Stress Index</div>
                        <div class="fer-summary-value">
                            {{ number_format($hasil->fer_stress_score, 1) }}%
                        </div>
                    </div>
                    <div class="fer-summary-status">
                        {{ $hasil->fer_status }}
                    </div>
                </div>

                <!-- Dominant Emotion -->
                <div class="fer-dominant-box">
                    <div class="fer-dominant-label">EMOSI WAJAH DOMINAN</div>
                    <div class="fer-dominant-value">
                        {{ $emotionEmoji[$hasil->dominant_emotion] ?? '😐' }}
                        {{ $hasil->dominant_emotion ?? 'neutral' }}
                        ({{ number_format(($hasil->dominant_emotion_score ?? 0) * 100, 1) }}%)
                    </div>
                </div>

                <!-- Breakdown of all 7 emotions -->
                <div class="fer-breakdown-list">
                    @foreach ($emotionList as $emo)
                        @php $pct = ($emo['value'] ?? 0) * 100; @endphp
                        <div class="fer-breakdown-row">
                            <span class="fer-breakdown-name">
                                {{ $emotionEmoji[$emo['key']] }} {{ $emo['label'] }}
                            </span>
                            <div class="fer-breakdown-bar-outer">
                                <div style="width: {{ $pct }}%; height: 100%; background: {{ $emo['color'] }};"></div>
                            </div>
                            <span class="fer-breakdown-pct">{{ number_format($pct, 0) }}%</span>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- FER Tidak Terdeteksi -->
                <div class="fer-undetected-box">
                    <div class="fer-undetected-icon">❓</div>
                    <div class="fer-undetected-title">Face Scan Tidak Terdeteksi</div>
                    <div class="fer-undetected-subtitle">
                        Diagnosis diselesaikan 100% menggunakan Fuzzy Sugeno.
                    </div>
                </div>
                <p class="fer-undetected-desc">
                    Anda melewatkan scan kamera atau wajah terhalang. Coba ulangi dengan mengaktifkan kamera agar Face-API.js AI dapat mendukung akurasi stress score Anda!
                </p>
            @endif
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- TECHNICAL DIAGNOSTICS & SYSTEM INFO -->
    <!-- ========================================================== -->
    <h3 class="system-info-title">
        ⚙️ Informasi Sistem & Parameter Fuzzy Sugeno
    </h3>
    <div class="system-info-grid">

        <div class="neo-box system-info-box">
            <h4 class="system-info-header">
                MATRIKS FUZZY SUGENO
            </h4>
            <p class="system-info-text">
                • Fuzzy Score: <b>{{ number_format($hasil->nilai_fatigue, 1) }}% × 70%</b>
            </p>
            <p class="system-info-text">
                • FER Score: <b>{{ number_format($hasil->fer_stress_score ?? 0, 1) }}% × 30%</b>
            </p>
            <p class="system-info-highlight" style="color: var(--primary);">
                • Combined Index: <b>{{ number_format($finalScore, 1) }}%</b>
            </p>
        </div>

        <div class="neo-box system-info-box">
            <h4 class="system-info-header">
                RULE INFERENCE ENGINE
            </h4>
            <p class="system-info-text">
                • Aturan Terbaca: <b>14 Sugeno Rules (Orde Nol)</b>
            </p>
            <p class="system-info-text">
                • Defuzzifikasi: <b>Weighted Average (WA)</b>
            </p>
            <p class="system-info-text" style="margin: 0;">
                • AI Model: <b>FaceExpressionNet (Face-API)</b>
            </p>
        </div>

        <div class="neo-box system-info-box">
            <h4 class="system-info-header">
                DB TRACKING ID
            </h4>
            <p class="system-info-text">
                • Record Serial: <b>#{{ $hasil->id }}</b>
            </p>
            <p class="system-info-text">
                • Tanggal Simpan: <b>{{ $hasil->created_at->format('d M Y, H:i') }} WIB</b>
            </p>
            <p class="system-info-highlight" style="color: var(--green);">
                • Database Status: <b>SUCCESS</b>
            </p>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="nav-actions">
        <a href="{{ route('kuisioner') }}" class="neo-btn neo-btn-secondary" style="background-color: var(--yellow);">
            COBA LAGI
        </a>
        <a href="{{ route('recommendation') }}" class="neo-btn" style="background-color: var(--green); display: inline-flex; align-items: center; gap: 0.75rem;">
            REKOMENDASI PERSONAL LENGKAP
            <svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="btn-icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
            </svg>
        </a>
    </div>
</div>
@endsection
