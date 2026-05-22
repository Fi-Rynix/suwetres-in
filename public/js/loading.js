document.addEventListener('DOMContentLoaded', () => {
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
            
            let delay = 600; 
            if (currentStep === 2) delay = 900; 
            
            currentStep++;
            setTimeout(runProgress, delay);
        } else {
            // Selesai, redirect ke routes process
            window.location.href = window.loadingRedirectUrl;
        }
    }

    // Start progress
    setTimeout(runProgress, 500);
});
