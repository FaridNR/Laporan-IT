<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\WorkReportController;
use Illuminate\Support\Facades\Route;

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Programmer-only create & store (must be defined before /reports/{report})
    Route::middleware('role:programer')->group(function () {
        Route::get('/reports/create', [WorkReportController::class, 'create'])->name('reports.create');
        Route::post('/reports', [WorkReportController::class, 'store'])->name('reports.store');
        Route::get('/reports/{report}/edit', [WorkReportController::class, 'edit'])->name('reports.edit');
        Route::put('/reports/{report}', [WorkReportController::class, 'update'])->name('reports.update');
        Route::delete('/reports/{report}', [WorkReportController::class, 'destroy'])->name('reports.destroy');
    });

    // Reports Read-only & Search (All Roles: Programer, Staff IT, Kepala IT)
    Route::get('/reports', [WorkReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [WorkReportController::class, 'show'])->name('reports.show');

    // Rekapitulasi & Export (All Roles, primarily Staff IT & Kepala IT)
    Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');
    Route::get('/rekap/print', [RekapController::class, 'printPdf'])->name('rekap.print');
    Route::get('/rekap/csv', [RekapController::class, 'exportCsv'])->name('rekap.csv');
});
