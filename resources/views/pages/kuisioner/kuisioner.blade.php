@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/kuisioner.css') }}">
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.400.0/dist/umd/lucide.min.js"></script>
    <script src="{{ asset('js/kuisioner.js') }}"></script>
@endsection

@section('content')
@php
    $psikologisQuestions = [
        [
            'field' => 'kualitas_tidur',
            'num' => 'Mood 1',
            'q' => 'Dalam 7 hari terakhir, seberapa puas Anda dengan kualitas tidur Anda?',
            'anchor_low' => '1 = Sangat Tidak Puas',
            'anchor_high' => '10 = Sangat Puas',
            'polarity' => 'positive',
            'step' => 2,
        ],
        [
            'field' => 'kelelahan_mental',
            'num' => 'Burnout 1',
            'q' => 'Dalam 7 hari terakhir, seberapa sering Anda merasa mudah lelah secara mental?',
            'anchor_low' => '1 = Tidak Sama Sekali',
            'anchor_high' => '10 = Hampir Sepanjang Waktu',
            'polarity' => 'negative',
            'step' => 4,
        ],
        [
            'field' => 'gangguan_konsentrasi',
            'num' => 'Burnout 2',
            'q' => 'Dalam 7 hari terakhir, seberapa terganggu konsentrasi Anda saat belajar atau mengerjakan tugas?',
            'anchor_low' => '1 = Tidak Terganggu',
            'anchor_high' => '10 = Sangat Terganggu',
            'polarity' => 'negative',
            'step' => 4,
        ],
        [
            'field' => 'mood_rendah',
            'num' => 'Mood 2',
            'q' => 'Seberapa sering Anda merasa sedih atau putus asa?',
            'anchor_low' => '1 = Tidak Sama Sekali',
            'anchor_high' => '10 = Hampir Sepanjang Waktu',
            'polarity' => 'negative',
            'step' => 2,
        ],
        [
            'field' => 'kecemasan',
            'num' => 'Stres 1',
            'q' => 'Dalam 7 hari terakhir, seberapa sering Anda merasa cemas, gelisah, atau sulit merasa tenang?',
            'anchor_low' => '1 = Tidak Sama Sekali',
            'anchor_high' => '10 = Sangat Sering',
            'polarity' => 'negative',
            'step' => 3,
        ],
        [
            'field' => 'kewalahan',
            'num' => 'Stres 2',
            'q' => 'Dalam 7 hari terakhir, seberapa besar Anda merasa kewalahan oleh tuntutan kuliah dan aktivitas sehari-hari?',
            'anchor_low' => '1 = Tidak Kewalahan',
            'anchor_high' => '10 = Sangat Kewalahan',
            'polarity' => 'negative',
            'step' => 3,
        ],
        [
            'field' => 'dampak_screen_time',
            'num' => 'Digital 1',
            'q' => 'Dalam 7 hari terakhir, seberapa besar screen time memengaruhi kondisi mental atau emosional Anda?',
            'anchor_low' => '1 = Tidak Berpengaruh',
            'anchor_high' => '10 = Sangat Berpengaruh',
            'polarity' => 'negative',
            'step' => 5,
        ],
        [
            'field' => 'kehilangan_motivasi',
            'num' => 'Burnout 3',
            'q' => 'Dalam 7 hari terakhir, seberapa sering Anda merasa kehilangan motivasi untuk menjalani aktivitas kuliah?',
            'anchor_low' => '1 = Tidak Pernah',
            'anchor_high' => '10 = Hampir Sepanjang Waktu',
            'polarity' => 'negative',
            'step' => 4,
        ],
        [
            'field' => 'dampak_emosi',
            'num' => 'Digital 2',
            'q' => 'Dalam 7 hari terakhir, seberapa besar kondisi emosional Anda memengaruhi produktivitas belajar atau bekerja?',
            'anchor_low' => '1 = Tidak Memengaruhi',
            'anchor_high' => '10 = Sangat Memengaruhi',
            'polarity' => 'negative',
            'step' => 5,
        ],
        [
            'field' => 'beban_mental',
            'num' => 'Burnout 4',
            'q' => 'Dalam 7 hari terakhir, seberapa besar beban mental yang Anda rasakan?',
            'anchor_low' => '1 = Sangat Ringan',
            'anchor_high' => '10 = Sangat Berat',
            'polarity' => 'negative',
            'step' => 4,
        ],
        [
            'field' => 'kepuasan_hidup',
            'num' => 'Mood 3',
            'q' => 'Dalam 7 hari terakhir, seberapa puas Anda dengan kehidupan sehari-hari Anda secara keseluruhan?',
            'anchor_low' => '1 = Sangat Tidak Puas',
            'anchor_high' => '10 = Sangat Puas',
            'polarity' => 'positive',
            'step' => 2,
        ],
        [
            'field' => 'regulasi_emosi',
            'num' => 'Mood 4',
            'q' => 'Dalam 7 hari terakhir, seberapa mudah Anda menenangkan diri ketika mengalami emosi yang kuat?',
            'anchor_low' => '1 = Sangat Sulit',
            'anchor_high' => '10 = Sangat Mudah',
            'polarity' => 'positive',
            'step' => 2,
        ],
        [
            'field' => 'overthinking',
            'num' => 'Burnout 5',
            'q' => 'Dalam 7 hari terakhir, seberapa sering pikiran Anda terus berputar memikirkan banyak hal sekaligus?',
            'anchor_low' => '1 = Tidak Sama Sekali',
            'anchor_high' => '10 = Hampir Sepanjang Waktu',
            'polarity' => 'negative',
            'step' => 4,
        ],
        [
            'field' => 'sulit_rileks',
            'num' => 'Stres 3',
            'q' => 'Dalam 7 hari terakhir, seberapa sering Anda merasa sulit untuk rileks atau menenangkan diri?',
            'anchor_low' => '1 = Tidak Sama Sekali',
            'anchor_high' => '10 = Hampir Sepanjang Waktu',
            'polarity' => 'negative',
            'step' => 3,
        ],
        [
            'field' => 'gejala_fisik_stres',
            'num' => 'Stres 4',
            'q' => 'Seberapa sering Anda mengalami gejala fisik saat tertekan (misalnya jantung berdebar, napas terasa pendek, atau tubuh terasa tegang)?',
            'anchor_low' => '1 = Tidak Sama Sekali',
            'anchor_high' => '10 = Sangat Sering',
            'polarity' => 'negative',
            'step' => 3,
        ],
    ];
@endphp

<div style="max-width: 700px; margin: 1rem auto;">
    
    <!-- SURVEY TOP HEADER (Hidden on step 0 and 6) -->
    <div id="survey-top-header" style="display: none; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div class="neo-badge" style="background-color: var(--purple); color: var(--white); margin: 0;">
            TAHAP 1: STUDENT BURNOUT & FATIGUE ASSESSMENT
        </div>
        <div style="font-weight: 700; text-transform: uppercase; font-size: 0.9rem; background-color: var(--white); border: 2px solid var(--dark); padding: 0.2rem 0.6rem; box-shadow: 2px 2px 0 var(--dark);">
            Skala Likert (1 - 10)
        </div>
    </div>

    <!-- DYNAMIC PROGRESS INDICATOR & VALIDATION ERROR -->
    <div id="step-progress-header" style="display: none;">
        <!-- Step tabs -->
        <div class="step-progress-wrapper">
            <div class="step-progress-item" data-progress-step="1">
                <div class="step-progress-icon"><i data-lucide="moon"></i></div>
                <div class="step-progress-text">
                    <span class="step-lbl">STEP 1</span>
                    <span class="step-title">Daily</span>
                </div>
            </div>
            <div class="step-progress-item" data-progress-step="2">
                <div class="step-progress-icon"><i data-lucide="smile"></i></div>
                <div class="step-progress-text">
                    <span class="step-lbl">STEP 2</span>
                    <span class="step-title">Mood</span>
                </div>
            </div>
            <div class="step-progress-item" data-progress-step="3">
                <div class="step-progress-icon"><i data-lucide="brain"></i></div>
                <div class="step-progress-text">
                    <span class="step-lbl">STEP 3</span>
                    <span class="step-title">Stres</span>
                </div>
            </div>
            <div class="step-progress-item" data-progress-step="4">
                <div class="step-progress-icon"><i data-lucide="battery-warning"></i></div>
                <div class="step-progress-text">
                    <span class="step-lbl">STEP 4</span>
                    <span class="step-title">Burnout</span>
                </div>
            </div>
            <div class="step-progress-item" data-progress-step="5">
                <div class="step-progress-icon"><i data-lucide="monitor"></i></div>
                <div class="step-progress-text">
                    <span class="step-lbl">STEP 5</span>
                    <span class="step-title">Digital</span>
                </div>
            </div>
        </div>

        <!-- Active Step Title -->
        <div class="active-step-label-container">
            <h3 id="active-step-title" style="margin: 0; font-size: 1.15rem;">Bagian 1 dari 5: DAILY ACTIVITIES</h3>
        </div>

        <!-- Progress bar outer and inner -->
        <div class="progress-container" style="margin-top: 1rem; margin-bottom: 2rem;">
            <div style="font-weight: 700; font-size: 0.95rem; text-transform: uppercase; white-space: nowrap;">Progress:</div>
            <div class="progress-bar-outer">
                <div id="survey-progress" class="progress-bar-inner"></div>
            </div>
            <div id="progress-text" class="progress-label">0% Selesai</div>
        </div>
    </div>

    <!-- Validation error box -->
    <div id="validation-error-box" class="neo-validation-error" style="display: none;">
        <i data-lucide="alert-triangle" style="width: 20px; height: 20px; flex-shrink: 0;"></i>
        <span>Silakan jawab seluruh pertanyaan pada bagian ini terlebih dahulu.</span>
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
        <!-- STEP 0: INTRO SCREEN -->
        <!-- ========================================== -->
        <div class="wizard-step active" data-step="0">
            <div class="neo-box" style="margin-top: 1rem; border-top: 10px solid var(--purple);">
                <h2 style="font-size: 1.5rem; line-height: 1.3; margin-bottom: 1.5rem; text-align: center;">
                    ASSESSMENT TINGKAT FATIGUE & BURNOUT MAHASISWA
                </h2>
                <p style="font-size: 1.05rem; line-height: 1.6; color: #333; margin-bottom: 2rem; text-align: justify; font-weight: 500;">
                    Assessment ini dirancang untuk membantu mengidentifikasi tingkat kelelahan, stres, burnout akademik, dan kesejahteraan psikologis berdasarkan kondisi yang Anda alami dalam <strong style="color: var(--primary);">7 hari terakhir</strong>. Jawablah seluruh pertanyaan dengan jujur agar sistem dapat memberikan hasil analisis yang lebih akurat.
                </p>

                <!-- Info Cards -->
                <div class="wizard-info-grid">
                    <div style="border: 2px solid var(--dark); padding: 1rem; background-color: var(--yellow); box-shadow: 3px 3px 0 var(--dark); text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <i data-lucide="help-circle" style="width: 24px; height: 24px; margin-bottom: 0.5rem; color: var(--dark);"></i>
                        <div style="font-weight: 700; font-size: 0.8rem; text-transform: uppercase; color: var(--dark);">Pertanyaan</div>
                        <div style="font-size: 1.2rem; font-weight: 700; margin-top: 0.25rem; color: var(--dark);">17 Butir</div>
                    </div>
                    <div style="border: 2px solid var(--dark); padding: 1rem; background-color: var(--secondary); box-shadow: 3px 3px 0 var(--dark); text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <i data-lucide="clock" style="width: 24px; height: 24px; margin-bottom: 0.5rem; color: var(--dark);"></i>
                        <div style="font-weight: 700; font-size: 0.8rem; text-transform: uppercase; color: var(--dark);">Estimasi</div>
                        <div style="font-size: 1.2rem; font-weight: 700; margin-top: 0.25rem; color: var(--dark);">2-3 Menit</div>
                    </div>
                    <div style="border: 2px solid var(--dark); padding: 1rem; background-color: var(--green); box-shadow: 3px 3px 0 var(--dark); text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <i data-lucide="cpu" style="width: 24px; height: 24px; margin-bottom: 0.5rem; color: var(--dark);"></i>
                        <div style="font-weight: 700; font-size: 0.8rem; text-transform: uppercase; color: var(--dark);">Metode</div>
                        <div style="font-size: 0.95rem; font-weight: 700; margin-top: 0.25rem; line-height: 1.2; color: var(--dark);">Fuzzy & AI FER</div>
                    </div>
                </div>

                <!-- Action CTA -->
                <div style="text-align: center;">
                    <button type="button" class="neo-btn" id="btn-start" style="width: 100%; max-width: 400px; background-color: var(--green); display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;">
                        MULAI ASSESSMENT <i data-lucide="play" style="width: 18px; height: 18px;"></i>
                    </button>
                    <p style="font-size: 0.8rem; color: #666; margin-top: 1.2rem; font-weight: 600; font-style: italic; line-height: 1.4; max-width: 500px; margin-left: auto; margin-right: auto;">
                        "Hasil assessment ini bersifat edukatif dan bukan diagnosis medis maupun psikologis profesional."
                    </p>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- STEP 1: DAILY ACTIVITIES -->
        <!-- ========================================== -->
        <div class="wizard-step" data-step="1">
            <div class="step-intro-card" style="background-color: #FFFDE5; border: 2px solid var(--dark); border-left: 8px solid var(--yellow); padding: 1.2rem; margin-bottom: 2rem; box-shadow: 4px 4px 0 var(--dark);">
                <h3 style="font-size: 1.15rem; margin: 0 0 0.5rem 0; font-weight: 700; text-transform: uppercase; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="moon" style="width: 20px; height: 20px;"></i> DAILY ACTIVITIES
                </h3>
                <p style="font-size: 0.9rem; margin: 0; line-height: 1.4; font-weight: 600; color: #444;">
                    Data daily activities digunakan untuk mengukur faktor fisik yang berkontribusi terhadap kelelahan dan burnout.
                </p>
            </div>

            <!-- JAM TIDUR -->
            <div class="question-card activity-card">
                <div class="question-num" style="background-color: var(--yellow); color: var(--dark);">Daily 1</div>
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
                <div class="question-num" style="background-color: var(--yellow); color: var(--dark);">Daily 2</div>
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

            <!-- BUTTONS -->
            <div class="wizard-actions">
                <button type="button" class="neo-btn btn-next" style="flex: 1; background-color: var(--green); display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    LANJUT <i data-lucide="arrow-right" style="width: 18px; height: 18px;"></i>
                </button>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- STEP 2: MOOD & KESEJAHTERAAN EMOSIONAL -->
        <!-- ========================================== -->
        <div class="wizard-step" data-step="2">
            <div class="step-intro-card" style="background-color: #FFFDE5; border: 2px solid var(--dark); border-left: 8px solid var(--purple); padding: 1.2rem; margin-bottom: 2rem; box-shadow: 4px 4px 0 var(--dark);">
                <h3 style="font-size: 1.15rem; margin: 0 0 0.5rem 0; font-weight: 700; text-transform: uppercase; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="smile" style="width: 20px; height: 20px;"></i> MOOD & KESEJAHTERAAN EMOSIONAL
                </h3>
                <p style="font-size: 0.9rem; margin: 0; line-height: 1.4; font-weight: 600; color: #444;">
                    Bagian ini mengukur kondisi emosional dan kesejahteraan psikologis yang Anda alami dalam 7 hari terakhir.
                </p>
            </div>

            @foreach ($psikologisQuestions as $q)
                @if ($q['step'] === 2)
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
                @endif
            @endforeach

            <!-- BUTTONS -->
            <div class="wizard-actions">
                <button type="button" class="neo-btn neo-btn-secondary btn-prev" style="flex: 1; background-color: var(--yellow); display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <i data-lucide="arrow-left" style="width: 18px; height: 18px;"></i> KEMBALI
                </button>
                <button type="button" class="neo-btn btn-next" style="flex: 2; background-color: var(--green); display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    LANJUT <i data-lucide="arrow-right" style="width: 18px; height: 18px;"></i>
                </button>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- STEP 3: KECEMASAN & TINGKAT STRES -->
        <!-- ========================================== -->
        <div class="wizard-step" data-step="3">
            <div class="step-intro-card" style="background-color: #FFFDE5; border: 2px solid var(--dark); border-left: 8px solid var(--primary); padding: 1.2rem; margin-bottom: 2rem; box-shadow: 4px 4px 0 var(--dark);">
                <h3 style="font-size: 1.15rem; margin: 0 0 0.5rem 0; font-weight: 700; text-transform: uppercase; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="brain" style="width: 20px; height: 20px;"></i> KECEMASAN & TINGKAT STRES
                </h3>
                <p style="font-size: 0.9rem; margin: 0; line-height: 1.4; font-weight: 600; color: #444;">
                    Bagian ini mengukur tekanan psikologis, kecemasan, serta respons tubuh terhadap stres.
                </p>
            </div>

            @foreach ($psikologisQuestions as $q)
                @if ($q['step'] === 3)
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
                @endif
            @endforeach

            <!-- BUTTONS -->
            <div class="wizard-actions">
                <button type="button" class="neo-btn neo-btn-secondary btn-prev" style="flex: 1; background-color: var(--yellow); display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <i data-lucide="arrow-left" style="width: 18px; height: 18px;"></i> KEMBALI
                </button>
                <button type="button" class="neo-btn btn-next" style="flex: 2; background-color: var(--green); display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    LANJUT <i data-lucide="arrow-right" style="width: 18px; height: 18px;"></i>
                </button>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- STEP 4: BURNOUT & KELELAHAN MENTAL -->
        <!-- ========================================== -->
        <div class="wizard-step" data-step="4">
            <div class="step-intro-card" style="background-color: #FFFDE5; border: 2px solid var(--dark); border-left: 8px solid var(--purple); padding: 1.2rem; margin-bottom: 2rem; box-shadow: 4px 4px 0 var(--dark);">
                <h3 style="font-size: 1.15rem; margin: 0 0 0.5rem 0; font-weight: 700; text-transform: uppercase; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="battery-warning" style="width: 20px; height: 20px;"></i> BURNOUT & KELELAHAN MENTAL
                </h3>
                <p style="font-size: 0.9rem; margin: 0; line-height: 1.4; font-weight: 600; color: #444;">
                    Bagian ini mengukur tingkat kelelahan psikologis yang sering dialami mahasiswa akibat aktivitas akademik.
                </p>
            </div>

            @foreach ($psikologisQuestions as $q)
                @if ($q['step'] === 4)
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
                @endif
            @endforeach

            <!-- BUTTONS -->
            <div class="wizard-actions">
                <button type="button" class="neo-btn neo-btn-secondary btn-prev" style="flex: 1; background-color: var(--yellow); display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <i data-lucide="arrow-left" style="width: 18px; height: 18px;"></i> KEMBALI
                </button>
                <button type="button" class="neo-btn btn-next" style="flex: 2; background-color: var(--green); display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    LANJUT <i data-lucide="arrow-right" style="width: 18px; height: 18px;"></i>
                </button>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- STEP 5: DIGITAL IMPACT -->
        <!-- ========================================== -->
        <div class="wizard-step" data-step="5">
            <div class="step-intro-card" style="background-color: #FFFDE5; border: 2px solid var(--dark); border-left: 8px solid var(--secondary); padding: 1.2rem; margin-bottom: 2rem; box-shadow: 4px 4px 0 var(--dark);">
                <h3 style="font-size: 1.15rem; margin: 0 0 0.5rem 0; font-weight: 700; text-transform: uppercase; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="monitor" style="width: 20px; height: 20px;"></i> DIGITAL IMPACT
                </h3>
                <p style="font-size: 0.9rem; margin: 0; line-height: 1.4; font-weight: 600; color: #444;">
                    Bagian ini mengukur bagaimana aktivitas digital dan kondisi emosional memengaruhi kehidupan sehari-hari.
                </p>
            </div>

            @foreach ($psikologisQuestions as $q)
                @if ($q['step'] === 5)
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
                @endif
            @endforeach

            <!-- BUTTONS -->
            <div class="wizard-actions">
                <button type="button" class="neo-btn neo-btn-secondary btn-prev" style="flex: 1; background-color: var(--yellow); display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <i data-lucide="arrow-left" style="width: 18px; height: 18px;"></i> KEMBALI
                </button>
                <button type="button" class="neo-btn btn-next" style="flex: 2; background-color: var(--green); display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    MULAI SCAN <i data-lucide="scan" style="width: 18px; height: 18px;"></i>
                </button>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- STEP 6: COMPLETION SUMMARY SCREEN -->
        <!-- ========================================== -->
        <div class="wizard-step" data-step="6" id="step-6">
            <div class="neo-box" style="margin-top: 1rem; border-top: 10px solid var(--green);">
                <h2 style="font-size: 1.5rem; line-height: 1.3; margin-bottom: 1.5rem; text-align: center; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <i data-lucide="check-circle" style="width: 28px; height: 28px; color: var(--green);"></i> ASSESSMENT SELESAI
                </h2>

                <div class="completion-layout">
                    <!-- Left Side: Checklist -->
                    <div style="border: 2px solid var(--dark); padding: 1.5rem; background-color: var(--white); box-shadow: 4px 4px 0 var(--dark);">
                        <h3 style="font-size: 1.1rem; margin-bottom: 1rem; border-bottom: 2px solid var(--dark); padding-bottom: 0.5rem;">STATUS DATA</h3>
                        <ul class="completion-checklist" style="list-style: none; padding: 0; margin: 0 0 1.5rem 0;">
                            <li style="font-weight: 700; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; color: #155724; font-size: 0.9rem;">
                                <i data-lucide="check" style="width: 18px; height: 18px; background-color: var(--green); border: 2px solid var(--dark); border-radius: 50%; padding: 1px; color: var(--dark);"></i>
                                17 dari 17 Pertanyaan Terisi
                            </li>
                            <li style="font-weight: 700; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; color: #155724; font-size: 0.9rem;">
                                <i data-lucide="check" style="width: 18px; height: 18px; background-color: var(--green); border: 2px solid var(--dark); border-radius: 50%; padding: 1px; color: var(--dark);"></i>
                                Daily Activities Recorded
                            </li>
                            <li style="font-weight: 700; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; color: #155724; font-size: 0.9rem;">
                                <i data-lucide="check" style="width: 18px; height: 18px; background-color: var(--green); border: 2px solid var(--dark); border-radius: 50%; padding: 1px; color: var(--dark);"></i>
                                Psychological Conditions Recorded
                            </li>
                            <li style="font-weight: 700; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; color: #155724; font-size: 0.9rem;">
                                <i data-lucide="check" style="width: 18px; height: 18px; background-color: var(--green); border: 2px solid var(--dark); border-radius: 50%; padding: 1px; color: var(--dark);"></i>
                                Burnout Assessment Siap Diproses
                            </li>
                            <li style="font-weight: 700; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; color: #155724; font-size: 0.9rem;">
                                <i data-lucide="check" style="width: 18px; height: 18px; background-color: var(--green); border: 2px solid var(--dark); border-radius: 50%; padding: 1px; color: var(--dark);"></i>
                                Data Siap Dianalisis AI
                            </li>
                        </ul>
                        
                        <p style="font-size: 0.9rem; line-height: 1.5; color: #333; margin: 0; font-weight: 600; text-align: justify;">
                            Terima kasih. Sistem telah menerima seluruh jawaban Anda. Langkah berikutnya adalah melakukan analisis ekspresi wajah menggunakan AI Facial Emotion Recognition untuk membantu meningkatkan akurasi hasil analisis.
                        </p>
                    </div>

                    <!-- Right Side: Mini Summary -->
                    <div style="border: 2px solid var(--dark); padding: 1.5rem; background-color: #FFFDE5; box-shadow: 4px 4px 0 var(--dark);">
                        <h3 style="font-size: 1.1rem; margin-bottom: 1rem; border-bottom: 2px solid var(--dark); padding-bottom: 0.5rem; text-transform: uppercase; display: flex; align-items: center; gap: 0.35rem;">
                            <i data-lucide="clipboard-list" style="width: 18px; height: 18px;"></i> Ringkasan Jawaban
                        </h3>
                        
                        <div class="summary-list" style="display: flex; flex-direction: column; gap: 0.65rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed var(--dark); padding-bottom: 0.4rem;">
                                <span style="font-weight: 700; font-size: 0.85rem;">Jam Tidur:</span>
                                <strong id="summary-jam-tidur" style="font-size: 1rem; color: var(--primary); font-weight: 700;">- Jam</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed var(--dark); padding-bottom: 0.4rem;">
                                <span style="font-weight: 700; font-size: 0.85rem;">Screen Time:</span>
                                <strong id="summary-screen-time" style="font-size: 1rem; color: var(--primary); font-weight: 700;">- Jam</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed var(--dark); padding-bottom: 0.4rem;">
                                <span style="font-weight: 700; font-size: 0.85rem;">Mood Rendah:</span>
                                <strong id="summary-mood-rendah" style="font-size: 1rem; color: var(--primary); font-weight: 700;">-/10</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed var(--dark); padding-bottom: 0.4rem;">
                                <span style="font-weight: 700; font-size: 0.85rem;">Kecemasan:</span>
                                <strong id="summary-kecemasan" style="font-size: 1rem; color: var(--primary); font-weight: 700;">-/10</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.1rem;">
                                <span style="font-weight: 700; font-size: 0.85rem;">Overthinking:</span>
                                <strong id="summary-overthinking" style="font-size: 1rem; color: var(--primary); font-weight: 700;">-/10</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit and Back Actions -->
                <div class="wizard-actions" style="margin-top: 2.5rem;">
                    <button type="button" class="neo-btn neo-btn-secondary btn-prev" style="flex: 1; background-color: var(--yellow); display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;">
                        <i data-lucide="arrow-left" style="width: 18px; height: 18px;"></i> PERBAIKI JAWABAN
                    </button>
                    <button type="submit" class="neo-btn" style="flex: 2; background-color: var(--green); display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;">
                        MULAI ANALISIS WAJAH <i data-lucide="scan" style="width: 18px; height: 18px;"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
