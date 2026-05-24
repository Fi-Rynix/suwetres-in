@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
@endsection

@section('content')
<div class="loading-container">
    
    <div class="neo-box loading-box">
        <div class="loader-element"></div>
        
        <h1 class="loading-title-text" id="loading-title">
            PROSES FUZZY SUGENO
        </h1>

        <div class="loading-progress-bar-container">
            <div id="loading-progress" class="loading-progress-bar"></div>
        </div>

        <div class="neo-badge loading-status-badge" id="loading-status">
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
