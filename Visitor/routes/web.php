<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmergencyController;
use App\Http\Controllers\Admin\QrCodeController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\VisitorFormFieldController;
use App\Http\Controllers\Admin\VisitorController;
use App\Http\Controllers\Admin\WatchlistController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PublicVisitorRegistrationController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/visitor-register');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:5,1');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/visitor-registration', [PublicVisitorRegistrationController::class, 'create'])->name('visitor-registration.create');
Route::post('/visitor-registration', [PublicVisitorRegistrationController::class, 'store'])->name('visitor-registration.store')->middleware('throttle:10,1');
Route::get('/visitor-registration/thank-you', [PublicVisitorRegistrationController::class, 'thankYou'])->name('visitor-registration.thank-you');

// New aliases requested by spec
Route::get('/visitor-register', [PublicVisitorRegistrationController::class, 'create'])->name('visitor-register');
Route::post('/visitor-register', [PublicVisitorRegistrationController::class, 'store'])->name('visitor-register.store')->middleware('throttle:10,1');
Route::get('/visitor-success', [PublicVisitorRegistrationController::class, 'thankYou'])->name('visitor-success');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('visitors', VisitorController::class);
    Route::patch('visitors/{visitor}/status', [VisitorController::class, 'updateStatus'])->name('visitors.status');
    Route::get('qr-code', QrCodeController::class)->name('qr.index');
    Route::get('emergency', [EmergencyController::class, 'index'])->name('emergency.index');
    
    Route::get('watchlists', [WatchlistController::class, 'index'])->name('watchlists.index');
    Route::post('watchlists', [WatchlistController::class, 'store'])->name('watchlists.store');
    Route::delete('watchlists/{watchlist}', [WatchlistController::class, 'destroy'])->name('watchlists.destroy');
    
    // Admin Only Actions
    Route::middleware('admin_only')->group(function () {
        Route::resource('form-fields', VisitorFormFieldController::class)->except('show');
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
    });
});
