@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/kuisioner.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/kuisioner.js') }}"></script>
@endsection

@section('content')
<div style="max-width: 700px; margin: 1rem auto;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div class="neo-badge" style="background-color: var(--purple); color: var(--white); margin: 0;">
            TAHAP 1: STUDENT BURNOUT & FATIGUE ASSESSMENT
        </div>
        <div style="font-weight: 700; text-transform: uppercase; font-size: 0.9rem; background-color: var(--white); border: 2px solid var(--dark); padding: 0.2rem 0.6rem; box-shadow: 2px 2px 0 var(--dark);">
            Skala Likert (1 - 10)
        </div>
    </div>

    <!-- INSTRUKSI TIMEFRAME -->
    <div class="question-card" style="background-color: #FFFDE5; border-left: 8px solid var(--yellow);">
        <p style="font-size: 1.1rem; font-weight: 700; margin: 0; line-height: 1.5;">
             Jawablah pertanyaan berikut berdasarkan kondisi yang Anda alami dalam <strong style="color: var(--primary);">7 hari terakhir</strong>.
        </p>
        <p style="font-size: 0.85rem; margin: 0.5rem 0 0 0; color: #555; font-weight: 600;">
            Diadaptasi dari PHQ-9, GAD-7, DASS-21, PSQI, DERS, & WHO-5.
        </p>
    </div>

    <!-- DYNAMIC PROGRESS INDICATOR -->
    <div class="progress-container">
        <div style="font-weight: 700; font-size: 0.95rem; text-transform: uppercase;">Progress:</div>
        <div class="progress-bar-outer">
            <div id="survey-progress" class="progress-bar-inner"></div>
        </div>
        <div id="progress-text" class="progress-label">0% Selesai</div>
    </div>

    @if ($errors->any())
        <div style="background-color: var(--primary); color: var(--white); border: 4px solid var(--dark); padding: 1.2rem; margin-bottom: 2rem; box-shadow: 4px 4px 0 var(--dark); font-weight: 700; transform: rotate(-0.5deg);">
            <div style="font-size: 1.1rem; margin-bottom: 0.5rem; text-transform: uppercase;">Pengisian belum lengkap!</div>
            <ul style="list-style: square; padding-left: 1.2rem; font-size: 0.9rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('post.kuisioner') }}" method="POST" id="questionnaire-form">
        @csrf
        
        <!-- ========================================== -->
        <!-- BAGIAN A: AKTIVITAS HARIAN -->
        <!-- ========================================== -->
        <h2 style="font-size: 1.3rem; margin-bottom: 1rem; padding-left: 0.5rem; border-left: 8px solid var(--yellow); text-transform: uppercase;">
            Bagian A: Aktivitas Harian
        </h2>

        <!-- JAM TIDUR -->
        <div class="question-card activity-card">
            <div class="question-num" style="background-color: var(--yellow); color: var(--dark);">Harian 01</div>
            <label class="form-label" for="jam_tidur" style="font-size: 1.2rem; margin-bottom: 0.8rem;">
                Berapa rata-rata jam tidur Anda per hari? (0 - 24 Jam)
            </label>
            <input type="number" step="any" min="0" max="24" name="jam_tidur" id="jam_tidur" class="neo-input" 
                   placeholder="Masukkan rata-rata jam tidur (contoh: 6)" value="{{ old('jam_tidur') }}" required>
            <div style="display: flex; justify-content: space-between; margin-top: 0.5rem; font-size: 0.75rem; font-weight: 700; color: #555;">
                <span>Sedikit (0-5 Jam)</span>
                <span>Cukup (4-8 Jam)</span>
                <span>Banyak (7+ Jam)</span>
            </div>
        </div>

        <!-- SCREEN TIME -->
        <div class="question-card activity-card">
            <div class="question-num" style="background-color: var(--yellow); color: var(--dark);">Harian 02</div>
            <label class="form-label" for="screen_time" style="font-size: 1.2rem; margin-bottom: 0.8rem;">
                Berapa rata-rata screen time Anda per hari? (0 - 24 Jam)
            </label>
            <input type="number" step="any" min="0" max="24" name="screen_time" id="screen_time" class="neo-input" 
                   placeholder="Masukkan durasi screen time (contoh: 8)" value="{{ old('screen_time') }}" required>
            <div style="display: flex; justify-content: space-between; margin-top: 0.5rem; font-size: 0.75rem; font-weight: 700; color: #555;">
                <span>Rendah (0-5 Jam)</span>
                <span>Sedang (4-9 Jam)</span>
                <span>Tinggi (8+ Jam)</span>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- BAGIAN B: KONDISI PSIKOLOGIS (LIKERT 1-10) -->
        <!-- ========================================== -->
        <h2 style="font-size: 1.3rem; margin-top: 3rem; margin-bottom: 1rem; padding-left: 0.5rem; border-left: 8px solid var(--purple); text-transform: uppercase;">
            Bagian B: Kondisi Psikologis (7 Hari Terakhir)
        </h2>

        @php
            $psikologisQuestions = [
                [
                    'field' => 'kualitas_tidur',
                    'num' => 'Psikologis 01',
                    'q' => 'Dalam 7 hari terakhir, seberapa puas Anda dengan kualitas tidur Anda?',
                    'anchor_low' => '1 = Sangat Tidak Puas',
                    'anchor_high' => '10 = Sangat Puas',
                    'polarity' => 'positive',
                ],
                [
                    'field' => 'kelelahan_mental',
                    'num' => 'Psikologis 02',
                    'q' => 'Dalam 7 hari terakhir, seberapa sering Anda merasa mudah lelah secara mental?',
                    'anchor_low' => '1 = Tidak Sama Sekali',
                    'anchor_high' => '10 = Hampir Sepanjang Waktu',
                    'polarity' => 'negative',
                ],
                [
                    'field' => 'gangguan_konsentrasi',
                    'num' => 'Psikologis 03',
                    'q' => 'Dalam 7 hari terakhir, seberapa terganggu konsentrasi Anda saat belajar atau mengerjakan tugas?',
                    'anchor_low' => '1 = Tidak Terganggu',
                    'anchor_high' => '10 = Sangat Terganggu',
                    'polarity' => 'negative',
                ],
                [
                    'field' => 'mood_rendah',
                    'num' => 'Psikologis 04',
                    'q' => 'Seberapa sering Anda merasa sedih atau putus asa?',
                    'anchor_low' => '1 = Tidak Sama Sekali',
                    'anchor_high' => '10 = Hampir Sepanjang Waktu',
                    'polarity' => 'negative',
                ],
                [
                    'field' => 'kecemasan',
                    'num' => 'Psikologis 05',
                    'q' => 'Dalam 7 hari terakhir, seberapa sering Anda merasa cemas, gelisah, atau sulit merasa tenang?',
                    'anchor_low' => '1 = Tidak Sama Sekali',
                    'anchor_high' => '10 = Sangat Sering',
                    'polarity' => 'negative',
                ],
                [
                    'field' => 'kewalahan',
                    'num' => 'Psikologis 06',
                    'q' => 'Dalam 7 hari terakhir, seberapa besar Anda merasa kewalahan oleh tuntutan kuliah dan aktivitas sehari-hari?',
                    'anchor_low' => '1 = Tidak Kewalahan',
                    'anchor_high' => '10 = Sangat Kewalahan',
                    'polarity' => 'negative',
                ],
                [
                    'field' => 'dampak_screen_time',
                    'num' => 'Psikologis 07',
                    'q' => 'Dalam 7 hari terakhir, seberapa besar screen time memengaruhi kondisi mental atau emosional Anda?',
                    'anchor_low' => '1 = Tidak Berpengaruh',
                    'anchor_high' => '10 = Sangat Berpengaruh',
                    'polarity' => 'negative',
                ],
                [
                    'field' => 'kehilangan_motivasi',
                    'num' => 'Psikologis 08',
                    'q' => 'Dalam 7 hari terakhir, seberapa sering Anda merasa kehilangan motivasi untuk menjalani aktivitas kuliah?',
                    'anchor_low' => '1 = Tidak Pernah',
                    'anchor_high' => '10 = Hampir Sepanjang Waktu',
                    'polarity' => 'negative',
                ],
                [
                    'field' => 'dampak_emosi',
                    'num' => 'Psikologis 09',
                    'q' => 'Dalam 7 hari terakhir, seberapa besar kondisi emosional Anda memengaruhi produktivitas belajar atau bekerja?',
                    'anchor_low' => '1 = Tidak Memengaruhi',
                    'anchor_high' => '10 = Sangat Memengaruhi',
                    'polarity' => 'negative',
                ],
                [
                    'field' => 'beban_mental',
                    'num' => 'Psikologis 10',
                    'q' => 'Dalam 7 hari terakhir, seberapa besar beban mental yang Anda rasakan?',
                    'anchor_low' => '1 = Sangat Ringan',
                    'anchor_high' => '10 = Sangat Berat',
                    'polarity' => 'negative',
                ],
                [
                    'field' => 'kepuasan_hidup',
                    'num' => 'Psikologis 11',
                    'q' => 'Dalam 7 hari terakhir, seberapa puas Anda dengan kehidupan sehari-hari Anda secara keseluruhan?',
                    'anchor_low' => '1 = Sangat Tidak Puas',
                    'anchor_high' => '10 = Sangat Puas',
                    'polarity' => 'positive',
                ],
                [
                    'field' => 'regulasi_emosi',
                    'num' => 'Psikologis 12',
                    'q' => 'Dalam 7 hari terakhir, seberapa mudah Anda menenangkan diri ketika mengalami emosi yang kuat?',
                    'anchor_low' => '1 = Sangat Sulit',
                    'anchor_high' => '10 = Sangat Mudah',
                    'polarity' => 'positive',
                ],
                [
                    'field' => 'overthinking',
                    'num' => 'Psikologis 13',
                    'q' => 'Dalam 7 hari terakhir, seberapa sering pikiran Anda terus berputar memikirkan banyak hal sekaligus?',
                    'anchor_low' => '1 = Tidak Sama Sekali',
                    'anchor_high' => '10 = Hampir Sepanjang Waktu',
                    'polarity' => 'negative',
                ],
                [
                    'field' => 'sulit_rileks',
                    'num' => 'Psikologis 14',
                    'q' => 'Dalam 7 hari terakhir, seberapa sering Anda merasa sulit untuk rileks atau menenangkan diri?',
                    'anchor_low' => '1 = Tidak Sama Sekali',
                    'anchor_high' => '10 = Hampir Sepanjang Waktu',
                    'polarity' => 'negative',
                ],
                [
                    'field' => 'gejala_fisik_stres',
                    'num' => 'Psikologis 15',
                    'q' => 'Seberapa sering Anda mengalami gejala fisik saat tertekan (misalnya jantung berdebar, napas terasa pendek, atau tubuh terasa tegang)?',
                    'anchor_low' => '1 = Tidak Sama Sekali',
                    'anchor_high' => '10 = Sangat Sering',
                    'polarity' => 'negative',
                ],
            ];
        @endphp

        @foreach ($psikologisQuestions as $index => $q)
            <div class="question-card">
                <div class="question-num">{{ $q['num'] }}</div>
                <p style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; line-height: 1.4;">
                    {{ $q['q'] }}
                </p>
                @if ($q['polarity'] === 'positive')
                    <div style="display: inline-block; font-size: 0.75rem; font-weight: 700; padding: 0.15rem 0.5rem; background-color: var(--green); border: 2px solid var(--dark); margin-bottom: 0.5rem;">
                        POSITIF — skor tinggi = kondisi baik
                    </div>
                @endif
                
                <div class="likert-container">
                    @for ($val = 1; $val <= 10; $val++)
                        <label class="likert-option">
                            <input type="radio" name="{{ $q['field'] }}" value="{{ $val }}" 
                                   {{ old($q['field']) == $val ? 'checked' : '' }} required>
                            <span>{{ $val }}</span>
                        </label>
                    @endfor
                </div>
                <div class="likert-legend">
                    <span>{{ $q['anchor_low'] }}</span>
                    <span>{{ $q['anchor_high'] }}</span>
                </div>
            </div>
        @endforeach

        <!-- FORM ACTION BUTTONS -->
        <div style="margin-top: 3rem; display: flex; gap: 1.5rem; margin-bottom: 4rem;">
            <a href="{{ route('landing') }}" class="neo-btn neo-btn-secondary" style="flex: 1; background-color: var(--yellow);">
                ↩ BACK
            </a>
            <button type="submit" class="neo-btn" style="flex: 2; background-color: var(--green);">
                LANJUT: SCAN EXPRESI WAJAH AI
            </button>
        </div>
    </form>
</div>
@endsection
