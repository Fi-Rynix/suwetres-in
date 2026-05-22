<h1 align="center">🎭 Suwetres.in</h1>

<p align="center">
<b>Sistem Deteksi Tingkat Stress & Fatigue Mahasiswa</b><br>
<i>Fuzzy Sugeno + Facial Expression Recognition (FER)</i>
</p>

<p align="center">
  <img alt="Laravel" src="https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square&logo=laravel">
  <img alt="PHP" src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php">
  <img alt="MySQL" src="https://img.shields.io/badge/MySQL-8-4479A1?style=flat-square&logo=mysql">
  <img alt="Face-API.js" src="https://img.shields.io/badge/Face--API.js-1.7.13-00FF66?style=flat-square">
  <img alt="UAS" src="https://img.shields.io/badge/Project-UAS%20Praktikum%20AI-FFE500?style=flat-square">
</p>

---

## 📖 Tentang Project

**Suwetres.in** adalah sistem analisis tingkat stress & kelelahan mahasiswa yang menggabungkan dua metode:

1. **🧠 Fuzzy Sugeno (Primary - 70%)** — Analisis aktivitas harian berbasis kuisioner (jam tidur, jumlah tugas, aktivitas organisasi, screen time)
2. **🎭 Facial Expression Recognition (Supporting - 30%)** — Analisis ekspresi wajah real-time selama 5 detik menggunakan Face-API.js untuk deteksi 7 emosi

**Hasil akhir** adalah composite score 0-100 dengan klasifikasi 5-tier (Relaxed → Severe Stress) dan rekomendasi coping yang spesifik berdasarkan emosi dominan.

---

## 🚀 Quick Start

### Prerequisites
- PHP ≥ 8.2
- Composer
- Node.js ≥ 18
- MySQL (via Laragon/XAMPP)

### Installation

```bash
# 1. Clone & install dependencies
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Konfigurasi database di .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

# 4. Run migrations
php artisan migrate

# 5. Run dev server
npm run dev          # Terminal 1: Vite
php artisan serve    # Terminal 2: Laravel
```

Buka `http://127.0.0.1:8000` di browser.

> ⚠️ **Catatan:** Webcam butuh akses via `localhost` atau HTTPS. Browser akan blok kamera di HTTP non-localhost.

---

## 🎯 Flow Aplikasi

```
Landing → Kuisioner → Scan (5s FER) → Loading → Process → Result → Recommendation
```

1. **Landing** — Halaman intro
2. **Kuisioner** — Input 4 parameter aktivitas
3. **Scan** — Webcam aktif, deteksi ekspresi wajah selama 5 detik
4. **Loading** — Transisi visual
5. **Process** — Hitung Fuzzy Sugeno + olah FER + composite scoring
6. **Result** — Dashboard dual-analysis (Fatigue + FER terpisah)
7. **Recommendation** — Saran coping berdasarkan kombinasi hasil

---

## 📚 Dokumentasi

Dokumentasi lengkap ada di folder [`docs/`](./docs/):

| Dokumen | Isi |
|---------|-----|
| [`docs/README.md`](./docs/README.md) | Index dokumentasi |
| [`docs/Fatigue_System_Guide.md`](./docs/Fatigue_System_Guide.md) | Setup project dari nol |
| [`docs/FER_Scanner_Flow.md`](./docs/FER_Scanner_Flow.md) ⭐ | **Cara kerja FER scanner (technical deep-dive)** |
| [`docs/FER_Implementation_Guide.md`](./docs/FER_Implementation_Guide.md) | Quick-start testing fitur FER |

---

## 🛠️ Tech Stack

| Layer | Tech |
|-------|------|
| Backend | Laravel 13, PHP 8.2 |
| Database | MySQL |
| Frontend | Blade + vanilla JS + Tailwind 4 |
| Build | Vite 7 |
| FER | [Face-API.js](https://github.com/vladmandic/face-api) (TinyFaceDetector + FaceLandmark68 + FaceExpressionNet) |
| Design | Neo Brutalism (CSS custom, Space Grotesk font) |

---

## 🧪 Testing Skenario

### Skenario 1: Stress Tinggi
```
Jam tidur: 3, Tugas: 8, Organisasi: 6, Screen time: 12
Saat scan: pasang muka serius/cemberut
Expected: Final score > 70, "High Stress"
```

### Skenario 2: Relaxed
```
Jam tidur: 8, Tugas: 2, Organisasi: 1, Screen time: 4
Saat scan: senyum
Expected: Final score < 30, "Relaxed"
```

### Skenario 3: No FER (Fallback)
```
Tutup kamera atau tolak izin → klik "LANJUT TANPA FER"
Expected: Final score = 100% Fuzzy
```

---

## 🎓 Credit

Project UAS Praktikum AI — implementasi Fuzzy Sugeno orde-0 dengan augmentasi Facial Expression Recognition untuk analisis stress mahasiswa yang lebih kontekstual.

Dibangun dengan ❤️ menggunakan Laravel 13 dan Face-API.js.

---

## 📄 License

MIT License — bebas digunakan untuk keperluan akademik dan non-komersial.
