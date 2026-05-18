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
