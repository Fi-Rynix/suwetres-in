/**
 * Suwetres.in - FER Scanner
 * Menggunakan Face-API.js untuk:
 *   1. Real-time face detection (Tiny Face Detector)
 *   2. Facial expression recognition (7 emotions)
 *   3. Temporal analysis selama 5 detik
 *   4. Hitung emotion variance (instabilitas) & negative duration
 *
 * Output dikirim ke Laravel via AJAX (POST /scan/submit-fer)
 * Lalu redirect ke loading -> process -> result.
 */

(() => {
    "use strict";

    const config = window.scanConfig || {};
    const SCAN_DURATION = config.scanDuration || 5000;
    const DETECTION_INTERVAL = 150; // ms (~6-7 fps detection)
    const NEGATIVE_EMOTIONS = ["sad", "angry", "fearful", "disgusted"];

    // DOM refs
    let video, overlay, captureBtn, camStatus, scanningOverlay,
        faceFrame, liveEmotion, dominantEmotionText, emotionBars,
        scanProgress;

    // State
    let modelsLoaded = false;
    let cameraReady = false;
    let detectionLoopId = null;
    let scanning = false;
    let frameHistory = []; // Array of emotion objects per frame

    // ==============================================================
    // INIT
    // ==============================================================

    document.addEventListener("DOMContentLoaded", async () => {
        cacheDom();
        bindEvents();

        await loadModels();
        await startCamera();
        startLiveDetection();
    });

    function cacheDom() {
        video = document.getElementById("webcam");
        overlay = document.getElementById("overlay");
        captureBtn = document.getElementById("capture-btn");
        camStatus = document.getElementById("cam-status");
        scanningOverlay = document.getElementById("scanning-overlay");
        faceFrame = document.getElementById("face-frame");
        liveEmotion = document.getElementById("live-emotion");
        dominantEmotionText = document.getElementById("dominant-emotion-text");
        emotionBars = document.getElementById("emotion-bars");
        scanProgress = document.getElementById("scan-progress");
    }

    function bindEvents() {
        captureBtn.addEventListener("click", startScan);
    }

    // ==============================================================
    // MODEL LOADING
    // ==============================================================

    async function loadModels() {
        try {
            setStatus("LOADING AI MODEL...", "var(--purple)", "var(--white)");

            // Tunggu face-api siap (defer script)
            await waitForFaceApi();

            const url = config.modelsUrl;
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(url),
                faceapi.nets.faceExpressionNet.loadFromUri(url),
                faceapi.nets.faceLandmark68Net.loadFromUri(url),
            ]);

            modelsLoaded = true;
            setStatus("AI MODEL READY", "var(--green)", "var(--dark)");
        } catch (err) {
            console.error("Gagal load model:", err);
            setStatus("GAGAL LOAD AI MODEL", "var(--primary)", "var(--white)");
            // Tetap aktifkan tombol untuk fallback simulasi
            enableFallbackSimulation();
        }
    }

    function waitForFaceApi(timeout = 8000) {
        return new Promise((resolve, reject) => {
            const start = Date.now();
            const check = () => {
                if (typeof faceapi !== "undefined") return resolve();
                if (Date.now() - start > timeout) {
                    return reject(new Error("face-api.js tidak ter-load"));
                }
                setTimeout(check, 100);
            };
            check();
        });
    }

    // ==============================================================
    // CAMERA
    // ==============================================================

    async function startCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            setStatus("WEBCAM TIDAK DIDUKUNG", "var(--primary)", "var(--white)");
            enableFallbackSimulation();
            return;
        }

        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: { width: 640, height: 480, facingMode: "user" },
            });
            video.srcObject = stream;

            await new Promise((resolve) => {
                video.onloadedmetadata = () => {
                    video.play();
                    resolve();
                };
            });

            // Set canvas size sesuai video
            overlay.width = video.videoWidth;
            overlay.height = video.videoHeight;

            cameraReady = true;
            if (modelsLoaded) {
                setStatus("KAMERA & AI SIAP - ARAHKAN WAJAH", "var(--green)", "var(--dark)");
                captureBtn.style.display = "inline-block";
                captureBtn.disabled = false;
            }
        } catch (err) {
            console.error("Akses kamera gagal:", err);
            setStatus("KAMERA DITOLAK / TIDAK ADA", "var(--primary)", "var(--white)");
            enableFallbackSimulation();
        }
    }

    function stopCamera() {
        if (video && video.srcObject) {
            video.srcObject.getTracks().forEach((t) => t.stop());
        }
    }

    // ==============================================================
    // LIVE DETECTION (Pre-scan)
    // ==============================================================

    function startLiveDetection() {
        if (!modelsLoaded || !cameraReady) return;

        liveEmotion.style.display = "block";

        const tick = async () => {
            if (!modelsLoaded || !cameraReady) return;

            try {
                const result = await detectExpressions();

                if (result) {
                    drawDetection(result);
                    updateLiveEmotion(result.expressions);
                    faceFrame.style.borderColor = "var(--green)";

                    // Capture ke history saat scanning aktif
                    if (scanning) {
                        frameHistory.push(result.expressions);
                    }
                } else {
                    clearOverlay();
                    faceFrame.style.borderColor = "var(--yellow)";
                    if (!scanning) {
                        dominantEmotionText.textContent = "WAJAH TIDAK TERDETEKSI";
                    }
                }
            } catch (err) {
                console.warn("Detection error:", err);
            }

            detectionLoopId = setTimeout(tick, DETECTION_INTERVAL);
        };

        tick();
    }

    async function detectExpressions() {
        const detection = await faceapi
            .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({
                inputSize: 320,
                scoreThreshold: 0.5,
            }))
            .withFaceLandmarks()
            .withFaceExpressions();

        return detection || null;
    }

    function drawDetection(result) {
        const ctx = overlay.getContext("2d");
        ctx.clearRect(0, 0, overlay.width, overlay.height);

        const box = result.detection.box;
        ctx.strokeStyle = "#00FF66";
        ctx.lineWidth = 4;
        ctx.strokeRect(box.x, box.y, box.width, box.height);
    }

    function clearOverlay() {
        const ctx = overlay.getContext("2d");
        ctx.clearRect(0, 0, overlay.width, overlay.height);
    }

    function updateLiveEmotion(expressions) {
        const sorted = Object.entries(expressions).sort((a, b) => b[1] - a[1]);
        const [topEmotion, topScore] = sorted[0];

        const emoji = emotionEmoji(topEmotion);
        dominantEmotionText.textContent = `${emoji} ${topEmotion.toUpperCase()} (${(topScore * 100).toFixed(1)}%)`;

        // Render bars
        emotionBars.innerHTML = "";
        sorted.forEach(([name, score]) => {
            const pct = (score * 100).toFixed(1);
            const isNeg = NEGATIVE_EMOTIONS.includes(name);
            const color = name === "happy"
                ? "var(--green)"
                : isNeg ? "var(--primary)" : "var(--secondary)";

            const row = document.createElement("div");
            row.style.cssText = "display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; font-weight: 600;";
            row.innerHTML = `
                <span style="width: 80px; text-transform: uppercase;">${name}</span>
                <div style="flex: 1; height: 12px; background: #EEE; border: 1.5px solid var(--dark); position: relative;">
                    <div style="width: ${pct}%; height: 100%; background: ${color}; transition: width 0.2s;"></div>
                </div>
                <span style="width: 50px; text-align: right;">${pct}%</span>
            `;
            emotionBars.appendChild(row);
        });
    }

    function emotionEmoji(emotion) {
        const map = {
            neutral: "😐",
            happy: "😊",
            sad: "😢",
            angry: "😠",
            fearful: "😨",
            disgusted: "🤢",
            surprised: "😲",
        };
        return map[emotion] || "❓";
    }

    // ==============================================================
    // SCANNING (5 second capture)
    // ==============================================================

    function startScan() {
        if (scanning) return;

        scanning = true;
        frameHistory = [];

        captureBtn.textContent = "SCANNING...";
        captureBtn.style.backgroundColor = "var(--yellow)";
        captureBtn.style.color = "var(--dark)";
        captureBtn.disabled = true;

        scanningOverlay.style.display = "block";
        setStatus("MENGANALISIS EKSPRESI WAJAH...", "var(--purple)", "var(--white)");

        // Animate progress bar
        const startTime = Date.now();
        const progressInterval = setInterval(() => {
            const elapsed = Date.now() - startTime;
            const pct = Math.min((elapsed / SCAN_DURATION) * 100, 100);
            scanProgress.style.width = pct + "%";

            if (elapsed >= SCAN_DURATION) {
                clearInterval(progressInterval);
                finishScan();
            }
        }, 50);
    }

    async function finishScan() {
        scanning = false;
        if (detectionLoopId) clearTimeout(detectionLoopId);

        setStatus("MEMPROSES HASIL FER...", "var(--secondary)", "var(--dark)");

        const ferData = analyzeFrameHistory(frameHistory);

        try {
            await submitFER(ferData);
            stopCamera();
            window.location.href = config.loadingUrl;
        } catch (err) {
            console.error("Submit FER gagal:", err);
            setStatus("GAGAL KIRIM DATA", "var(--primary)", "var(--white)");
            // Fallback: tetap lanjut ke loading dengan FER kosong
            stopCamera();
            window.location.href = config.loadingUrl;
        }
    }

    // ==============================================================
    // ANALYSIS
    // ==============================================================

    /**
     * Analisis frame history menjadi data agregat:
     *   - Rata-rata 7 emosi
     *   - Dominant emotion
     *   - Variance emosi (stabilitas)
     *   - Durasi emosi negatif (detik)
     */
    function analyzeFrameHistory(frames) {
        if (!frames || frames.length === 0) {
            return { detected: false };
        }

        const emotionKeys = ["neutral", "happy", "sad", "angry", "fearful", "disgusted", "surprised"];

        // 1. Rata-rata tiap emosi
        const avg = {};
        emotionKeys.forEach((key) => {
            const sum = frames.reduce((acc, f) => acc + (f[key] || 0), 0);
            avg[key] = sum / frames.length;
        });

        // 2. Dominant emotion (rata-rata tertinggi)
        const sortedAvg = Object.entries(avg).sort((a, b) => b[1] - a[1]);
        const dominantEmotion = sortedAvg[0][0];
        const dominantScore = sortedAvg[0][1];

        // 3. Emotion variance (rata-rata variance dari semua emosi)
        // Mengukur "betapa fluktuatif" emosi tiap frame
        let totalVariance = 0;
        emotionKeys.forEach((key) => {
            const mean = avg[key];
            const variance = frames.reduce((acc, f) => {
                const diff = (f[key] || 0) - mean;
                return acc + diff * diff;
            }, 0) / frames.length;
            totalVariance += variance;
        });
        const emotionVariance = totalVariance / emotionKeys.length;

        // 4. Negative emotion duration (detik)
        // Hitung berapa frame yang dominant-nya negatif, lalu dikonversi ke detik
        const negativeFrames = frames.filter((f) => {
            const dominant = Object.entries(f).sort((a, b) => b[1] - a[1])[0][0];
            return NEGATIVE_EMOTIONS.includes(dominant);
        }).length;

        const frameDurationSec = SCAN_DURATION / 1000 / frames.length;
        const negativeEmotionDuration = negativeFrames * frameDurationSec;

        return {
            detected: true,
            emotions: avg,
            dominant_emotion: dominantEmotion,
            dominant_emotion_score: dominantScore,
            emotion_variance: Math.min(emotionVariance * 5, 1), // Skala ke 0-1
            negative_emotion_duration: parseFloat(negativeEmotionDuration.toFixed(2)),
            total_frames_analyzed: frames.length,
        };
    }

    // ==============================================================
    // SUBMIT TO LARAVEL
    // ==============================================================

    async function submitFER(data) {
        const response = await fetch(config.submitFerUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": config.csrfToken,
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify(data),
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        return await response.json();
    }

    // ==============================================================
    // FALLBACK (kalau kamera/model gagal)
    // ==============================================================

    function enableFallbackSimulation() {
        captureBtn.style.display = "inline-block";
        captureBtn.disabled = false;
        captureBtn.textContent = "LANJUT TANPA FER";
        captureBtn.onclick = async () => {
            captureBtn.disabled = true;
            captureBtn.textContent = "MEMPROSES...";
            try {
                await submitFER({ detected: false });
            } catch (e) {
                console.warn("Submit fallback gagal:", e);
            }
            window.location.href = config.loadingUrl;
        };
    }

    // ==============================================================
    // UTILITIES
    // ==============================================================

    function setStatus(text, bg, color) {
        if (!camStatus) return;
        camStatus.textContent = text;
        camStatus.style.backgroundColor = bg;
        camStatus.style.color = color;
    }
})();
