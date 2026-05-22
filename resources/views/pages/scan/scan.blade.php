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
            LIVE MUKA KUSUT SCANNER
        </div>
    </div>

    <div class="neo-box" style="background-color: var(--white); padding: 2rem;">
        <h2 style="font-size: 1.8rem; margin-bottom: 0.5rem; border-bottom: 4px solid var(--dark); padding-bottom: 1rem;">
            PEMINDAIAN INTEGRITAS MUKA
        </h2>
        <p style="font-size: 0.95rem; color: #555; margin-bottom: 2rem; font-weight: 600;">
            Arahkan wajah kusut, mata ngantuk, dan bibir manyun Anda ke kamera. AI simulasi kami akan mendeteksi tingkat keparahan mata panda Anda untuk melengkapi kalkulasi logika fuzzy.
        </p>

        <!-- Webcam Container -->
        <div style="position: relative; width: 100%; max-width: 500px; margin: 0 auto 2rem auto; border: var(--border-width) solid var(--dark); box-shadow: 6px 6px 0 var(--dark); background-color: var(--dark); overflow: hidden; aspect-ratio: 4/3;">
            <video id="webcam" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1);"></video>
            
            <!-- Scanning Overlay -->
            <div id="scanning-overlay" style="position: absolute; inset: 0; pointer-events: none; border: 4px dashed var(--secondary); display: none;">
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 6px; background-color: var(--secondary); box-shadow: 0 0 15px var(--secondary); animation: scanLine 2s linear infinite;"></div>
            </div>

            <!-- Face Frame Overlay -->
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 200px; height: 260px; border: 4px dashed var(--yellow); border-radius: 50%; pointer-events: none;">
                <div style="color: var(--yellow); font-size: 0.8rem; font-weight: 700; background: var(--dark); padding: 2px 6px; display: inline-block; position: absolute; top: -15px; left: 50%; transform: translateX(-50%); text-transform: uppercase;">
                    TARUH MUKA KUSUT DI SINI
                </div>
            </div>
            
            <!-- Status Badge on Camera -->
            <div id="cam-status" style="position: absolute; bottom: 10px; left: 10px; background-color: var(--primary); color: var(--white); font-weight: 700; border: 2px solid var(--dark); padding: 0.3rem 0.8rem; font-size: 0.85rem; text-transform: uppercase;">
                MENUNGGU AKSES KAMERA HP/LAPTOP...
            </div>
        </div>

        <div style="display: flex; gap: 1.5rem; justify-content: center; max-width: 500px; margin: 0 auto;">
            <a href="{{ route('kuisioner') }}" class="neo-btn neo-btn-secondary" style="flex: 1; background-color: var(--yellow);">
                ↩ KEMBALI
            </a>
            <button id="capture-btn" class="neo-btn" style="flex: 2; background-color: var(--primary); color: var(--white); display: none;">
                MULAI SCAN MUKA KUSUT
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        window.scanRedirectUrl = "{{ route('loading') }}";
    </script>
    <script src="{{ asset('js/scan.js') }}"></script>
@endsection
