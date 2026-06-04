@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/recommendation.css') }}">
@endsection

@section('content')
@php
    $finalStatus = $hasil->final_status ?? $hasil->status;
    $finalScore = $hasil->final_score ?? $hasil->nilai_fatigue;

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

    // Helper for negative variable severity
    function getSeverityPill($value) {
        if ($value >= 9) return '<span class="indicator-pill bg-purple">Sangat Tinggi 🚨</span>';
        if ($value >= 7) return '<span class="indicator-pill bg-primary">Tinggi ⚠️</span>';
        if ($value >= 4) return '<span class="indicator-pill bg-yellow">Sedang ⚡</span>';
        return '<span class="indicator-pill bg-green">Ringan ✅</span>';
    }

    // Helper for positive variable severity (inverted display)
    function getPositivePill($value) {
        if ($value >= 8) return '<span class="indicator-pill bg-purple">Sangat Baik ✨</span>';
        if ($value >= 6) return '<span class="indicator-pill bg-green">Baik 🟢</span>';
        if ($value >= 4) return '<span class="indicator-pill bg-yellow">Cukup ⚡</span>';
        return '<span class="indicator-pill bg-primary">Rendah 🚨</span>';
    }
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
    <!-- DYNAMIC STATUS MATRIX PANEL (7 DOMAINS) -->
    <!-- ========================================================== -->
    <div class="neo-box matrix-panel">
        <h3 class="matrix-title">
            📊 MATRIKS KONDISI PSIKOLOGIS ANDA
        </h3>
        
        <div class="insight-grid">
            <div class="insight-card">
                <div class="insight-card-label">Mood Rendah</div>
                {!! getSeverityPill($hasil->mood_rendah) !!}
            </div>

            <div class="insight-card">
                <div class="insight-card-label">Kecemasan</div>
                {!! getSeverityPill($hasil->kecemasan) !!}
            </div>

            <div class="insight-card">
                <div class="insight-card-label">Gangguan Konsentrasi</div>
                {!! getSeverityPill($hasil->gangguan_konsentrasi) !!}
            </div>

            <div class="insight-card">
                <div class="insight-card-label">Kualitas Tidur</div>
                {!! getPositivePill($hasil->kualitas_tidur) !!}
            </div>

            <div class="insight-card">
                <div class="insight-card-label">Regulasi Emosi</div>
                {!! getPositivePill($hasil->regulasi_emosi) !!}
            </div>

            <div class="insight-card">
                <div class="insight-card-label">Beban Mental</div>
                {!! getSeverityPill($hasil->beban_mental) !!}
            </div>

            <div class="insight-card">
                <div class="insight-card-label">Kehilangan Motivasi</div>
                {!! getSeverityPill($hasil->kehilangan_motivasi) !!}
            </div>

            <div class="insight-card">
                <div class="insight-card-label">Overthinking</div>
                {!! getSeverityPill($hasil->overthinking) !!}
            </div>

            <div class="insight-card">
                <div class="insight-card-label">Sulit Rileks</div>
                {!! getSeverityPill($hasil->sulit_rileks) !!}
            </div>

            <div class="insight-card">
                <div class="insight-card-label">Gejala Fisik Stres</div>
                {!! getSeverityPill($hasil->gejala_fisik_stres) !!}
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
                INDEKS STRES: {{ number_format($finalScore, 0) }}%
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

        <!-- DYNAMIC RECOMMENDATION GRID (7 DOMAINS) -->
        <div class="recom-grid">

            <!-- 1. SLEEP HYGIENE (kualitas_tidur + jam_tidur) -->
            @if ($hasil->kualitas_tidur <= 4 || $hasil->jam_tidur < 5)
                <div class="recom-card recom-card-alert">
                    <div class="recom-card-title">🌙 Sleep Hygiene — Perbaikan Kualitas Tidur</div>
                    <div class="recom-card-text">
                        <b>Kualitas tidur Anda rendah ({{ $hasil->kualitas_tidur }}/10) dan rata-rata hanya {{ $hasil->jam_tidur }} jam/hari.</b> Riset menunjukkan kualitas tidur yang buruk berkorelasi kuat dengan kelelahan mental dan penurunan konsentrasi (PSQI). Terapkan sleep hygiene: matikan layar 1 jam sebelum tidur, jaga suhu ruangan 18-22°C, dan tidur-bangun di jam yang sama setiap hari.
                    </div>
                </div>
            @else
                <div class="recom-card recom-card-success">
                    <div class="recom-card-title">🌙 Sleep Quality — Consistent Rest</div>
                    <div class="recom-card-text">
                        Kualitas tidur Anda ({{ $hasil->kualitas_tidur }}/10) dan durasi ({{ $hasil->jam_tidur }} jam) sudah baik. Pertahankan jadwal tidur yang konsisten agar ritme sirkadian tubuh Anda tetap seimbang. Hindari begadang nonton serial/main game!
                    </div>
                </div>
            @endif

            <!-- 2. MOOD RESTORATION (mood_rendah + dominant_emotion) -->
            @if ($hasil->mood_rendah >= 7 || $hasil->dominant_emotion == 'sad')
                <div class="recom-card recom-card-info">
                    <div class="recom-card-title">😔 Mood Restoration — Pemulihan Suasana Hati</div>
                    <div class="recom-card-text">
                        <b>Mood rendah Anda ({{ $hasil->mood_rendah }}/10) menunjukkan gejala depresif ringan-sedang (PHQ-2).</b> Lakukan aktivitas yang meningkatkan mood secara natural: jalan kaki 15 menit di luar ruangan (paparan sinar matahari meningkatkan serotonin), dengarkan musik favorit, atau hubungi teman dekat/keluarga untuk berbicara. Jika perasaan sedih/putus asa menetap lebih dari 2 minggu, pertimbangkan konsultasi dengan konselor kampus.
                    </div>
                </div>
            @endif

            <!-- 3. ANXIETY COPING (kecemasan + kewalahan) -->
            @if ($hasil->kecemasan >= 7 || $hasil->kewalahan >= 7)
                <div class="recom-card recom-card-purple">
                    <div class="recom-card-title">😰 Anxiety Coping — Manajemen Kecemasan</div>
                    <div class="recom-card-text">
                        @if ($hasil->kecemasan >= 7 && $hasil->kewalahan >= 7)
                            <b>Kecemasan ({{ $hasil->kecemasan }}/10) DAN kewalahan ({{ $hasil->kewalahan }}/10) Anda sama-sama tinggi.</b>
                        @elseif ($hasil->kecemasan >= 7)
                            <b>Kecemasan Anda ({{ $hasil->kecemasan }}/10) tergolong tinggi (GAD-7 equivalent).</b>
                        @else
                            <b>Rasa kewalahan Anda ({{ $hasil->kewalahan }}/10) sangat tinggi (DASS-21 Stress).</b>
                        @endif
                        Lakukan teknik pernapasan 4-7-8: tarik napas 4 detik, tahan 7 detik, embuskan perlahan 8 detik. Ulangi 4 siklus. Kemudian buat daftar prioritas tugas — pecah tugas besar menjadi langkah kecil untuk mengurangi rasa kewalahan.
                    </div>
                </div>
            @endif

            <!-- 4. FOCUS RECOVERY (gangguan_konsentrasi + kelelahan_mental) -->
            @if ($hasil->gangguan_konsentrasi >= 7 || $hasil->kelelahan_mental >= 7)
                <div class="recom-card recom-card-warning">
                    <div class="recom-card-title">🧠 Focus Recovery — Pemulihan Konsentrasi</div>
                    <div class="recom-card-text">
                        @if ($hasil->gangguan_konsentrasi >= 7 && $hasil->kelelahan_mental >= 7)
                            <b>Konsentrasi terganggu ({{ $hasil->gangguan_konsentrasi }}/10) dan kelelahan mental tinggi ({{ $hasil->kelelahan_mental }}/10).</b>
                        @elseif ($hasil->gangguan_konsentrasi >= 7)
                            <b>Konsentrasi Anda sangat terganggu ({{ $hasil->gangguan_konsentrasi }}/10, PHQ-9 item 7).</b>
                        @else
                            <b>Kelelahan mental Anda tinggi ({{ $hasil->kelelahan_mental }}/10, DASS-21).</b>
                        @endif
                        Gunakan metode Pomodoro: <b>25 menit fokus penuh</b> (tanpa tab media sosial/HP), diikuti <b>5 menit istirahat total</b>. Juga lakukan micro-break setiap 20 menit: tatap objek 20 kaki jauhnya selama 20 detik (aturan 20-20-20).
                    </div>
                </div>
            @endif

            <!-- 5. DIGITAL DETOX (dampak_screen_time + screen_time) -->
            @if ($hasil->dampak_screen_time >= 7 || $hasil->screen_time > 8)
                <div class="recom-card recom-card-info">
                    <div class="recom-card-title">📱 Digital Detox — Kurangi Paparan Layar</div>
                    <div class="recom-card-text">
                        <b>Screen time Anda {{ $hasil->screen_time }} jam/hari dengan dampak mental {{ $hasil->dampak_screen_time }}/10.</b> Radiasi cahaya biru layar mengganggu produksi melatonin dan meningkatkan kelelahan mata. Lakukan detoks layar minimal 1 jam penuh sebelum tidur. Ganti dengan membaca buku fisik atau mendengarkan musik santai. Aktifkan mode grayscale di HP Anda untuk mengurangi daya tarik scrolling.
                    </div>
                </div>
            @else
                <div class="recom-card recom-card-success">
                    <div class="recom-card-title">📱 Smart Device Balance</div>
                    <div class="recom-card-text">
                        Durasi screen time Anda ({{ $hasil->screen_time }} jam) dan dampak mentalnya ({{ $hasil->dampak_screen_time }}/10) masih terkendali. Selaraskan aktivitas digital dengan rehat visual singkat setiap 20 menit menatap layar.
                    </div>
                </div>
            @endif

            <!-- 6. EMOTION REGULATION (regulasi_emosi + dampak_emosi) -->
            @if ($hasil->regulasi_emosi <= 4 || $hasil->dampak_emosi >= 7)
                <div class="recom-card recom-card-purple">
                    <div class="recom-card-title">💜 Emotion Regulation — Pengelolaan Emosi</div>
                    <div class="recom-card-text">
                        @if ($hasil->regulasi_emosi <= 4 && $hasil->dampak_emosi >= 7)
                            <b>Kemampuan regulasi emosi Anda rendah ({{ $hasil->regulasi_emosi }}/10) dan emosi sangat memengaruhi produktivitas ({{ $hasil->dampak_emosi }}/10).</b>
                        @elseif ($hasil->regulasi_emosi <= 4)
                            <b>Anda merasa sulit menenangkan diri saat emosi kuat ({{ $hasil->regulasi_emosi }}/10, DERS-adapted).</b>
                        @else
                            <b>Kondisi emosional Anda sangat memengaruhi produktivitas ({{ $hasil->dampak_emosi }}/10).</b>
                        @endif
                        Praktikkan teknik grounding 5-4-3-2-1: sebutkan 5 hal yang Anda lihat, 4 yang disentuh, 3 yang didengar, 2 yang dicium, 1 yang dirasakan. Kemudian lakukan journaling — tulis semua kecemasan dan emosi yang mengganjal untuk melepaskan beban pikiran.
                    </div>
                </div>
            @endif

            <!-- 7. MOTIVATION BOOST (kehilangan_motivasi) -->
            @if ($hasil->kehilangan_motivasi >= 7)
                <div class="recom-card recom-card-alert">
                    <div class="recom-card-title">🔥 Motivation Boost — Pemulihan Motivasi</div>
                    <div class="recom-card-text">
                        <b>Anda sering kehilangan motivasi kuliah ({{ $hasil->kehilangan_motivasi }}/10).</b> Ini adalah tanda awal burnout akademik. Coba teknik "2-Minute Rule": mulai tugas apapun hanya selama 2 menit — momentum awal sering memicu kelanjutan. Tulis ulang tujuan jangka pendek (minggu ini) dan tujuan jangka panjang (semester ini) untuk mengembalikan sense of purpose. Reward diri Anda setelah menyelesaikan setiap tugas kecil.
                    </div>
                </div>
            @endif

            <!-- 8. OVERTHINKING & SULIT RILEKS (overthinking + sulit_rileks) -->
            @if ($hasil->overthinking >= 7 || $hasil->sulit_rileks >= 7)
                <div class="recom-card recom-card-warning">
                    <div class="recom-card-title">🌀 Mind Calming — Mengatasi Overthinking & Sulit Rileks</div>
                    <div class="recom-card-text">
                        <b>Overthinking Anda ({{ $hasil->overthinking }}/10) atau tingkat kesulitan rileks ({{ $hasil->sulit_rileks }}/10) tergolong tinggi.</b> Pikiran yang terus berputar memicu ketegangan saraf konstan. Lakukan latihan <i>mindfulness grounding</i>: sadari 5 benda sekitar, atau dengarkan suara alam/ambient tanpa distorsi. Terapkan teknik "brain dump" dengan menuliskan semua pikiran yang berputar di atas kertas sebelum tidur agar otak bisa melepaskan beban tugas.
                    </div>
                </div>
            @endif

            <!-- 9. GEJALA FISIK STRES (gejala_fisik_stres) -->
            @if ($hasil->gejala_fisik_stres >= 7)
                <div class="recom-card recom-card-alert">
                    <div class="recom-card-title">💓 Somatic Relief — Mengurangi Gejala Fisik Stres</div>
                    <div class="recom-card-text">
                        <b>Tingkat gejala fisik stres Anda tinggi ({{ $hasil->gejala_fisik_stres }}/10) seperti jantung berdebar atau otot tegang.</b> Ini tanda sistem saraf simpatik Anda terlalu aktif. Lakukan peregangan otot progresif (Progressive Muscle Relaxation) selama 10 menit: tegangkan lalu rilekskan kelompok otot dari kaki hingga wajah bergantian. Mandi air hangat atau kompres hangat di area leher belakang juga dapat membantu meredakan ketegangan fisik secara langsung.
                    </div>
                </div>
            @endif

            <!-- CATCH-ALL: If everything is fine -->
            @if ($tier === 'low' && $hasil->kualitas_tidur > 4 && $hasil->jam_tidur >= 5 && $hasil->mood_rendah < 7 && $hasil->kecemasan < 7 && $hasil->kewalahan < 7 && $hasil->gangguan_konsentrasi < 7 && $hasil->kelelahan_mental < 7 && $hasil->dampak_screen_time < 7 && $hasil->screen_time <= 8 && $hasil->regulasi_emosi > 4 && $hasil->dampak_emosi < 7 && $hasil->kehilangan_motivasi < 7 && $hasil->overthinking < 7 && $hasil->sulit_rileks < 7 && $hasil->gejala_fisik_stres < 7)
                <div class="recom-card recom-card-success">
                    <div class="recom-card-title">🌟 Mindfulness & Maintenance</div>
                    <div class="recom-card-text">
                        Semua indikator psikologis Anda berada di zona hijau! Pertahankan kebiasaan sehat ini. Luangkan 3-5 menit setiap hari untuk mindful breathing atau meditasi ringan sebagai pencegahan burnout. Terus jaga keseimbangan antara aktivitas kuliah dan waktu istirahat.
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
