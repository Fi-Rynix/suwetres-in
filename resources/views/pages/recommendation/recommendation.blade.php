@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/recommendation.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/recommendation.js') }}"></script>
@endsection

@section('content')
@php
    // Pakai final_status (gabungan Fuzzy + FER) sebagai basis utama rekomendasi
    $finalStatus = $hasil->final_status ?? $hasil->status;
    $finalScore = $hasil->final_score ?? $hasil->nilai_fatigue;

    // Map status ke kategori rekomendasi (3 tier untuk simplifikasi konten)
    $tier = 'low';
    if (in_array($finalStatus, ['Moderate Stress', 'Kelelahan Sedang', 'Mild Pressure'])) {
        $tier = 'medium';
    } elseif (in_array($finalStatus, ['High Stress', 'Severe Stress', 'Kelelahan Tinggi'])) {
        $tier = 'high';
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

<div style="max-width: 800px; margin: 1rem auto;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div class="neo-badge" style="background-color: var(--purple); color: var(--white); margin: 0;">
            RITUAL PENYELAMAT STRESS DARI AI
        </div>
        <div style="font-weight: 700; text-transform: uppercase;">
            SOLUSI COPING STRESS
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- INSIGHT BOX: Rangkuman Dual-Source Analysis -->
    <!-- ========================================================== -->
    <div class="neo-box" style="background-color: #FFFDE5; padding: 1.5rem; margin-bottom: 2rem;">
        <h3 style="font-size: 1.2rem; border-bottom: 3px solid var(--dark); padding-bottom: 0.6rem; margin-bottom: 1rem;">
            🔍 RANGKUMAN ANALISIS
        </h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;" class="insight-grid">
            <div style="background: var(--white); border: 2px solid var(--dark); padding: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                <div style="font-size: 0.75rem; font-weight: 700; color: #555;">FATIGUE (FUZZY)</div>
                <div style="font-size: 1.4rem; font-weight: 700;">{{ number_format($hasil->nilai_fatigue, 1) }}%</div>
                <div style="font-size: 0.85rem; font-weight: 600;">{{ $hasil->status }}</div>
            </div>
            <div style="background: var(--white); border: 2px solid var(--dark); padding: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                <div style="font-size: 0.75rem; font-weight: 700; color: #555;">STRESS (FER)</div>
                @if ($hasil->fer_detected)
                    <div style="font-size: 1.4rem; font-weight: 700;">{{ number_format($hasil->fer_stress_score, 1) }}%</div>
                    <div style="font-size: 0.85rem; font-weight: 600;">
                        {{ $emotionEmoji[$hasil->dominant_emotion] ?? '' }} {{ $hasil->fer_status }}
                    </div>
                @else
                    <div style="font-size: 1.4rem; font-weight: 700;">N/A</div>
                    <div style="font-size: 0.85rem; font-weight: 600;">Tidak terdeteksi</div>
                @endif
            </div>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- MAIN RECOMMENDATION BOX -->
    <!-- ========================================================== -->
    <div class="neo-box" style="background-color: var(--white); padding: 2.5rem;">
        <h2 style="font-size: 2rem; border-bottom: 4px solid var(--dark); padding-bottom: 1rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <span>TINDAKAN PENYELAMAT DIRI</span>
            <span style="font-size: 1.1rem; background-color: var(--yellow); border: 2px solid var(--dark); padding: 0.2rem 0.8rem; font-weight: 700;">
                FINAL: {{ $finalStatus }}
            </span>
        </h2>

        @if ($tier === 'low')
            <div style="background-color: var(--green); border: var(--border-width) solid var(--dark); box-shadow: 4px 4px 0 var(--dark); padding: 1.5rem; margin-bottom: 2rem; font-weight: 700;">
                BEBAN HIDUP AMAN! SEHAT SENTOSA JAYA GAYS
            </div>
            <p>
                Kadar stress dan kelelahanmu masih sangat normal. Belum butuh self-reward aneh-aneh yang bikin rekening jebol. Otakmu lagi di mode super-sehat buat nyerap materi kuliah sesulit apapun!
            </p>

            <h3 style="font-size: 1.3rem; margin-top: 2rem; margin-bottom: 1rem;">RITUAL PENYELAMAT DIRI HARI INI:</h3>
            <ul style="list-style-type: none; padding: 0; font-weight: 600; font-size: 1.05rem;">
                <li style="background: #F0FAF6; border: 2px solid var(--dark); padding: 0.8rem; margin-bottom: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                    <b>Gaskeun Tugas Berat:</b> Selesaikan proyek coding atau laporan praktikum yang paling mager kamu kerjain sebelum energinya ngedrop.
                </li>
                <li style="background: #F0FAF6; border: 2px solid var(--dark); padding: 0.8rem; margin-bottom: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                    <b>Olahraga Tipis-Tipis:</b> Jalan santai atau sekadar peregangan agar otot tidak kaku gara-gara kelamaan nongkrong.
                </li>
                <li style="background: #F0FAF6; border: 2px solid var(--dark); padding: 0.8rem; margin-bottom: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                    <b>Consistent Sleeping:</b> Pertahankan jam tidurmu semalam yang sudah di angka aman 7-8 jam. Jangan coba-coba begadang nonton anime!
                </li>
            </ul>

        @elseif ($tier === 'medium')
            <div style="background-color: var(--yellow); border: var(--border-width) solid var(--dark); box-shadow: 4px 4px 0 var(--dark); padding: 1.5rem; margin-bottom: 2rem; font-weight: 700;">
                BEBAN SEDANG! SEDIKIT LAGI LOGOUT DARI KAMPUS
            </div>
            <p>
                Kombinasi tugas yang mulai tumpuk-tumpuk ditambah kepanitiaan/organisasi dan screen time berlebih mulai menghisap stamina jiwamu. Hati-hati, sebentar lagi burnout mendekat!
            </p>

            <h3 style="font-size: 1.3rem; margin-top: 2rem; margin-bottom: 1rem;">RITUAL PENYELAMAT DIRI HARI INI:</h3>
            <ul style="list-style-type: none; padding: 0; font-weight: 600; font-size: 1.05rem;">
                <li style="background: #FFFDE5; border: 2px solid var(--dark); padding: 0.8rem; margin-bottom: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                    <b>Pomodoro Ritual:</b> Fokus ngerjain tugas 25 menit, terus matiin layar HP/Laptop buat istirahat 5 menit. Jangan main sosmed pas istirahat!
                </li>
                <li style="background: #FFFDE5; border: 2px solid var(--dark); padding: 0.8rem; margin-bottom: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                    <b>Jauhkan HP Setelah Jam 10 Malam:</b> Kurangi screen time iseng scrolling Tiktok/Reels yang tidak berfaedah demi kesehatan matamu.
                </li>
                <li style="background: #FFFDE5; border: 2px solid var(--dark); padding: 0.8rem; margin-bottom: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                    <b>Minum Air Putih Banyak-Banyak:</b> Hidrasi otakmu. Kurangi minum kopi/energi instan yang berlebihan biar jantung gak dugun-dugun.
                </li>
            </ul>

        @else
            <div style="background-color: var(--primary); color: var(--white); border: var(--border-width) solid var(--dark); box-shadow: 4px 4px 0 var(--dark); padding: 1.5rem; margin-bottom: 2rem; font-weight: 700;">
                APOCALYPSE LEVEL STRESS! BAHAYA TINGKAT DEWA GAYS
            </div>
            <p>
                <b>Gawat!</b> Jiwamu sudah menjerit minta tolong karena kurang tidur parah, tugas menumpuk gunung, dan screen time kebablasan. Segera tarik rem darurat sebelum semestermu terbakar habis!
            </p>

            <h3 style="font-size: 1.3rem; margin-top: 2rem; margin-bottom: 1rem; color: var(--primary); font-weight: 700;">RITUAL DARURAT PENYELAMATAN SEGERA:</h3>
            <ul style="list-style-type: none; padding: 0; font-weight: 600; font-size: 1.05rem;">
                <li style="background: #FFF0F5; border: 2px solid var(--dark); padding: 0.8rem; margin-bottom: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                    <b>Wajib Hibernasi (Tidur):</b> Taruh HP di meja sebelah, matikan lampu kamar, tidurlah minimal 8-9 jam malam ini. Kesehatan jauh lebih mahal dari nilai A!
                </li>
                <li style="background: #FFF0F5; border: 2px solid var(--dark); padding: 0.8rem; margin-bottom: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                    <b>Tolak/Tunda Rapat Organisasi:</b> Izin tidak ikut rapat atau menunda pengerjaan tugas yang tidak ber-deadline besok pagi. Tarik nafas dalam-dalam.
                </li>
                <li style="background: #FFF0F5; border: 2px solid var(--dark); padding: 0.8rem; margin-bottom: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                    <b>Digital Detox Maksimal:</b> Jauhkan mata dari segala jenis layar monitor/ponsel minimal 1 jam penuh sebelum memejamkan mata di kasur.
                </li>
            </ul>
        @endif

        <!-- ========================================================== -->
        <!-- FER-SPECIFIC ADVICE: Berdasarkan emosi dominan -->
        <!-- ========================================================== -->
        @if ($hasil->fer_detected && $hasil->dominant_emotion)
            <h3 style="font-size: 1.3rem; margin-top: 2.5rem; margin-bottom: 1rem; border-top: 4px solid var(--dark); padding-top: 1.5rem;">
                🎭 INSIGHT DARI EKSPRESI WAJAH
            </h3>

            @php
                $emotionAdvice = [
                    'happy' => [
                        'title' => 'Mood Positif Terdeteksi!',
                        'msg'   => 'Ekspresimu menunjukkan emosi positif. Manfaatkan mood bagus ini untuk mengerjakan tugas yang butuh kreativitas dan fokus tinggi.',
                        'bg'    => 'var(--green)',
                        'color' => 'var(--dark)',
                    ],
                    'neutral' => [
                        'title' => 'Ekspresi Datar (Flat Affect)',
                        'msg'   => 'Wajah datar terus-menerus bisa jadi tanda burnout awal. Coba tonton sesuatu yang lucu, ngobrol sama teman, atau lakukan hobi yang bikin senyum.',
                        'bg'    => 'var(--yellow)',
                        'color' => 'var(--dark)',
                    ],
                    'sad' => [
                        'title' => 'Sinyal Kesedihan Terdeteksi',
                        'msg'   => 'Ekspresi sedih dominan. Jangan dipendam sendiri ya! Cerita ke teman dekat, keluarga, atau jika berlanjut, hubungi konselor kampus.',
                        'bg'    => '#3366FF',
                        'color' => 'var(--white)',
                    ],
                    'angry' => [
                        'title' => 'Frustrasi Tingkat Tinggi',
                        'msg'   => 'Ekspresi marah/frustrasi terdeteksi kuat. Ini sinyal stress akut. Coba teknik 4-7-8 breathing (tarik 4s, tahan 7s, buang 8s) sebanyak 3x.',
                        'bg'    => 'var(--primary)',
                        'color' => 'var(--white)',
                    ],
                    'fearful' => [
                        'title' => 'Anxiety Spike Terdeteksi',
                        'msg'   => 'Ekspresi takut/cemas dominan. Ini bisa jadi indikator anxiety. Lakukan grounding 5-4-3-2-1: sebut 5 hal yang dilihat, 4 disentuh, 3 didengar, 2 dicium, 1 dirasakan.',
                        'bg'    => 'var(--purple)',
                        'color' => 'var(--white)',
                    ],
                    'disgusted' => [
                        'title' => 'Ketidaknyamanan Mental',
                        'msg'   => 'Ekspresi disgusted muncul, mungkin ada beban yang bikin gak nyaman. Coba identifikasi pemicunya dan ambil jeda sejenak dari aktivitas tersebut.',
                        'bg'    => '#FF8800',
                        'color' => 'var(--white)',
                    ],
                    'surprised' => [
                        'title' => 'Sinyal Tegang/Kaget',
                        'msg'   => 'Ekspresi terkejut bisa muncul saat overwhelmed dengan info atau deadline mendadak. Ambil nafas, prioritaskan task, dan lakukan satu per satu.',
                        'bg'    => 'var(--secondary)',
                        'color' => 'var(--dark)',
                    ],
                ];
                $advice = $emotionAdvice[$hasil->dominant_emotion] ?? null;
            @endphp

            @if ($advice)
                <div style="background-color: {{ $advice['bg'] }}; color: {{ $advice['color'] }}; border: var(--border-width) solid var(--dark); box-shadow: 4px 4px 0 var(--dark); padding: 1.5rem; margin-bottom: 1rem;">
                    <div style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem;">
                        {{ $emotionEmoji[$hasil->dominant_emotion] ?? '' }} {{ $advice['title'] }}
                    </div>
                    <div style="font-size: 0.95rem; font-weight: 600; line-height: 1.5;">
                        {{ $advice['msg'] }}
                    </div>
                </div>
            @endif

            @if (($hasil->emotion_variance ?? 0) > 0.3)
                <div style="background-color: #FFFDE5; border: 2px solid var(--dark); padding: 0.8rem; box-shadow: 3px 3px 0 var(--dark); font-size: 0.9rem; font-weight: 600;">
                    ⚠️ <b>Mood Swing Tinggi:</b> Emosimu naik-turun cukup ekstrem selama scan. Ini tanda mental fatigue. Coba journaling 5 menit sebelum tidur.
                </div>
            @endif
        @endif

        <div style="margin-top: 3rem; display: flex; gap: 1.5rem; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('result') }}" class="neo-btn neo-btn-secondary" style="flex: 1; min-width: 200px; background-color: var(--yellow);">
                ↩ BACK TO DASHBOARD
            </a>
            <a href="{{ route('landing') }}" class="neo-btn" style="flex: 1; min-width: 200px; background-color: var(--purple); color: var(--white);">
                KEMBALI KE BERANDA
            </a>
        </div>
    </div>
</div>

<style>
    @media (max-width: 600px) {
        .insight-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
