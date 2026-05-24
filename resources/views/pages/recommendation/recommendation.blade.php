@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/recommendation.css') }}">
@endsection

@section('content')
@php
    $finalStatus = $hasil->final_status ?? $hasil->status;
    $finalScore = $hasil->final_score ?? $hasil->nilai_fatigue;

    // Derived variables from Likert
    $fokus = 11 - $hasil->fokus_belajar;
    $motivasi = 11 - $hasil->motivasi_kuliah;
    $tekanan = $hasil->tekanan_tugas;
    $beban = $hasil->beban_mental;

    // Tier calculation
    $tier = 'low';
    if ($finalScore >= 75) {
        $tier = 'high';
    } elseif ($finalScore >= 45) {
        $tier = 'medium';
    }

    $emotionEmoji = [
        'neutral'   => '😐',
        'happy'     => '😊',
        'sad'       => '😢',
        'angry'     => '😠',
        'fearful'   => '😨',
        'disgusted' => '🤢',
        'surprised' => '😲',
    ];
@endphp

<div class="recom-container">

    <div class="recom-header">
        <div class="neo-badge recom-header-badge">
            AI PERSONALIZED HEALTH PLAN
        </div>
        <div class="recom-header-title">
            SUWETRES COPING MATRIX
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- DYNAMIC STATUS MATRIX PANEL -->
    <!-- ========================================================== -->
    <div class="neo-box matrix-panel">
        <h3 class="matrix-title">
            📊 MATRIKS KONDISI PSIKOLOGIS ANDA
        </h3>
        
        <div class="insight-grid">
            <div class="insight-card">
                <div class="insight-card-label">Tekanan Akademik</div>
                @if ($tekanan >= 9)
                    <span class="indicator-pill bg-purple">Sangat Tinggi 🚨</span>
                @elseif ($tekanan >= 7)
                    <span class="indicator-pill bg-primary">Tinggi ⚠️</span>
                @elseif ($tekanan >= 4)
                    <span class="indicator-pill bg-yellow">Sedang ⚡</span>
                @else
                    <span class="indicator-pill bg-green">Ringan ✅</span>
                @endif
            </div>

            <div class="insight-card">
                <div class="insight-card-label">Kualitas Fokus</div>
                @if ($fokus <= 3)
                    <span class="indicator-pill bg-primary">Sangat Rendah 🚨</span>
                @elseif ($fokus <= 6)
                    <span class="indicator-pill bg-yellow">Sedang ⚡</span>
                @elseif ($fokus <= 8)
                    <span class="indicator-pill bg-green">Tinggi 🟢</span>
                @else
                    <span class="indicator-pill bg-purple">Sangat Tinggi ✨</span>
                @endif
            </div>

            <div class="insight-card">
                <div class="insight-card-label">Level Motivasi</div>
                @if ($motivasi <= 3)
                    <span class="indicator-pill bg-primary">Sangat Rendah 🚨</span>
                @elseif ($motivasi <= 6)
                    <span class="indicator-pill bg-yellow">Sedang ⚡</span>
                @elseif ($motivasi <= 8)
                    <span class="indicator-pill bg-green">Tinggi 🟢</span>
                @else
                    <span class="indicator-pill bg-purple">Sangat Tinggi ✨</span>
                @endif
            </div>

            <div class="insight-card">
                <div class="insight-card-label">Beban Mental</div>
                @if ($beban >= 9)
                    <span class="indicator-pill bg-purple">Sangat Tinggi 🚨</span>
                @elseif ($beban >= 7)
                    <span class="indicator-pill bg-primary">Tinggi ⚠️</span>
                @elseif ($beban >= 4)
                    <span class="indicator-pill bg-yellow">Sedang ⚡</span>
                @else
                    <span class="indicator-pill bg-green">Ringan ✅</span>
                @endif
            </div>
        </div>

        @if ($hasil->fer_detected)
            <div class="fer-insight-box">
                🎭 <b>AI Emotion Insight:</b> Ekspresi dominan Anda terdeteksi sebagai <b>{{ strtoupper($hasil->dominant_emotion) }} {{ $emotionEmoji[$hasil->dominant_emotion] ?? '' }}</b>.
                @if (in_array($hasil->dominant_emotion, ['sad', 'angry', 'fearful', 'disgusted']))
                    Sistem mendeteksi adanya gejolak stres emosional yang tinggi pada ekspresi wajah Anda. Prioritaskan relaksasi sore ini!
                @else
                    Struktur emosi luar Anda cukup stabil dan datar/netral. Upayakan menjaga kebugaran pikiran.
                @endif
            </div>
        @endif
    </div>

    <!-- ========================================================== -->
    <!-- HIGH-IMPACT SUGGESTIONS PANEL -->
    <!-- ========================================================== -->
    <div class="neo-box action-panel">
        <h2 class="action-panel-title">
            <span>TINDAKAN PENYELAMAT DIRI</span>
            <span class="action-panel-badge">
                INDeks stres: {{ number_format($finalScore, 0) }}%
            </span>
        </h2>

        @if ($tier === 'low')
            <p class="action-lead-text">
                <b>Mantap!</b> Kadar stres dan kelelahan mental Anda berada di rentang sangat sehat. Otak Anda berada di performa puncak untuk mencerna informasi perkuliahan sesulit apapun hari ini. Manfaatkan momentum energi ini!
            </p>
        @elseif ($tier === 'medium')
            <p class="action-lead-text">
                <b>Perhatian!</b> Beban harian, screen time, dan kesibukan tugas Anda mulai menumpuk. Hati-hati, Anda berada di ambang burnout jika mengabaikan sinyal kelelahan ini. Lakukan coping stres aktif di bawah ini!
            </p>
        @else
            <p class="action-lead-text-alert">
                <b>⚠️ DARURAT BURNOUT!</b> Sistem mendeteksi stres kronik/kelelahan berat. Pikiran dan tubuh Anda membutuhkan penanganan segera sebelum semester Anda terhambat. Terapkan tindakan penanggulangan darurat sekarang!
            </p>
        @endif

        <h3 class="action-subtitle">
            Ritual Coping Stres Sesuai Kondisi Anda:
        </h3>

        <!-- DYNAMIC RECOMMENDATION GRID -->
        <div class="recom-grid">

            <!-- 1. TIDUR LEBIH AWAL (Always dynamic based on sleep duration) -->
            @if ($hasil->jam_tidur < 5)
                <div class="recom-card recom-card-alert">
                    <div class="recom-card-title">Tidur Lebih Awal</div>
                    <div class="recom-card-text">
                        <b>Wajib non-negotiable!</b> Anda hanya tidur {{ $hasil->jam_tidur }} jam semalam. Tubuh Anda berhutang energi besar. Taruh HP di luar jangkauan kasur pada jam 10 malam dan tidurlah minimal 8 jam untuk memulihkan fungsi kognitif otak Anda.
                    </div>
                </div>
            @else
                <div class="recom-card recom-card-success">
                    <div class="recom-card-title">Consistent Sleep</div>
                    <div class="recom-card-text">
                        Tidur {{ $hasil->jam_tidur }} jam Anda semalam sudah sangat baik. Pertahankan jadwal tidur yang konsisten ini agar ritme sirkadian tubuh Anda tetap seimbang. Hindari begadang nonton serial/main game!
                    </div>
                </div>
            @endif

            <!-- 2. TEKNIK POMODORO (Academic / Focus based) -->
            @if ($tekanan >= 7 || $fokus <= 6)
                <div class="recom-card recom-card-warning">
                    <div class="recom-card-title">Teknik Pomodoro</div>
                    <div class="recom-card-text">
                        Karena tekanan tugas Anda tinggi dan fokus menurun, belajarlah menggunakan metode **25 menit fokus penuh (tanpa tab media sosial/HP)**, diikuti **5 menit istirahat total**. Hal ini mencegah kelelahan otak kronis (mental block).
                    </div>
                </div>
            @else
                <div class="recom-card recom-card-success">
                    <div class="recom-card-title">Deep Work Session</div>
                    <div class="recom-card-text">
                        Fokus Anda berada di tingkat yang bagus. Manfaatkan ini untuk melakukan sesi *Deep Work* selama 90 menit tanpa interupsi untuk menuntaskan proyek coding atau laporan yang paling menantang.
                    </div>
                </div>
            @endif

            <!-- 3. DIGITAL DETOX (Screen Time / Impact based) -->
            @if ($hasil->screen_time > 8 || $hasil->dampak_screen_time >= 7)
                <div class="recom-card recom-card-info">
                    <div class="recom-card-title">Digital Detox</div>
                    <div class="recom-card-text">
                        Screen time Anda mencapai {{ $hasil->screen_time }} jam! Radiasi cahaya biru layar mengganggu melatonin. Lakukan detoks layar minimal 1 jam penuh sebelum tidur. Ganti dengan membaca buku fisik atau mendengarkan musik santai.
                    </div>
                </div>
            @else
                <div class="recom-card recom-card-success">
                    <div class="recom-card-title">Smart Device Balance</div>
                    <div class="recom-card-text">
                        Durasi screen time Anda yang terkendali sudah bagus. Selaraskan aktivitas digital Anda dengan rehat visual singkat setiap 20 menit menatap layar (rumus 20-20-20: tatap sejauh 20 kaki selama 20 detik).
                    </div>
                </div>
            @endif

            <!-- 4. BREATHING EXERCISE (Mental Burden / Anxiety based) -->
            @if ($beban >= 7 || $hasil->kecemasan_deadline >= 7 || in_array($hasil->dominant_emotion, ['angry', 'fearful']))
                <div class="recom-card recom-card-purple">
                    <div class="recom-card-title">Breathing Exercise</div>
                    <div class="recom-card-text">
                        Beban mental atau kecemasan Anda tergolong tinggi. Lakukan latihan pernapasan metode **4-7-8**: Tarik napas 4 detik, tahan 7 detik, embuskan perlahan 8 detik. Ulangi sebanyak 4 siklus untuk menurunkan sistem saraf simpatis Anda.
                    </div>
                </div>
            @else
                <div class="recom-card recom-card-success">
                    <div class="recom-card-title">Mindfulness Santai</div>
                    <div class="recom-card-text">
                        Pikiran Anda stabil. Sempatkan waktu 3 menit untuk duduk tenang tanpa memikirkan tugas kuliah. Cukup rasakan napas masuk dan keluar secara alami untuk memperkuat ketahanan mental harian.
                    </div>
                </div>
            @endif

            <!-- 5. JOURNALING (Emotional Exhaustion / Expression based) -->
            @if ($beban >= 7 || $hasil->keseimbangan_hidup >= 7 || $hasil->dominant_emotion == 'sad')
                <div class="recom-card recom-card-warning">
                    <div class="recom-card-title">Journaling & Brain Dump</div>
                    <div class="recom-card-text">
                        Kondisi mental Anda cukup terbebani. Keluarkan tumpukan pikiran dengan metode *Brain Dump*: tulis semua kecemasan, tugas, atau emosi yang mengganjal di sela-sela waktu istirahat agar pikiran terasa ringan.
                    </div>
                </div>
            @endif

            <!-- 6. STRETCHING & PHYSICAL RECOVERY (General Fatigue / Activity fatigue based) -->
            @if ($hasil->kelelahan_setelah_istirahat >= 7 || $hasil->kelelahan_aktivitas >= 7)
                <div class="recom-card recom-card-alert">
                    <div class="recom-card-title">Stretching & Self-Care</div>
                    <div class="recom-card-text">
                        Tubuh Anda mudah lelah. Lakukan peregangan dinamis ringan pada leher, bahu, dan punggung bawah selama 5 menit. Tubuh yang segar akan membantu mengembalikan stamina berpikir Anda secara instan.
                    </div>
                </div>
            @endif
        </div>

        <div class="recom-actions">
            <a href="{{ route('result') }}" class="neo-btn neo-btn-secondary btn-secondary-recom">
                ↩ BACK TO DASHBOARD
            </a>
            <a href="{{ route('landing') }}" class="neo-btn btn-primary-recom">
                KEMBALI KE BERANDA
            </a>
        </div>
    </div>
</div>
@endsection
