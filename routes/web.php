<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PrediksiController;
use App\Http\Controllers\ProfilController;

Route::get('/', [DashboardController::class, 'index']);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');


Route::get('/prediksi', [PrediksiController::class, 'index'])->name('prediksi');

Route::prefix('predictions')->group(function () {
    Route::get('/', [PrediksiController::class, 'index'])->name('predictions.index');
    Route::get('/create', [PrediksiController::class, 'create'])->name('predictions.create');
    Route::post('/store', [PrediksiController::class, 'store'])->name('predictions.store');
});

Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
Route::post('/profil/update', [ProfilController::class, 'update'])->name('profil.update');
Route::get('/profil/delete', [ProfilController::class, 'delete'])->name('profil.delete');