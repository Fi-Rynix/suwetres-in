<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\KuisionerController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\LoadingController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\FuzzyController;

Route::get('/', [LandingController::class, 'landing'])->name('landing');
Route::get('/kuisioner', [KuisionerController::class, 'kuisioner'])->name('kuisioner');
Route::post('/kuisioner', [KuisionerController::class, 'postKuisioner'])->name('post.kuisioner');
Route::get('/scan', [ScanController::class, 'scan'])->name('scan');

// Endpoint AJAX untuk menerima hasil FER dari client (Face-API.js)
Route::post('/scan/submit-fer', [ScanController::class, 'submitFER'])->name('scan.submit-fer');

Route::get('/loading', [LoadingController::class, 'loading'])->name('loading');
Route::get('/process', [FuzzyController::class, 'processFuzzy'])->name('process');
Route::get('/result', [ResultController::class, 'result'])->name('result');
Route::get('/recommendation', [RecommendationController::class, 'recommendation'])->name('recommendation');
