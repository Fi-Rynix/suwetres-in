// Scan Page Scripts - Suwetres.in
document.addEventListener('DOMContentLoaded', () => {
    const video = document.getElementById('webcam');
    const captureBtn = document.getElementById('capture-btn');
    const camStatus = document.getElementById('cam-status');
    const scanningOverlay = document.getElementById('scanning-overlay');

    // Minta Izin Kamera Browser
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 } })
            .then(stream => {
                video.srcObject = stream;
                camStatus.innerText = "KAMERA HP/LAPTOP AKTIF";
                camStatus.style.backgroundColor = "var(--green)";
                camStatus.style.color = "var(--dark)";
                captureBtn.style.display = "inline-block";
            })
            .catch(err => {
                console.error("Akses Kamera Gagal: ", err);
                camStatus.innerText = "AKSES DIABAIKAN / TIDAK DIDUKUNG";
                camStatus.style.backgroundColor = "var(--primary)";
                camStatus.style.color = "var(--white)";
                // Tetap sediakan jalan pintas simulasi untuk praktikum
                captureBtn.style.display = "inline-block";
                captureBtn.innerText = "SIMULASI SCAN (TANPA KAMERA)";
            });
    } else {
        camStatus.innerText = "WEBCAM API TIDAK DIDUKUNG";
        captureBtn.style.display = "inline-block";
        captureBtn.innerText = "SIMULASI SCAN";
    }

    captureBtn.addEventListener('click', () => {
        captureBtn.innerText = "SCANNING MUKA KUSUT...";
        captureBtn.style.backgroundColor = "var(--yellow)";
        captureBtn.style.color = "var(--dark)";
        captureBtn.disabled = true;
        
        // Show scan animations
        scanningOverlay.style.display = "block";
        camStatus.innerText = "DETEKSI MATA PANDA & BIBIR MANYUN...";
        camStatus.style.backgroundColor = "var(--purple)";
        camStatus.style.color = "var(--white)";

        // Simulasi scan selama 3 detik sebelum lanjut ke loading analysis
        setTimeout(() => {
            // Hentikan webcam stream untuk menghemat resource
            if (video.srcObject) {
                const tracks = video.srcObject.getTracks();
                tracks.forEach(track => track.stop());
            }
            // Use global redirectUrl variable defined in the Blade file
            window.location.href = window.scanRedirectUrl;
        }, 3000);
    });
});
