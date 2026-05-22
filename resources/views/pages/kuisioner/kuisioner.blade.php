@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/kuisioner.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/kuisioner.js') }}"></script>
@endsection

@section('content')
<div style="max-width: 650px; margin: 1rem auto;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div class="neo-badge" style="background-color: var(--purple); color: var(--white); margin: 0;">
            TAHAP 1: INPUT BEBAN HIDUP
        </div>
        <div style="font-weight: 700; text-transform: uppercase;">
            FORM DATA HARIAN
        </div>
    </div>

    <div class="neo-box" style="background-color: var(--white);">
        <h2 style="font-size: 1.8rem; margin-bottom: 0.5rem; border-bottom: 4px solid var(--dark); padding-bottom: 1rem;">
            SEBERAPA BEBAN HARI INI?
        </h2>
        <p style="font-size: 0.95rem; color: #555; margin-bottom: 2rem; font-weight: 600;">
            Isi kuisioner singkat di bawah ini secara jujur. Jangan gengsi, biar sistem <span style="background-color: var(--yellow); padding: 2px 4px;">Suwetres.in</span> bisa hitung seberapa mendekati batas burnout kamu!
        </p>

        @if ($errors->any())
            <div style="background-color: var(--primary); color: var(--white); border: 4px solid var(--dark); padding: 1.2rem; margin-bottom: 2rem; box-shadow: 4px 4px 0 var(--dark); font-weight: 700;">
                <div style="font-size: 1.1rem; margin-bottom: 0.5rem;">KACAU! DATA FORM TIDAK VALID GAYS!</div>
                <ul style="list-style: square; padding-left: 1.2rem; font-size: 0.95rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('post.kuisioner') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label" for="jam_tidur">
                    Jam Tidur Semalam (0 - 12 Jam)
                </label>
                <input type="number" step="any" min="0" max="12" name="jam_tidur" id="jam_tidur" class="neo-input" 
                       placeholder="Masukkan jumlah jam tidur (contoh: 4)" value="{{ old('jam_tidur') }}" required>
                <small style="font-weight: 600; display: block; margin-top: 0.3rem; color: #555;">Himpunan Fuzzy: Sedikit (0-5 Jam), Cukup (4-8 Jam), Banyak (7-12 Jam)</small>
            </div>

            <div class="form-group">
                <label class="form-label" for="jumlah_tugas">
                    Jumlah Tugas Kuliah (0 - 10 Tugas)
                </label>
                <input type="number" step="any" min="0" max="10" name="jumlah_tugas" id="jumlah_tugas" class="neo-input" 
                       placeholder="Masukkan jumlah tugas hari ini (contoh: 7)" value="{{ old('jumlah_tugas') }}" required>
                <small style="font-weight: 600; display: block; margin-top: 0.3rem; color: #555;">Himpunan Fuzzy: Sedikit (0-3 Tugas), Sedang (2-6 Tugas), Banyak (5-10 Tugas)</small>
            </div>

            <div class="form-group">
                <label class="form-label" for="aktivitas_organisasi">
                    Jam Rapat / Aktivitas Organisasi (0 - 10 Jam)
                </label>
                <input type="number" step="any" min="0" max="10" name="aktivitas_organisasi" id="aktivitas_organisasi" class="neo-input" 
                       placeholder="Masukkan jam aktif organisasi (contoh: 5)" value="{{ old('aktivitas_organisasi') }}" required>
                <small style="font-weight: 600; display: block; margin-top: 0.3rem; color: #555;">Himpunan Fuzzy: Rendah (0-3 Jam), Sedang (2-6 Jam), Tinggi (5-10 Jam)</small>
            </div>

            <div class="form-group">
                <label class="form-label" for="screen_time">
                    Screen Time HP / Laptop (0 - 15 Jam)
                </label>
                <input type="number" step="any" min="0" max="15" name="screen_time" id="screen_time" class="neo-input" 
                       placeholder="Masukkan durasi screen time (contoh: 11)" value="{{ old('screen_time') }}" required>
                <small style="font-weight: 600; display: block; margin-top: 0.3rem; color: #555;">Himpunan Fuzzy: Rendah (0-5 Jam), Sedang (4-9 Jam), Tinggi (8-15 Jam)</small>
            </div>

            <div style="margin-top: 3rem; display: flex; gap: 1.5rem;">
                <a href="{{ route('landing') }}" class="neo-btn neo-btn-secondary" style="flex: 1; background-color: var(--yellow);">
                    ↩ BACK
                </a>
                <button type="submit" class="neo-btn" style="flex: 2; background-color: var(--green);">
                    SCAN MUKA KUSUT
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
