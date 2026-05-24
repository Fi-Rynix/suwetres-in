@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/scan.css') }}">
@endsection

@section('content')
<div class="scan-container">

    <div class="scan-header">
        <div class="neo-badge" style="background-color: var(--purple); color: var(--white); margin: 0;">
            TAHAP 2: DETEKSI MUKA KUSUT
        </div>
        <div class="scan-header-text">
            LIVE FER STRESS SCANNER
        </div>
    </div>

    <div class="neo-box" style="background-color: var(--white); padding: 2rem;">
        <h2 class="scan-title">
            FACIAL EXPRESSION ANALYSIS
        </h2>
        <p class="scan-description">
            Sistem akan menganalisis ekspresi wajah selama <b>5 detik</b> menggunakan AI Face-API.js untuk mendeteksi tingkat stress sebagai pendukung perhitungan Fuzzy Sugeno.
        </p>

        <!-- Webcam Container -->
        <div class="webcam-wrapper">
            <video id="webcam" autoplay playsinline muted class="webcam-video"></video>
            <canvas id="overlay" class="webcam-overlay"></canvas>

            <!-- Scanning Overlay -->
            <div id="scanning-overlay" class="scanning-overlay">
                <div class="scanning-line"></div>
            </div>

            <!-- Face Frame Guide (hidden saat detected) -->
            <div id="face-frame" class="face-guide-frame">
                <div class="face-guide-label">
                    TARUH MUKA KUSUT DI SINI
                </div>
            </div>

            <!-- Status Badge on Camera -->
            <div id="cam-status" class="camera-status-badge">
                LOADING AI MODEL...
            </div>

            <!-- Progress Bar Saat Scanning -->
            <div id="scan-progress" class="scan-progress-bar"></div>
        </div>

        <!-- Real-time Emotion Display -->
        <div id="live-emotion" class="live-emotion-panel">
            <div class="live-emotion-title">LIVE EMOTION DETECTION</div>
            <div id="dominant-emotion-text" class="live-emotion-dominant">-</div>
            <div id="emotion-bars" class="live-emotion-bars"></div>
        </div>

        <div class="scan-actions">
            <a href="{{ route('kuisioner') }}" class="neo-btn neo-btn-secondary" style="flex: 1; background-color: var(--yellow);">
                ↩ KEMBALI
            </a>
            <button id="capture-btn" class="neo-btn" style="flex: 2; background-color: var(--primary); color: var(--white); display: none;" disabled>
                MULAI SCAN MUKA KUSUT (5 DETIK)
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- Face-API.js dari CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api@1.7.13/dist/face-api.js"></script>
    <script>
        window.scanConfig = {
            modelsUrl: "{{ asset('models') }}",
            submitFerUrl: "{{ route('scan.submit-fer') }}",
            loadingUrl: "{{ route('loading') }}",
            csrfToken: "{{ csrf_token() }}",
            scanDuration: 5000, // 5 detik
        };
    </script>
    <script defer src="{{ asset('js/scan.js') }}"></script>
@endsection
