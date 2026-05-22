# 📚 Dokumentasi Suwetres.in

Folder ini berisi seluruh dokumentasi teknis project **Suwetres.in - Sistem Deteksi Tingkat Stress & Fatigue Mahasiswa**.

---

## 📑 Daftar Dokumentasi

### 🏗️ Setup & Foundation

- **[Fatigue_System_Guide.md](./Fatigue_System_Guide.md)**
  Panduan lengkap setup project dari nol: instalasi Laravel, migration, controller, fuzzy logic, hingga blade views Neo Brutalism. Mulai dari sini kalau project di-clone ulang.

### 🎭 Fitur FER (Facial Expression Recognition)

- **[FER_Scanner_Flow.md](./FER_Scanner_Flow.md)** ⭐
  Dokumentasi teknis **lengkap** flow scanner FER. Berisi:
  - High-level architecture
  - Component overview (frontend & backend)
  - Step-by-step flow (9 langkah detail)
  - Data flow diagram
  - Algoritma & formula (Fuzzy Sugeno + FER composite)
  - Database schema
  - API contract
  - Edge cases & error handling
  - Performance & privacy considerations
  - Sequence diagram

- **[FER_Implementation_Guide.md](./FER_Implementation_Guide.md)**
  Quick-start guide untuk testing fitur FER:
  - Checklist file yang sudah diimplementasi
  - Cara testing flow
  - Skenario test (stress tinggi, relaxed, no-FER)
  - Tweaking bobot Fuzzy vs FER
  - Troubleshooting

---

## 🎯 Recommended Reading Order

**Untuk Developer Baru:**
1. `Fatigue_System_Guide.md` — Pahami foundation Fuzzy Sugeno
2. `FER_Scanner_Flow.md` — Pahami arsitektur FER
3. `FER_Implementation_Guide.md` — Test & deploy

**Untuk Reviewer / Penguji UAS:**
1. `FER_Scanner_Flow.md` (bagian 1-3) — Quick understanding
2. `FER_Scanner_Flow.md` (bagian 5) — Algoritma & formula

**Untuk Maintenance / Bug Fix:**
1. `FER_Scanner_Flow.md` (bagian 8 & 10) — Edge cases & file index
2. `FER_Implementation_Guide.md` (Troubleshooting)

---

## 🔧 Tech Stack Summary

| Layer | Tech |
|-------|------|
| Framework | Laravel 13 (PHP 8.2+) |
| Database | MySQL (table `hasil_analisis`) |
| Frontend | Blade + vanilla JS + Tailwind 4 |
| FER Library | [Face-API.js v1.7.13](https://github.com/vladmandic/face-api) (vladmandic fork) |
| AI Models | TinyFaceDetector, FaceLandmark68, FaceExpressionNet (~1.1 MB) |
| Build Tool | Vite 7 |

---

## 📂 Quick File Navigation

```
suwetresin-ai-prak-uas/
├── docs/                                  ← You are here
│   ├── README.md                          ← This index
│   ├── Fatigue_System_Guide.md
│   ├── FER_Scanner_Flow.md
│   └── FER_Implementation_Guide.md
├── app/Http/Controllers/
│   ├── ScanController.php                 ← FER endpoint
│   └── FuzzyController.php                ← Composite scoring
├── app/Models/HasilAnalisis.php           ← Eloquent model
├── database/migrations/
│   ├── 2026_05_18_*_create_hasil_analisis_table.php
│   └── 2026_05_22_*_add_fer_columns_*.php ← FER schema
├── public/
│   ├── js/scan.js                         ← FER client logic
│   └── models/                            ← Face-API.js weights
├── resources/views/pages/
│   ├── scan/scan.blade.php                ← Webcam UI
│   ├── result/result.blade.php            ← Dual analysis view
│   └── recommendation/recommendation.blade.php
└── routes/web.php                         ← Routes (incl. submit-fer)
```
