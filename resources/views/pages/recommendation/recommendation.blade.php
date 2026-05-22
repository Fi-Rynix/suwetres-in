@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/recommendation.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/recommendation.js') }}"></script>
@endsection

@section('content')
<div style="max-width: 800px; margin: 1rem auto;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div class="neo-badge" style="background-color: var(--purple); color: var(--white); margin: 0;">
            RITUAL PENYELAMAT STRESS DARI AI
        </div>
        <div style="font-weight: 700; text-transform: uppercase;">
            SOLUSI COPING STRESS
        </div>
    </div>

    <!-- Recommendation Box -->
    <div class="neo-box" style="background-color: var(--white); padding: 2.5rem;">
        <h2 style="font-size: 2rem; border-bottom: 4px solid var(--dark); padding-bottom: 1rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
            <span>TINDAKAN PENYELAMAT DIRI</span>
            <span style="font-size: 1.1rem; background-color: var(--yellow); border: 2px solid var(--dark); padding: 0.2rem 0.8rem; font-weight: 700;">
                BEBAN: {{ $hasil->status }}
            </span>
        </h2>

        @if ($hasil->status == 'Kelelahan Ringan')
            <div style="background-color: var(--green); border: var(--border-width) solid var(--dark); box-shadow: 4px 4px 0 var(--dark); padding: 1.5rem; margin-bottom: 2rem; font-weight: 700;">
                BEBAN HIDUP AMAN! SEHAT SENTOSA JAYA GAYS
            </div>
            <p>
                Kadar stress dan kelelahanmu masih sangat normal. Belum butuh self-reward aneh-aneh yang bikin rekening jebol. Otakmu lagi di mode super-sehat buat nyerap materi kuliah sesulit apapun!
            </p>
            
            <h3 style="font-size: 1.3rem; margin-top: 2rem; margin-bottom: 1rem;">RITUAL PENYELAMAT DIRI HARI INI:</h3>
            <ul style="list-style-type: none; padding: 0; font-weight: 600; font-size: 1.05rem;">
                <li style="background: #F0FAF6; border: 2px solid var(--dark); padding: 0.8rem; margin-bottom: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                    <b>Gaskeun Tugas Berat:</b> Selesaikan proyek coding atau laporan praktikum yang paling mager kamu kerjain sebelum energinya ngedrop.
                </li>
                <li style="background: #F0FAF6; border: 2px solid var(--dark); padding: 0.8rem; margin-bottom: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                    <b>Olahraga Tipis-Tipis:</b> Jalan santai atau sekadar peregangan agar otot tidak kaku gara-gara kelamaan nongkrong.
                </li>
                <li style="background: #F0FAF6; border: 2px solid var(--dark); padding: 0.8rem; margin-bottom: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                    <b>Consitent Sleeping:</b> Pertahankan jam tidurmu semalam yang sudah di angka aman 7-8 jam. Jangan coba-coba begadang nonton anime!
                </li>
            </ul>
        @elseif ($hasil->status == 'Kelelahan Sedang')
            <div style="background-color: var(--yellow); border: var(--border-width) solid var(--dark); box-shadow: 4px 4px 0 var(--dark); padding: 1.5rem; margin-bottom: 2rem; font-weight: 700;">
                BEBAN SEDANG! SEDIKIT LAGI LOGOUT DARI KAMPUS
            </div>
            <p>
                Kombinasi tugas yang mulai tumpuk-tumpuk ditambah kepanitiaan/organisasi dan screen time berlebih mulai menghisap stamina jiwamu. Hati-hati, sebentar lagi burnout mendekat!
            </p>

            <h3 style="font-size: 1.3rem; margin-top: 2rem; margin-bottom: 1rem;">RITUAL PENYELAMAT DIRI HARI INI:</h3>
            <ul style="list-style-type: none; padding: 0; font-weight: 600; font-size: 1.05rem;">
                <li style="background: #FFFDE5; border: 2px solid var(--dark); padding: 0.8rem; margin-bottom: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                    <b>Pomodoro Ritual:</b> Fokus ngerjain tugas 25 menit, terus matiin layar HP/Laptop buat istirahat 5 menit. Jangan main sosmed pas istirahat!
                </li>
                <li style="background: #FFFDE5; border: 2px solid var(--dark); padding: 0.8rem; margin-bottom: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                    <b>Jauhkan HP Setelah Jam 10 Malam:</b> Kurangi screen time iseng scrolling Tiktok/Reels yang tidak berfaedah demi kesehatan matamu.
                </li>
                <li style="background: #FFFDE5; border: 2px solid var(--dark); padding: 0.8rem; margin-bottom: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                    <b>Minum Air Putih Banyak-Banyak:</b> Hidrasi otakmu. Kurangi minum kopi/energi instan yang berlebihan biar jantung gak dugun-dugun.
                </li>
            </ul>
        @else
            <div style="background-color: var(--primary); color: var(--white); border: var(--border-width) solid var(--dark); box-shadow: 4px 4px 0 var(--dark); padding: 1.5rem; margin-bottom: 2rem; font-weight: 700;">
                APOCALYPSE LEVEL STRESS! BAHAYA TINGKAT DEWA GAYS
            </div>
            <p>
                <b>Gawat!</b> Jiwamu sudah menjerit minta tolong karena kurang tidur parah, tugas menumpuk gunung, dan screen time kebablasan. Segera tarik rem darurat sebelum semestermu terbakar habis!
            </p>

            <h3 style="font-size: 1.3rem; margin-top: 2rem; margin-bottom: 1rem; color: var(--primary); font-weight: 700;">RITUAL DARURAT PENYELAMATAN SEGERA:</h3>
            <ul style="list-style-type: none; padding: 0; font-weight: 600; font-size: 1.05rem;">
                <li style="background: #FFF0F5; border: 2px solid var(--dark); padding: 0.8rem; margin-bottom: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                    <b>Wajib Hibernasi (Tidur):</b> Taruh HP di meja sebelah, matikan lampu kamar, tidurlah minimal 8-9 jam malam ini. Kesehatan jauh lebih mahal dari nilai A!
                </li>
                <li style="background: #FFF0F5; border: 2px solid var(--dark); padding: 0.8rem; margin-bottom: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                    <b>Tolak/Tunda Rapat Organisasi:</b> Izin tidak ikut rapat atau menunda pengerjaan tugas yang tidak ber-deadline besok pagi. Tarik nafas dalam-dalam.
                </li>
                <li style="background: #FFF0F5; border: 2px solid var(--dark); padding: 0.8rem; margin-bottom: 0.8rem; box-shadow: 3px 3px 0 var(--dark);">
                    <b>Digital Detox Maksimal:</b> Jauhkan mata dari segala jenis layar monitor/ponsel minimal 1 jam penuh sebelum memejamkan mata di kasur.
                </li>
            </ul>
        @endif

        <div style="margin-top: 3rem; display: flex; gap: 1.5rem; justify-content: center;">
            <a href="{{ route('result') }}" class="neo-btn neo-btn-secondary" style="flex: 1; background-color: var(--yellow);">
                ↩ BACK TO DASHBOARD
            </a>
            <a href="{{ route('landing') }}" class="neo-btn" style="flex: 1; background-color: var(--purple); color: var(--white);">
                KEMBALI KE BERANDA
            </a>
        </div>
    </div>
</div>
@endsection
