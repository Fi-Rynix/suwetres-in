@extends('app')

@section('content')
<div style="margin-top: 1rem;">
    <div style="text-align: center; margin-bottom: 3rem;">
        <div class="neo-badge" style="background-color: var(--green); font-size: 1.1rem; padding: 0.5rem 1.5rem;">
            HASIL DIAGNOSIS STRESS DIHITUNG!
        </div>
        <h1 style="font-size: 2.8rem; margin-top: 1rem;">DASHBOARD STRESS SUWETRES.IN</h1>
    </div>

    <!-- Main Results Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: stretch; margin-bottom: 3rem; @media (max-width: 768px) { grid-template-columns: 1fr; }">
        
        <!-- Box 1: Skor & Status -->
        @php
            $statusBg = 'var(--green)';
            $statusColor = 'var(--dark)';
            if ($hasil->status == 'Kelelahan Sedang') {
                $statusBg = 'var(--yellow)';
            } elseif ($hasil->status == 'Kelelahan Tinggi') {
                $statusBg = 'var(--primary)';
                $statusColor = 'var(--white)';
            }
        @endphp
        <div class="neo-box" style="background-color: {{ $statusBg }}; color: {{ $statusColor }}; text-align: center; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 3rem 2rem;">
            <h3 style="font-size: 1.5rem; letter-spacing: 1px; color: {{ $statusColor }};">SKOR STRESS</h3>
            <h1 style="font-size: 6.5rem; margin: 1rem 0; line-height: 1; color: {{ $statusColor }}; text-shadow: 4px 4px 0px var(--dark);">
                {{ number_format($hasil->nilai_fatigue, 1) }}%
            </h1>
            <div style="background-color: var(--white); color: var(--dark); border: var(--border-width) solid var(--dark); box-shadow: 4px 4px 0 var(--dark); padding: 0.6rem 1.5rem; font-size: 1.3rem; font-weight: 700; text-transform: uppercase; transform: rotate(-1deg); display: inline-block;">
                {{ $hasil->status }}
            </div>
        </div>

        <!-- Box 2: Detail Parameter & Stamina Meter -->
        <div class="neo-box" style="background-color: var(--white); text-align: left;">
            <h3 style="font-size: 1.5rem; border-bottom: 4px solid var(--dark); padding-bottom: 0.8rem; margin-bottom: 1.5rem;">
                TRACKING BEBAN KAMU
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <div style="background: #FFFDE5; border: 2px solid var(--dark); padding: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                    <div style="font-size: 0.85rem; font-weight: 700; color: #555;">JAM TIDUR</div>
                    <div style="font-size: 1.4rem; font-weight: 700;">{{ $hasil->jam_tidur }} Jam</div>
                </div>

                <div style="background: #F0F8FF; border: 2px solid var(--dark); padding: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                    <div style="font-size: 0.85rem; font-weight: 700; color: #555;">JUMLAH TUGAS</div>
                    <div style="font-size: 1.4rem; font-weight: 700;">{{ $hasil->jumlah_tugas }} Buah</div>
                </div>

                <div style="background: #FFF0F5; border: 2px solid var(--dark); padding: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                    <div style="font-size: 0.85rem; font-weight: 700; color: #555;">AKTIF ORGANISASI</div>
                    <div style="font-size: 1.4rem; font-weight: 700;">{{ $hasil->aktivitas_organisasi }} Jam</div>
                </div>

                <div style="background: #F5FFFA; border: 2px solid var(--dark); padding: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                    <div style="font-size: 0.85rem; font-weight: 700; color: #555;">SCREEN TIME HP</div>
                    <div style="font-size: 1.4rem; font-weight: 700;">{{ $hasil->screen_time }} Jam</div>
                </div>
            </div>

            <!-- Stamina Meter (100 - fatigue) -->
            @php
                $stamina = 100 - $hasil->nilai_fatigue;
                $staminaBg = 'var(--green)';
                if ($stamina < 30) {
                    $staminaBg = 'var(--primary)';
                } elseif ($stamina < 60) {
                    $staminaBg = 'var(--yellow)';
                }
            @endphp
            <div style="border-top: 4px solid var(--dark); padding-top: 1.5rem;">
                <h4 style="font-size: 1.1rem; display: flex; justify-content: space-between;">
                    <span>METERAN STAMINA (KONDISI BADAN)</span>
                    <span>{{ number_format($stamina, 1) }}%</span>
                </h4>
                <div style="width: 100%; height: 30px; border: var(--border-width) solid var(--dark); box-shadow: 4px 4px 0 var(--dark); background-color: #EEE; overflow: hidden; margin-top: 0.8rem; position: relative;">
                    <div style="width: {{ $stamina }}%; height: 100%; background-color: {{ $staminaBg }}; transition: width 1s ease-in-out;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dummy Analytics Cards -->
    <h3 style="font-size: 1.6rem; margin-bottom: 1.5rem; text-align: left;">DUMMY STRESS ANALYTICS & FUZZY REPORT</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-bottom: 4rem;">
        
        <div class="neo-box" style="background-color: var(--white); margin: 0; padding: 1.5rem;">
            <h4 style="font-size: 1.1rem; border-bottom: 2px solid var(--dark); padding-bottom: 0.5rem; margin-bottom: 1rem;">
                KECENDERUNGAN BEBAN MAHASISWA
            </h4>
            <p style="font-size: 0.9rem; margin-bottom: 0.5rem; font-weight: 600;">
                • Rerata stress mahasiswa minggu ini: <b>58.2% (Suwetres)</b>
            </p>
            <p style="font-size: 0.9rem; margin-bottom: 0.5rem; font-weight: 600;">
                • Screen time Anda di atas rata-rata (+15%).
            </p>
            <p style="font-size: 0.9rem; margin: 0; font-weight: 600;">
                • Tidur Anda sangat kurang dari anjuran sehat.
            </p>
        </div>

        <div class="neo-box" style="background-color: var(--white); margin: 0; padding: 1.5rem;">
            <h4 style="font-size: 1.1rem; border-bottom: 2px solid var(--dark); padding-bottom: 0.5rem; margin-bottom: 1rem;">
                ATURAN YANG DIEVALUASI
            </h4>
            <p style="font-size: 0.9rem; margin-bottom: 0.5rem; font-weight: 600;">
                • Total Rule Basis: <b>5 Aturan Sugeno</b>
            </p>
            <p style="font-size: 0.9rem; margin-bottom: 0.5rem; font-weight: 600;">
                • Rule Dominan: <b>Rule 1 & Rule 4 (α > 0.6)</b>
            </p>
            <p style="font-size: 0.9rem; margin: 0; font-weight: 600;">
                • Hasil Perhitungan: <b>Weighted Average Presisi</b>
            </p>
        </div>

        <div class="neo-box" style="background-color: var(--white); margin: 0; padding: 1.5rem;">
            <h4 style="font-size: 1.1rem; border-bottom: 2px solid var(--dark); padding-bottom: 0.5rem; margin-bottom: 1rem;">
                DB RECORD TRACKING
            </h4>
            <p style="font-size: 0.9rem; margin-bottom: 0.5rem; font-weight: 600;">
                • ID Dosa Akademik MySQL: <b>#{{ $hasil->id }}</b>
            </p>
            <p style="font-size: 0.9rem; margin-bottom: 0.5rem; font-weight: 600;">
                • Waktu Tersimpan: <b>{{ $hasil->created_at->format('d M Y H:i:s') }}</b>
            </p>
            <p style="font-size: 0.9rem; margin: 0; font-weight: 600; color: var(--primary);">
                • DB Status: <b>SAVED TO MYSQL</b>
            </p>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div style="display: flex; gap: 2rem; justify-content: center; margin-top: 2rem;">
        <a href="{{ route('kuisioner') }}" class="neo-btn neo-btn-secondary" style="background-color: var(--yellow);">
            COBA LAGI
        </a>
        <a href="{{ route('recommendation') }}" class="neo-btn" style="background-color: var(--green); display: inline-flex; align-items: center; gap: 0.75rem;">
            LIHAT SOLUSI ANTI-STRESS AI
            <svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 1.25rem; height: 1.25rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
            </svg>
        </a>
    </div>
</div>
@endsection
