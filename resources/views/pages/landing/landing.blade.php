@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/landing.js') }}"></script>
@endsection

@section('content')
<div class="landing-hero">
    <div class="neo-badge landing-badge">
        Kenali Kondisimu Sebelum Burnout
    </div>
    
    <h1 class="landing-title">
        Tugas Menumpuk? <br>
        Tidur Berkurang? <br>
        <span class="landing-title-highlight">
            SUWETRES.IN
        </span>
    </h1>

    <p class="landing-description">
        Suwetres.in membantu mahasiswa mengenali tingkat kelelahan akademik, stres, dan risiko burnout melalui assessment berbasis aktivitas harian, kondisi psikologis, serta analisis ekspresi wajah berbasis AI.
    </p>

    <div class="landing-action-container">
        <a href="{{ route('kuisioner') }}" class="neo-btn landing-btn">
            <span class="landing-btn-inner">
                MULAI ASSESSMENT
                <svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="landing-btn-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                </svg>
            </span>
        </a>
    </div>

    <!-- Grid info -->
    <div class="landing-grid">
        <div class="neo-box landing-box landing-box-yellow">
            <div class="landing-box-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 2.5rem; height: 2.5rem;">
                    <rect x="3" y="3" width="7" height="7" rx="1.5" fill="none"></rect>
                    <rect x="14" y="3" width="7" height="7" rx="1.5" fill="none"></rect>
                    <rect x="3" y="14" width="7" height="7" rx="1.5" fill="none"></rect>
                    <rect x="14" y="14" width="7" height="7" rx="1.5" fill="none"></rect>
                </svg>
            </div>
            <h3 class="landing-box-title">Aktivitas Harian</h3>
            <p class="landing-box-text">Analisis pola aktivitas seperti jam tidur dan screen time yang dapat memengaruhi tingkat energi, fokus, dan keseimbangan aktivitas sehari-hari.</p>
        </div>

        <div class="neo-box landing-box landing-box-secondary">
            <div class="landing-box-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 2.5rem; height: 2.5rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V9a2 2 0 00-2-2H8a2 2 0 00-2 2v10a2 2 0 002 2zM9 13h.01M15 13h.01M12 7V3m-3 0h6"></path>
                </svg>
            </div>
            <h3 class="landing-box-title">Fuzzy Sugeno</h3>
            <p class="landing-box-text">Menggunakan metode Fuzzy Sugeno untuk mengolah berbagai indikator aktivitas dan psikologis menjadi hasil analisis tingkat fatigue dan burnout yang lebih terukur.</p>
        </div>

        <div class="neo-box landing-box landing-box-purple">
            <div class="landing-box-icon text-white">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 2.5rem; height: 2.5rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316A2.192 2.192 0 0014.515 4H9.485a2.192 2.192 0 00-1.838 1.027l-.82 1.348zM12 11a3 3 0 110 6 3 3 0 010-6z"></path>
                </svg>
            </div>
            <h3 class="landing-box-title text-white">AI Face Analysis</h3>
            <p class="landing-box-text text-white">Memanfaatkan teknologi Facial Emotion Recognition sebagai data pendukung untuk membantu mengidentifikasi kondisi emosional pengguna selama proses assessment.</p>
        </div>
    </div>
</div>
@endsection
