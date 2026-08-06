<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\AdminController;

Route::get('/', [VisitorController::class, 'index'])->name('visitor.login');
Route::get('/daftar', [VisitorController::class, 'showRegister'])->name('visitor.register');
Route::post('/daftar', [VisitorController::class, 'register'])->name('visitor.register.post');
Route::post('/masuk', [VisitorController::class, 'login'])->name('visitor.login.post');
Route::get('/aktivitas', [VisitorController::class, 'aktivitas'])->name('visitor.aktivitas');
Route::post('/aktivitas', [VisitorController::class, 'simpanAktivitas'])->name('visitor.aktivitas.post');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.post');
    Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/visitor/{id}', [AdminController::class, 'detailVisitor'])->name('visitor.detail');
    Route::get('/export-data', [AdminController::class, 'exportData'])->name('export-data');
});
