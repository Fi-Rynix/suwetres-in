@extends('app')

@section('content')
<div style="max-width: 650px; margin: 5rem auto; text-align: center;">
    
    <div class="neo-box" style="background-color: var(--white); padding: 3.5rem 2rem;">
        <div class="loader-element"></div>
        
        <h1 style="font-size: 2rem; margin-bottom: 1.5rem;" id="loading-title">
            PROSES FUZZY SUGENO
        </h1>

        <div style="background-color: var(--white); border: var(--border-width) solid var(--dark); box-shadow: 5px 5px 0 var(--dark); height: 35px; width: 100%; margin-bottom: 2rem; overflow: hidden; position: relative;">
            <div id="loading-progress" style="width: 0%; height: 100%; background-color: var(--secondary); transition: width 0.15s ease-out;"></div>
        </div>

        <div class="neo-badge" id="loading-status" style="background-color: var(--yellow); font-size: 1.05rem; padding: 0.5rem 1.5rem;">
            Menyiapkan data stress Anda...
        </div>
    </div>
</div>

<style>
    .loader-element {
        width: 60px;
        height: 60px;
        border: var(--border-width) solid var(--dark);
        background-color: var(--yellow);
        box-shadow: 5px 5px 0 var(--dark);
        margin: 0 auto 2rem auto;
        animation: spin-brutal 1.2s infinite cubic-bezier(0.18, 0.89, 0.32, 1.28);
    }

    @keyframes spin-brutal {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection

@section('scripts')
<script>
    const progress = document.getElementById('loading-progress');
    const title = document.getElementById('loading-title');
    const status = document.getElementById('loading-status');

    const statuses = [
        { pct: 15, title: "FUZZIFIKASI INPUT", msg: "Memetakan Jam Tidur yang tipis ke kurva Sedikit/Cukup..." },
        { pct: 35, title: "FUZZIFIKASI INPUT", msg: "Menimbang tumpukan Tugas Kuliah yang bikin pening..." },
        { pct: 55, title: "ENGINE SUWETRES.IN", msg: "Membandingkan 5 rule keramat Fuzzy Sugeno..." },
        { pct: 75, title: "DEFUZZIFIKASI OUTPUT", msg: "Mencari rata-rata tertimbang stress tingkat dewa..." },
        { pct: 90, title: "DATABASE SAVE", msg: "Mengamankan data stress mahasiswa ke database MySQL..." },
        { pct: 100, title: "SELESAI!", msg: "Mengarahkan ke Dashboard Stress Suwetres.in..." }
    ];

    let currentStep = 0;
    
    function runProgress() {
        if (currentStep < statuses.length) {
            const step = statuses[currentStep];
            progress.style.width = step.pct + "%";
            title.innerText = step.title;
            status.innerText = step.msg;
            
            let delay = 600; // standard delay per step
            if (currentStep === 2) delay = 900; // give more feel to fuzzy evaluation
            
            currentStep++;
            setTimeout(runProgress, delay);
        } else {
            // Selesai, redirect ke routes process
            window.location.href = "{{ route('process') }}";
        }
    }

    // Start progress
    setTimeout(runProgress, 500);
</script>
@endsection
