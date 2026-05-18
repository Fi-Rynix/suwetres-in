# 🚀 Panduan Lengkap: Sistem Penentuan Tingkat Kelelahan Mahasiswa (Fuzzy Sugeno)

Project ini dibangun menggunakan **Laravel 12**, **Fuzzy Sugeno Orde Nol**, dan desain **Neo Brutalism** yang sangat modern, *colorful*, dan interaktif. Cocok untuk project UAS atau praktikum mahasiswa.

---

## 🛠️ Step 1: Inisiasi Project & Koneksi Database

1. Buka Terminal/Command Prompt, lalu jalankan perintah berikut untuk membuat project Laravel 12:
```bash
composer create-project laravel/laravel fatigue_system
cd fatigue_system
```

2. Buka aplikasi database (seperti phpMyAdmin atau DBeaver), buat database baru bernama `fatigue_system`.
3. Buka file `.env` di root folder project, sesuaikan koneksi database MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fatigue_system
DB_USERNAME=root
DB_PASSWORD=
```

---

## 🗄️ Step 2: Migration & Model

1. Buat Model dan Migration untuk tabel `hasil_analisis`:
```bash
php artisan make:model HasilAnalisis -m
```

2. Buka file migration di `database/migrations/xxxx_xx_xx_xxxxxx_create_hasil_analisis_table.php`, ubah kodenya menjadi:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_analisis', function (Blueprint $table) {
            $table->id();
            $table->integer('jam_tidur');
            $table->integer('jumlah_tugas');
            $table->integer('aktivitas_organisasi');
            $table->integer('screen_time');
            $table->float('nilai_fatigue');
            $table->string('status'); // Kelelahan Ringan / Sedang / Tinggi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_analisis');
    }
};
```

3. Jalankan migration untuk membuat tabel di database:
```bash
php artisan migrate
```

4. Buka file `app/Models/HasilAnalisis.php` dan tambahkan `fillable`:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilAnalisis extends Model
{
    protected $table = 'hasil_analisis';
    protected $fillable = [
        'jam_tidur', 'jumlah_tugas', 'aktivitas_organisasi', 
        'screen_time', 'nilai_fatigue', 'status'
    ];
}
```

---

## 🧠 Step 3: Controller & Logika Fuzzy Sugeno

1. Buat controller `FatigueController`:
```bash
php artisan make:controller FatigueController
```

2. Buka `app/Http/Controllers/FatigueController.php` dan isi dengan kode berikut:
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilAnalisis;

class FatigueController extends Controller
{
    public function landing() {
        return view('landing');
    }

    public function kuisioner() {
        return view('kuisioner');
    }

    public function postKuisioner(Request $request) {
        $validated = $request->validate([
            'jam_tidur' => 'required|numeric|min:0|max:12',
            'jumlah_tugas' => 'required|numeric|min:0|max:10',
            'aktivitas_organisasi' => 'required|numeric|min:0|max:10',
            'screen_time' => 'required|numeric|min:0|max:15',
        ]);

        // Simpan sementara di session
        session(['data_kuisioner' => $validated]);
        return redirect()->route('scan');
    }

    public function scan() {
        if (!session('data_kuisioner')) return redirect()->route('kuisioner');
        return view('scan');
    }

    public function loading() {
        return view('loading');
    }

    public function processFuzzy() {
        $data = session('data_kuisioner');
        if (!$data) return redirect()->route('kuisioner');

        $jam_tidur = $data['jam_tidur'];
        $jumlah_tugas = $data['jumlah_tugas'];
        $aktivitas = $data['aktivitas_organisasi'];
        $screen_time = $data['screen_time'];

        // Fuzzifikasi
        // Jam Tidur
        $tidur_sedikit = $this->membershipTurun($jam_tidur, 4, 5);
        $tidur_cukup = $this->membershipSegitiga($jam_tidur, 4, 6, 8);
        $tidur_banyak = $this->membershipNaik($jam_tidur, 7, 8);

        // Jumlah Tugas
        $tugas_sedikit = $this->membershipTurun($jumlah_tugas, 2, 4);
        $tugas_sedang = $this->membershipSegitiga($jumlah_tugas, 2, 4, 6);
        $tugas_banyak = $this->membershipNaik($jumlah_tugas, 5, 6);

        // Aktivitas Organisasi
        $org_rendah = $this->membershipTurun($aktivitas, 2, 4);
        $org_sedang = $this->membershipSegitiga($aktivitas, 2, 4, 6);
        $org_tinggi = $this->membershipNaik($aktivitas, 5, 6);

        // Screen Time
        $screen_rendah = $this->membershipTurun($screen_time, 4, 5);
        $screen_sedang = $this->membershipSegitiga($screen_time, 4, 6.5, 9);
        $screen_tinggi = $this->membershipNaik($screen_time, 8, 9);

        // Rules & Inferensi Sugeno Orde Nol
        $rules = [];
        // Rule 1
        $rules[] = ['alpha' => min($tidur_sedikit, $tugas_banyak), 'z' => 80];
        // Rule 2
        $rules[] = ['alpha' => min($tidur_cukup, $org_rendah), 'z' => 25];
        // Rule 3
        $rules[] = ['alpha' => min($screen_tinggi, $tugas_sedang), 'z' => 50];
        // Rule 4
        $rules[] = ['alpha' => min($tidur_sedikit, $org_tinggi), 'z' => 80];
        // Rule 5
        $rules[] = ['alpha' => min($tidur_banyak, $tugas_sedikit), 'z' => 25];

        // Defuzzifikasi Weighted Average
        $pembilang = 0;
        $penyebut = 0;
        foreach($rules as $r) {
            $pembilang += ($r['alpha'] * $r['z']);
            $penyebut += $r['alpha'];
        }

        $nilai_fatigue = $penyebut == 0 ? 0 : round($pembilang / $penyebut, 2);

        // Interpretasi Output
        if ($nilai_fatigue <= 40) $status = "Kelelahan Ringan";
        elseif ($nilai_fatigue <= 70) $status = "Kelelahan Sedang";
        else $status = "Kelelahan Tinggi";

        // Simpan ke Database
        $hasil = HasilAnalisis::create([
            'jam_tidur' => $jam_tidur,
            'jumlah_tugas' => $jumlah_tugas,
            'aktivitas_organisasi' => $aktivitas,
            'screen_time' => $screen_time,
            'nilai_fatigue' => $nilai_fatigue,
            'status' => $status
        ]);

        session(['hasil_id' => $hasil->id]);
        return redirect()->route('result');
    }

    public function result() {
        $hasil = HasilAnalisis::find(session('hasil_id'));
        if (!$hasil) return redirect()->route('landing');
        return view('result', compact('hasil'));
    }

    public function recommendation() {
        $hasil = HasilAnalisis::find(session('hasil_id'));
        if (!$hasil) return redirect()->route('landing');
        return view('recommendation', compact('hasil'));
    }

    // --- Helper Functions Himpunan Fuzzy ---
    private function membershipTurun($x, $a, $b) {
        if ($x <= $a) return 1;
        if ($x >= $b) return 0;
        return ($b - $x) / ($b - $a);
    }

    private function membershipNaik($x, $a, $b) {
        if ($x <= $a) return 0;
        if ($x >= $b) return 1;
        return ($x - $a) / ($b - $a);
    }

    private function membershipSegitiga($x, $a, $b, $c) {
        if ($x <= $a || $x >= $c) return 0;
        if ($x > $a && $x <= $b) return ($x - $a) / ($b - $a);
        if ($x > $b && $x < $c) return ($c - $x) / ($c - $b);
        return 0;
    }
}
```

---

## 🌐 Step 4: Routing

Buka `routes/web.php` dan tambahkan routing berikut:
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FatigueController;

Route::get('/', [FatigueController::class, 'landing'])->name('landing');
Route::get('/kuisioner', [FatigueController::class, 'kuisioner'])->name('kuisioner');
Route::post('/kuisioner', [FatigueController::class, 'postKuisioner'])->name('post.kuisioner');
Route::get('/scan', [FatigueController::class, 'scan'])->name('scan');
Route::get('/loading', [FatigueController::class, 'loading'])->name('loading');
Route::get('/process', [FatigueController::class, 'processFuzzy'])->name('process');
Route::get('/result', [FatigueController::class, 'result'])->name('result');
Route::get('/recommendation', [FatigueController::class, 'recommendation'])->name('recommendation');
```

---

## 🎨 Step 5: Layout & CSS Neo Brutalism

Buat struktur folder `resources/views/` sehingga terdapat file:
- `app.blade.php` (Layout)
- `landing.blade.php`
- `kuisioner.blade.php`
- `scan.blade.php`
- `loading.blade.php`
- `result.blade.php`
- `recommendation.blade.php`

### 1. `resources/views/app.blade.php`
Ini adalah template utama yang berisi global CSS Neo Brutalism.
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fatigue Analysis System</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #fdf5df;
            --primary: #ff5e5b;
            --secondary: #00cecb;
            --yellow: #ffed66;
            --black: #111;
            --border-width: 4px;
            --shadow-offset: 6px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Space Grotesk', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--black);
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            flex: 1;
        }

        .neo-box {
            background-color: #fff;
            border: var(--border-width) solid var(--black);
            box-shadow: var(--shadow-offset) var(--shadow-offset) 0 var(--black);
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            transition: transform 0.2s;
        }

        .neo-btn {
            background-color: var(--primary);
            color: var(--black);
            font-size: 1.2rem;
            font-weight: bold;
            padding: 1rem 2rem;
            border: var(--border-width) solid var(--black);
            box-shadow: var(--shadow-offset) var(--shadow-offset) 0 var(--black);
            cursor: pointer;
            text-transform: uppercase;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
            border-radius: 4px;
        }

        .neo-btn:hover {
            transform: translate(-2px, -2px);
            box-shadow: 8px 8px 0 var(--black);
        }

        .neo-btn:active {
            transform: translate(4px, 4px);
            box-shadow: 2px 2px 0 var(--black);
        }

        .neo-input {
            width: 100%;
            padding: 1rem;
            font-size: 1.1rem;
            border: var(--border-width) solid var(--black);
            border-radius: 4px;
            box-shadow: 4px 4px 0 var(--black);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        .neo-input:focus {
            outline: none;
            background-color: #f0f8ff;
        }

        h1, h2, h3 { font-weight: 700; text-transform: uppercase; }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background-color: var(--yellow);
            border-bottom: var(--border-width) solid var(--black);
        }

        .badge {
            background: var(--secondary);
            padding: 0.5rem 1rem;
            border: 2px solid var(--black);
            font-weight: bold;
            box-shadow: 3px 3px 0 var(--black);
            display: inline-block;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h2>⚡ Fatigue AI</h2>
        <div><b>Fuzzy Sugeno V1.0</b></div>
    </nav>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
```

### 2. `resources/views/landing.blade.php`
```html
@extends('app')

@section('content')
<div style="text-align: center; margin-top: 4rem;">
    <div class="badge" style="background: var(--yellow);">Tugas Akhir / Praktikum AI</div>
    <h1 style="font-size: 4rem; margin-bottom: 1rem;">Sistem Deteksi<br><span style="color: var(--primary);">Kelelahan Mahasiswa</span></h1>
    <p style="font-size: 1.2rem; margin-bottom: 3rem; font-weight: 600;">Analisis tingkat kelelahanmu berdasarkan aktivitas harian dan AI Face Scan menggunakan metode Fuzzy Sugeno.</p>
    
    <a href="{{ route('kuisioner') }}" class="neo-btn" style="background: var(--secondary);">Mulai Analisis Sekarang</a>
</div>

<div style="display: flex; gap: 2rem; margin-top: 5rem; justify-content: center;">
    <div class="neo-box" style="background: #e2a0ff; width: 300px;">
        <h3>📊 Fuzzy Sugeno</h3>
        <p>Menggunakan sistem inferensi cerdas untuk perhitungan yang akurat.</p>
    </div>
    <div class="neo-box" style="background: #80ffdb; width: 300px;">
        <h3>📷 AI Face Scan</h3>
        <p>Simulasi pemindaian wajah melalui webcam browser.</p>
    </div>
</div>
@endsection
```

### 3. `resources/views/kuisioner.blade.php`
```html
@extends('app')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div class="neo-box" style="background: #fff;">
        <h2 style="margin-bottom: 2rem;">📝 Isi Kuisioner Aktivitas</h2>
        
        @if ($errors->any())
            <div style="background: var(--primary); padding: 1rem; border: 3px solid black; margin-bottom: 1rem; font-weight:bold;">
                Isi form dengan benar! (Sesuai batas maksimal)
            </div>
        @endif

        <form action="{{ route('post.kuisioner') }}" method="POST">
            @csrf
            <label><b>Jam Tidur Semalam (0-12 jam)</b></label>
            <input type="number" name="jam_tidur" class="neo-input" placeholder="Contoh: 6" required>

            <label><b>Jumlah Tugas (0-10 tugas)</b></label>
            <input type="number" name="jumlah_tugas" class="neo-input" placeholder="Contoh: 4" required>

            <label><b>Aktivitas Organisasi / Kepanitiaan (0-10 jam)</b></label>
            <input type="number" name="aktivitas_organisasi" class="neo-input" placeholder="Contoh: 3" required>

            <label><b>Screen Time Gadget (0-15 jam)</b></label>
            <input type="number" name="screen_time" class="neo-input" placeholder="Contoh: 8" required>

            <button type="submit" class="neo-btn" style="width: 100%; margin-top: 1rem;">Lanjut ke Face Scan ➔</button>
        </form>
    </div>
</div>
@endsection
```

### 4. `resources/views/scan.blade.php`
Menggunakan Webcam Browser API untuk mensimulasikan face scan.
```html
@extends('app')

@section('content')
<div style="text-align: center;">
    <h2 style="margin-bottom: 1rem;">📷 Tahap 2: AI Face Scan Simulasi</h2>
    <p>Arahkan wajah Anda ke kamera untuk dianalisis...</p>
    
    <div class="neo-box" style="display: inline-block; background: #000; padding: 1rem; margin-top: 2rem;">
        <video id="webcam" autoplay playsinline style="width: 640px; height: 480px; border: 4px solid #fff;"></video>
        <div id="scan-overlay" style="display:none; position:absolute; border: 4px solid var(--secondary); box-shadow: 0 0 20px var(--secondary); top: 30%; left: 40%; width: 20%; height: 40%;"></div>
    </div>

    <div style="margin-top: 2rem;">
        <button id="capture-btn" class="neo-btn">🔴 Mulai Scan Wajah</button>
    </div>
</div>

<script>
    const video = document.getElementById('webcam');
    const captureBtn = document.getElementById('capture-btn');

    // Minta Izin Kamera
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => { video.srcObject = stream; })
        .catch(err => { alert("Kamera tidak ditemukan / izin ditolak!"); });

    captureBtn.addEventListener('click', () => {
        captureBtn.innerText = "⏳ Menganalisis...";
        captureBtn.style.background = "var(--yellow)";
        captureBtn.disabled = true;

        // Simulasi proses scanning 3 detik
        setTimeout(() => {
            window.location.href = "{{ route('loading') }}";
        }, 3000);
    });
</script>
@endsection
```

### 5. `resources/views/loading.blade.php`
```html
@extends('app')

@section('content')
<div style="text-align: center; margin-top: 10rem;">
    <h1 style="font-size: 3rem;" id="loading-text">⚙️ Processing Fuzzy Sugeno...</h1>
    <div style="width: 50%; height: 30px; border: 4px solid black; margin: 2rem auto; background: white; box-shadow: 6px 6px 0 black;">
        <div id="progress-bar" style="width: 0%; height: 100%; background: var(--secondary); transition: width 0.1s;"></div>
    </div>
</div>

<script>
    let width = 0;
    const bar = document.getElementById('progress-bar');
    
    const interval = setInterval(() => {
        width += 5;
        bar.style.width = width + '%';
        if (width >= 100) {
            clearInterval(interval);
            window.location.href = "{{ route('process') }}"; // Memanggil logic perhitungan
        }
    }, 100); // Durasi loading 2 detik
</script>
@endsection
```

### 6. `resources/views/result.blade.php`
```html
@extends('app')

@section('content')
<div style="text-align: center;">
    <div class="badge" style="background: var(--yellow);">Hasil Analisis Tersimpan</div>
    <h1 style="font-size: 3rem; margin-bottom: 2rem;">Dashboard Kelelahan</h1>

    <div style="display: flex; gap: 2rem; justify-content: center; align-items: stretch;">
        <!-- Nilai Output Box -->
        <div class="neo-box" style="background: {{ $hasil->nilai_fatigue > 70 ? 'var(--primary)' : ($hasil->nilai_fatigue > 40 ? 'var(--yellow)' : '#80ffdb') }}; width: 400px;">
            <h2>Skor Fatigue</h2>
            <h1 style="font-size: 5rem; margin: 1rem 0;">{{ $hasil->nilai_fatigue }}</h1>
            <h3 style="background: white; border: 2px solid black; display:inline-block; padding: 0.5rem 1rem;">
                STATUS: {{ $hasil->status }}
            </h3>
        </div>

        <!-- Detail Input Box -->
        <div class="neo-box" style="width: 400px; text-align: left; background: #fff;">
            <h3>📊 Dummy Analytics</h3>
            <hr style="border: 2px solid black; margin: 1rem 0;">
            <p><b>Jam Tidur:</b> {{ $hasil->jam_tidur }} Jam</p>
            <p><b>Tugas:</b> {{ $hasil->jumlah_tugas }} Buah</p>
            <p><b>Organisasi:</b> {{ $hasil->aktivitas_organisasi }} Jam</p>
            <p><b>Screen Time:</b> {{ $hasil->screen_time }} Jam</p>

            <div style="margin-top: 1.5rem;">
                <b>Energy Meter:</b>
                <div style="width: 100%; height: 20px; border: 2px solid black; background: #eee; margin-top: 0.5rem;">
                    <div style="width: {{ 100 - $hasil->nilai_fatigue }}%; height: 100%; background: #00cecb;"></div>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top: 3rem;">
        <a href="{{ route('recommendation') }}" class="neo-btn">Lihat Rekomendasi AI ➔</a>
    </div>
</div>
@endsection
```

### 7. `resources/views/recommendation.blade.php`
```html
@extends('app')

@section('content')
<div style="max-width: 800px; margin: 0 auto; text-align: center;">
    <h1 style="margin-bottom: 2rem;">💡 Rekomendasi Aktivitas</h1>
    
    <div class="neo-box" style="background: #fff; text-align: left; font-size: 1.2rem; line-height: 1.6;">
        @if ($hasil->nilai_fatigue <= 40)
            <h2 style="color: #00cecb;">Kondisi Prima!</h2>
            <p>Tingkat kelelahan Anda sangat rendah. Terus pertahankan pola tidur dan aktivitas Anda. Anda sangat siap untuk mengerjakan tugas berat hari ini.</p>
            <ul>
                <li>Lakukan olahraga ringan.</li>
                <li>Fokus pada produktivitas maksimal.</li>
            </ul>
        @elseif ($hasil->nilai_fatigue <= 70)
            <h2 style="color: #d4a017;">Kelelahan Sedang</h2>
            <p>Anda mulai merasa capek. Sebaiknya kurangi screen time dan cicil tugas Anda perlahan.</p>
            <ul>
                <li>Gunakan teknik Pomodoro (kerja 25 menit, istirahat 5 menit).</li>
                <li>Lakukan peregangan (stretching) agar otot tidak kaku.</li>
                <li>Tidur lebih awal malam ini.</li>
            </ul>
        @else
            <h2 style="color: var(--primary);">Bahaya Kelelahan Tinggi!</h2>
            <p>Tubuh Anda memberikan sinyal *warning*. Segera hentikan aktivitas berat!</p>
            <ul>
                <li><b>Wajib:</b> Tidur minimal 8 jam malam ini.</li>
                <li>Matikan semua gadget 1 jam sebelum tidur.</li>
                <li>Tunda tugas yang tidak mendesak. Kesehatan adalah prioritas utama!</li>
            </ul>
        @endif
    </div>

    <a href="{{ route('landing') }}" class="neo-btn" style="background: var(--yellow); margin-top: 2rem;">↩ Kembali ke Beranda</a>
</div>
@endsection
```

---

## 🚀 Cara Menjalankan Project (Testing)

1. Pastikan XAMPP/Laragon berjalan (Apache & MySQL aktif).
2. Buka terminal di dalam folder `fatigue_system`
3. Jalankan server lokal Laravel:
```bash
php artisan serve
```
4. Buka browser dan akses url: **http://127.0.0.1:8000**
5. Alur Testing:
   - Klik **Mulai Analisis Sekarang**
   - Isi form (misal: Tidur 3 jam, Tugas 8, Organisasi 5, Screen Time 12) -> Akan menghasilkan kelelahan tinggi.
   - Klik Lanjut -> Berikan izin Webcam -> Klik Mulai Scan
   - Tunggu Progress Bar Loading -> Sistem memproses Fuzzy Sugeno
   - Anda akan diarahkan ke Dashboard Hasil yang menampilkan UI Neo Brutalism interaktif
   - Klik Lihat Rekomendasi untuk melihat saran dari AI.

🎉 **Selamat! Project UAS "Sistem Penentuan Tingkat Kelelahan Mahasiswa Menggunakan Fuzzy Sugeno" telah selesai dibuat.**
