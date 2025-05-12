<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ServiceController; 
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect('/admin/dashboard');
    }else{
        return redirect('/reservations');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User routes
    Route::resource('services', ServiceController::class);

    Route::prefix('reservations')->controller(ReservationController::class)->name('reservations.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{service}', 'store')->name('store');
        Route::delete('/{reservation}', 'cancel')->name('cancel');
    });


    // Admin routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::controller(AdminReservationController::class)->name('reservations.')->group(function () {
            Route::get('reservations', 'index')->name('index');
            Route::patch('reservations/{reservation}/status', 'updateStatus')->name('updateStatus');
        });
        
        Route::resource('services', AdminServiceController::class);

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });
});

require __DIR__.'/auth.php';
