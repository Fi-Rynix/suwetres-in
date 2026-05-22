@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
@endsection

@section('content')
<div style="max-width: 650px; margin: 5rem auto; text-align: center;">
    
    <div class="neo-box" style="background-color: var(--white); padding: 3.5rem 2rem;">
        <div class="loader-element"></div>
        
        <h1 style="font-size: 2rem; margin-bottom: 1.5rem;" id="loading-title">
            PROSES FUZZY SUGENO
        </h1>

        <div style="background-color: var(--white); border: var(--border-width) solid var(--dark); box-shadow: 5px 5px 0 var(--dark); height: 35px; width: 100%; margin-bottom: 2rem; overflow: hidden; position: relative;">
            <div id="loading-progress" style="width: 0%; height: 100%; background-color: var(--secondary); transition: width 0.15s ease-out;"></div>
        </div>

        <div class="neo-badge" id="loading-status" style="background-color: var(--yellow); font-size: 1.05rem; padding: 0.5rem 1.5rem;">
            Menyiapkan data stress Anda...
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        window.loadingRedirectUrl = "{{ route('process') }}";
    </script>
    <script src="{{ asset('js/loading.js') }}"></script>
@endsection
