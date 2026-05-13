<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WasteRequestController;
use App\Http\Controllers\AdminRequestController;
use App\Http\Controllers\WorkerTaskController;
use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin Routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin/api/stats', [AnalyticsController::class, 'getStats'])->name('admin.api.stats');
        Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
        Route::get('/admin/requests', [AdminRequestController::class, 'index'])->name('admin.requests.index');
        Route::post('/admin/requests/{wasteRequest}/assign', [AdminRequestController::class, 'assign'])->name('admin.requests.assign');
    });

    // Worker Routes
    Route::middleware('worker')->group(function () {
        Route::get('/worker/dashboard', [DashboardController::class, 'worker'])->name('worker.dashboard');
        Route::get('/worker/tasks', [WorkerTaskController::class, 'index'])->name('worker.tasks.index');
        Route::post('/worker/tasks/{wasteRequest}/complete', [WorkerTaskController::class, 'complete'])->name('worker.tasks.complete');
    });

    // User Routes
    Route::get('/user/dashboard', [DashboardController::class, 'user'])->name('user.dashboard');
    Route::resource('/user/requests', WasteRequestController::class)->names([
        'index' => 'user.requests.index',
        'create' => 'user.requests.create',
        'store' => 'user.requests.store',
        'destroy' => 'user.requests.destroy',
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
