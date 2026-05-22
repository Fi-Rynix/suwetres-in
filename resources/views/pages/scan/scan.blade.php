@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/scan.css') }}">
@endsection

@section('content')
<div style="max-width: 750px; margin: 1rem auto; text-align: center;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div class="neo-badge" style="background-color: var(--purple); color: var(--white); margin: 0;">
            TAHAP 2: DETEKSI MUKA KUSUT
        </div>
        <div style="font-weight: 700; text-transform: uppercase;">
            LIVE FER STRESS SCANNER
        </div>
    </div>

    <div class="neo-box" style="background-color: var(--white); padding: 2rem;">
        <h2 style="font-size: 1.8rem; margin-bottom: 0.5rem; border-bottom: 4px solid var(--dark); padding-bottom: 1rem;">
            FACIAL EXPRESSION ANALYSIS
        </h2>
        <p style="font-size: 0.95rem; color: #555; margin-bottom: 2rem; font-weight: 600;">
            Sistem akan menganalisis ekspresi wajah selama <b>5 detik</b> menggunakan AI Face-API.js untuk mendeteksi tingkat stress sebagai pendukung perhitungan Fuzzy Sugeno.
        </p>

        <!-- Webcam Container -->
        <div style="position: relative; width: 100%; max-width: 500px; margin: 0 auto 2rem auto; border: var(--border-width) solid var(--dark); box-shadow: 6px 6px 0 var(--dark); background-color: var(--dark); overflow: hidden; aspect-ratio: 4/3;">
            <video id="webcam" autoplay playsinline muted style="width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1);"></video>
            <canvas id="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; transform: scaleX(-1); pointer-events: none;"></canvas>

            <!-- Scanning Overlay -->
            <div id="scanning-overlay" style="position: absolute; inset: 0; pointer-events: none; border: 4px dashed var(--secondary); display: none;">
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 6px; background-color: var(--secondary); box-shadow: 0 0 15px var(--secondary); animation: scanLine 2s linear infinite;"></div>
            </div>

            <!-- Face Frame Guide (hidden saat detected) -->
            <div id="face-frame" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 200px; height: 260px; border: 4px dashed var(--yellow); border-radius: 50%; pointer-events: none;">
                <div style="color: var(--yellow); font-size: 0.8rem; font-weight: 700; background: var(--dark); padding: 2px 6px; display: inline-block; position: absolute; top: -15px; left: 50%; transform: translateX(-50%); text-transform: uppercase;">
                    TARUH MUKA KUSUT DI SINI
                </div>
            </div>

            <!-- Status Badge on Camera -->
            <div id="cam-status" style="position: absolute; bottom: 10px; left: 10px; background-color: var(--primary); color: var(--white); font-weight: 700; border: 2px solid var(--dark); padding: 0.3rem 0.8rem; font-size: 0.85rem; text-transform: uppercase;">
                LOADING AI MODEL...
            </div>

            <!-- Progress Bar Saat Scanning -->
            <div id="scan-progress" style="position: absolute; bottom: 0; left: 0; width: 0%; height: 8px; background-color: var(--green); transition: width 0.1s linear;"></div>
        </div>

        <!-- Real-time Emotion Display -->
        <div id="live-emotion" style="display: none; background: #FFFDE5; border: 3px solid var(--dark); box-shadow: 4px 4px 0 var(--dark); padding: 1rem; margin-bottom: 1.5rem; max-width: 500px; margin-left: auto; margin-right: auto;">
            <div style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">LIVE EMOTION DETECTION</div>
            <div id="dominant-emotion-text" style="font-size: 1.5rem; font-weight: 700;">-</div>
            <div id="emotion-bars" style="margin-top: 0.8rem; display: flex; flex-direction: column; gap: 0.3rem; text-align: left;"></div>
        </div>

        <div style="display: flex; gap: 1.5rem; justify-content: center; max-width: 500px; margin: 0 auto;">
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
