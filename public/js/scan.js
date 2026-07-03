(() => {
    "use strict";

    const config = window.scanConfig || {};
    const SCAN_DURATION = config.scanDuration || 5000;
    const DETECTION_INTERVAL = 150;
    const NEGATIVE_EMOTIONS = ["sad", "angry", "fearful", "disgusted"];

    let video, overlay, captureBtn, camStatus, scanningOverlay,
        faceFrame, liveEmotion, dominantEmotionText, emotionBars,
        scanProgress;

    let modelsLoaded = false;
    let cameraReady = false;
    let detectionLoopId = null;
    let scanning = false;
    let frameHistory = [];

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

    async function loadModels() {
        try {
            setStatus("LOADING AI MODEL...", "var(--purple)", "var(--white)");

            await waitForFaceApi();

            const url = config.modelsUrl;
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(url),
                faceapi.nets.faceExpressionNet.loadFromUri(url),
                faceapi.nets.faceLandmark68Net.loadFromUri(url),
            ]);

            modelsLoaded = true;
            setStatus("MODEL SIAP", "var(--green)", "var(--dark)");
        } catch (err) {
            console.error("Gagal load model:", err);
            setStatus("MODEL GAGAL - FALLBACK", "var(--primary)", "var(--white)");
            enableFallbackSimulation();
        }
    }

    function waitForFaceApi(retries = 50) {
        return new Promise((resolve, reject) => {
            const check = (i) => {
                if (typeof faceapi !== "undefined") return resolve();
                if (i <= 0) return reject(new Error("face-api timeout"));
                setTimeout(() => check(i - 1), 100);
            };
            check(retries);
        });
    }

    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: { width: 640, height: 480, facingMode: "user" },
                audio: false,
            });
            video.srcObject = stream;
            cameraReady = true;

            video.addEventListener("loadedmetadata", () => {
                overlay.width = video.videoWidth;
                overlay.height = video.videoHeight;
            });
        } catch (err) {
            console.error("Gagal akses kamera:", err);
            setStatus("KAMERA OFFLINE - FALLBACK", "var(--primary)", "var(--white)");
            enableFallbackSimulation();
        }
    }

    function startLiveDetection() {
        if (!modelsLoaded || !cameraReady) return;

        detectFrame();
    }

    async function detectFrame() {
        if (!video || video.paused || video.ended) return;

        try {
            const result = await faceapi
                .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 }))
                .withFaceLandmarks()
                .withFaceExpressions();

            const ctx = overlay.getContext("2d");
            ctx.clearRect(0, 0, overlay.width, overlay.height);

            if (result) {
                const dims = faceapi.matchDimensions(overlay, result, true);
                faceapi.draw.drawDetections(overlay, faceapi.resizeResults(result, dims));
                faceapi.draw.drawFaceLandmarks(overlay, faceapi.resizeResults(result, dims));
                updateLiveEmotion(result.expressions);

                if (!scanning) {
                    captureBtn.style.display = "inline-block";
                    captureBtn.disabled = false;
                    if (faceFrame) faceFrame.style.display = "none";
                    setStatus("WAJAH TERDETEKSI", "var(--green)", "var(--dark)");
                }

                if (scanning) frameHistory.push(result.expressions);
            } else {
                if (!scanning) {
                    captureBtn.style.display = "none";
                    captureBtn.disabled = true;
                    if (faceFrame) faceFrame.style.display = "flex";
                    setStatus("CARI WAJAH...", "var(--secondary)", "var(--dark)");
                }
            }
        } catch (err) {
            console.warn("Detect error:", err);
        }

        if (!scanning) {
            requestAnimationFrame(() => setTimeout(detectFrame, DETECTION_INTERVAL));
        }
    }

    function updateLiveEmotion(expressions) {
        const sorted = Object.entries(expressions).sort((a, b) => b[1] - a[1]);
        const [domKey, domVal] = sorted[0];

        if (dominantEmotionText) {
            dominantEmotionText.textContent = `${domKey.toUpperCase()} (${(domVal * 100).toFixed(1)}%)`;
        }

        if (emotionBars) {
            emotionBars.innerHTML = "";
            const emotionKeys = ["neutral", "happy", "sad", "angry", "fearful", "disgusted", "surprised"];
            const colors = {
                neutral: "#AAAAAA", happy: "var(--green)", sad: "#3366FF",
                angry: "var(--primary)", fearful: "var(--purple)",
                disgusted: "#FF8800", surprised: "var(--secondary)",
            };
            emotionKeys.forEach((key) => {
                const val = (expressions[key] || 0) * 100;
                const row = document.createElement("div");
                row.style.cssText = "display:flex;align-items:center;gap:0.5rem;margin-bottom:0.3rem;font-size:0.75rem;font-weight:700;text-transform:uppercase;";
                row.innerHTML = `
                    <span style="width:70px;">${key}</span>
                    <div style="flex:1;height:10px;background:#eee;border:2px solid var(--dark);">
                        <div style="width:${val}%;height:100%;background:${colors[key]};"></div>
                    </div>
                    <span style="width:40px;text-align:right;">${val.toFixed(0)}%</span>
                `;
                emotionBars.appendChild(row);
            });
        }
    }

    function startScan() {
        if (!modelsLoaded || !cameraReady) return;

        scanning = true;
        frameHistory = [];
        captureBtn.disabled = true;
        captureBtn.textContent = "MEMINDAI... JANGAN BERGERAK!";
        if (scanningOverlay) scanningOverlay.style.display = "block";
        if (liveEmotion) liveEmotion.classList.add("scanning-active");
        setStatus("SCANNING 5 DETIK...", "var(--primary)", "var(--white)");

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
            stopCamera();
            window.location.href = config.loadingUrl;
        }
    }

    // Analisis frame history → rata-rata 7 emosi + variance + durasi negatif.
    function analyzeFrameHistory(frames) {
        if (!frames || frames.length === 0) {
            return { detected: false };
        }

        const emotionKeys = ["neutral", "happy", "sad", "angry", "fearful", "disgusted", "surprised"];

        const avg = {};
        emotionKeys.forEach((key) => {
            const sum = frames.reduce((acc, f) => acc + (f[key] || 0), 0);
            avg[key] = sum / frames.length;
        });

        const sortedAvg = Object.entries(avg).sort((a, b) => b[1] - a[1]);
        const dominantEmotion = sortedAvg[0][0];
        const dominantScore = sortedAvg[0][1];

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
            emotion_variance: Math.min(emotionVariance * 5, 1),
            negative_emotion_duration: parseFloat(negativeEmotionDuration.toFixed(2)),
            total_frames_analyzed: frames.length,
        };
    }

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

    function setStatus(text, bg, color) {
        if (!camStatus) return;
        camStatus.textContent = text;
        camStatus.style.backgroundColor = bg;
        camStatus.style.color = color;
    }

    function stopCamera() {
        if (video && video.srcObject) {
            video.srcObject.getTracks().forEach((t) => t.stop());
        }
    }
})();
