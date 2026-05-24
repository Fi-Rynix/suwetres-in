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
                Berapa jam Anda tidur semalam? (0 - 12 Jam)
            </label>
            <input type="number" step="any" min="0" max="12" name="jam_tidur" id="jam_tidur" class="neo-input" 
                   placeholder="Masukkan jumlah jam tidur (contoh: 6)" value="{{ old('jam_tidur') }}" required>
            <div style="display: flex; justify-content: space-between; margin-top: 0.5rem; font-size: 0.75rem; font-weight: 700; color: #555;">
                <span>Sedikit (0-5 Jam)</span>
                <span>Cukup (4-8 Jam)</span>
                <span>Banyak (7-12 Jam)</span>
            </div>
        </div>

        <!-- SCREEN TIME -->
        <div class="question-card activity-card">
            <div class="question-num" style="background-color: var(--yellow); color: var(--dark);">Harian 02</div>
            <label class="form-label" for="screen_time" style="font-size: 1.2rem; margin-bottom: 0.8rem;">
                Berapa lama Anda menatap layar HP/laptop hari ini? (0 - 15 Jam)
            </label>
            <input type="number" step="any" min="0" max="15" name="screen_time" id="screen_time" class="neo-input" 
                   placeholder="Masukkan durasi screen time (contoh: 8)" value="{{ old('screen_time') }}" required>
            <div style="display: flex; justify-content: space-between; margin-top: 0.5rem; font-size: 0.75rem; font-weight: 700; color: #555;">
                <span>Rendah (0-5 Jam)</span>
                <span>Sedang (4-9 Jam)</span>
                <span>Tinggi (8-15 Jam)</span>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- BAGIAN B: KONDISI PSIKOLOGIS (LIKERT 1-10) -->
        <!-- ========================================== -->
        <h2 style="font-size: 1.3rem; margin-top: 3rem; margin-bottom: 1rem; padding-left: 0.5rem; border-left: 8px solid var(--purple); text-transform: uppercase;">
            Bagian B: Kondisi Psikologis Mahasiswa
        </h2>

        @php
            $psikologisQuestions = [
                [
                    'field' => 'fokus_belajar',
                    'num' => 'Psikologis 01',
                    'q' => 'Saya merasa kesulitan fokus saat belajar hari ini.'
                ],
                [
                    'field' => 'kelelahan_setelah_istirahat',
                    'num' => 'Psikologis 02',
                    'q' => 'Saya merasa kelelahan meskipun sudah beristirahat.'
                ],
                [
                    'field' => 'tekanan_tugas',
                    'num' => 'Psikologis 03',
                    'q' => 'Saya merasa tekanan tugas kuliah cukup berat minggu ini.'
                ],
                [
                    'field' => 'keseimbangan_hidup',
                    'num' => 'Psikologis 04',
                    'q' => 'Saya merasa sulit menjaga keseimbangan antara kuliah dan kehidupan pribadi.'
                ],
                [
                    'field' => 'penurunan_produktivitas',
                    'num' => 'Psikologis 05',
                    'q' => 'Saya merasa produktivitas saya menurun akhir-akhir ini.'
                ],
                [
                    'field' => 'kecemasan_deadline',
                    'num' => 'Psikologis 06',
                    'q' => 'Saya merasa cemas terhadap deadline atau tugas yang dimiliki.'
                ],
                [
                    'field' => 'dampak_screen_time',
                    'num' => 'Psikologis 07',
                    'q' => 'Saya merasa screen time memengaruhi kualitas istirahat saya.'
                ],
                [
                    'field' => 'motivasi_kuliah',
                    'num' => 'Psikologis 08',
                    'q' => 'Saya merasa kurang termotivasi dalam menjalani aktivitas kuliah.'
                ],
                [
                    'field' => 'kelelahan_aktivitas',
                    'num' => 'Psikologis 09',
                    'q' => 'Saya merasa mudah lelah saat menjalani aktivitas harian.'
                ],
                [
                    'field' => 'beban_mental',
                    'num' => 'Psikologis 10',
                    'q' => 'Saya merasa kondisi mental saya cukup terbebani akhir-akhir ini.'
                ]
            ];
        @endphp

        @foreach ($psikologisQuestions as $index => $q)
            <div class="question-card">
                <div class="question-num">{{ $q['num'] }}</div>
                <p style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; line-height: 1.4;">
                    {{ $q['q'] }}
                </p>
                
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
                    <span>1 (Sangat Rendah / Tidak Sama Sekali)</span>
                    <span>10 (Sangat Tinggi / Sangat Berat)</span>
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
