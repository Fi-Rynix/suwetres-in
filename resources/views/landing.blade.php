@extends('app')

@section('content')
<div style="text-align: center; margin-top: 2rem; margin-bottom: 4rem;">
    <div class="neo-badge" style="background-color: var(--yellow); transform: rotate(-1deg); font-size: 1.1rem; padding: 0.6rem 1.5rem;">
        SUWE-SUWE STRESS? DETEKSI DINI DULU GUYS!
    </div>
    
    <h1 style="font-size: 4rem; line-height: 1.1; margin: 1.5rem 0 2rem 0; text-transform: uppercase;">
        Kuliah Bikin <br>
        <span style="background-color: var(--primary); color: var(--white); padding: 0 15px; display: inline-block; transform: rotate(1deg); margin-top: 10px; box-shadow: 4px 4px 0 var(--dark);">
            SUWETRES.IN?
        </span>
    </h1>

    <p style="font-size: 1.3rem; max-width: 800px; margin: 0 auto 3rem auto; font-weight: 600; line-height: 1.6;">
        Gabungan kata <span style="background-color: var(--secondary); padding: 0 5px;">Suwe</span> + <span style="background-color: var(--purple); color: white; padding: 0 5px;">Stress</span>. Ukur kadar kelelahan akademikmu berdasarkan kurang tidur, tumpukan tugas, kepanitiaan toxic, dan kebanyakan scroll medsos menggunakan logika Fuzzy Sugeno Orde Nol yang cerdas!
    </p>

    <div style="margin-bottom: 4rem;">
        <a href="{{ route('kuisioner') }}" class="neo-btn" style="padding: 1.3rem 3rem; font-size: 1.5rem; background-color: var(--green); display: inline-block;">
            <span style="display: inline-flex; align-items: center; gap: 0.75rem;">
                MULAI CEK KADAR STRESS
                <svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 1.5rem; height: 1.5rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                </svg>
            </span>
        </a>
    </div>

    <!-- Grid info -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 2rem;">
        <div class="neo-box" style="background-color: var(--yellow); text-align: left;">
            <div style="margin-bottom: 1rem; color: var(--dark);">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 2.5rem; height: 2.5rem;">
                    <rect x="3" y="3" width="7" height="7" rx="1.5" fill="none"></rect>
                    <rect x="14" y="3" width="7" height="7" rx="1.5" fill="none"></rect>
                    <rect x="3" y="14" width="7" height="7" rx="1.5" fill="none"></rect>
                    <rect x="14" y="14" width="7" height="7" rx="1.5" fill="none"></rect>
                </svg>
            </div>
            <h3 style="font-size: 1.4rem;">4 Indikator Capek</h3>
            <p style="font-size: 0.95rem; margin: 0; font-weight: 600;">Pantau jam tidur pas-pasan, deadline tugas menumpuk, rapat organisasi non-stop, dan durasi screen time seharian.</p>
        </div>

        <div class="neo-box" style="background-color: var(--secondary); text-align: left;">
            <div style="margin-bottom: 1rem; color: var(--dark);">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 2.5rem; height: 2.5rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V9a2 2 0 00-2-2H8a2 2 0 00-2 2v10a2 2 0 002 2zM9 13h.01M15 13h.01M12 7V3m-3 0h6"></path>
                </svg>
            </div>
            <h3 style="font-size: 1.4rem;">Fuzzy Engine</h3>
            <p style="font-size: 0.95rem; margin: 0; font-weight: 600;">Inferensi logika cerdas untuk menghitung seberapa mepet kamu ke arah burnout tanpa tebak-tebakan.</p>
        </div>

        <div class="neo-box" style="background-color: var(--purple); color: var(--white); text-align: left;">
            <div style="margin-bottom: 1rem; color: var(--white);">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 2.5rem; height: 2.5rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316A2.192 2.192 0 0014.515 4H9.485a2.192 2.192 0 00-1.838 1.027l-.82 1.348zM12 11a3 3 0 110 6 3 3 0 010-6z"></path>
                </svg>
            </div>
            <h3 style="font-size: 1.4rem; color: var(--white);">Muka Kusut Scan</h3>
            <p style="font-size: 0.95rem; margin: 0; color: var(--white); font-weight: 500;">Deteksi raut wajah kurang tidur dan mata panda secara instan via Webcam Browser API secara interaktif.</p>
        </div>
    </div>
</div>
@endsection
