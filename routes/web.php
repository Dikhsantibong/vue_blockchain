<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Dashboard Route with Role Check
Route::get('/dashboard', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect('/admin/dashboard');
    }
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard');
        }
        return Inertia::render('AdminDashboard');
    })->name('admin.dashboard');

    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users');
    Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.users.store');

    // Elections routes
    Route::get('/elections', [App\Http\Controllers\Admin\ElectionController::class, 'index'])->name('admin.elections');
    Route::post('/elections', [App\Http\Controllers\Admin\ElectionController::class, 'store'])->name('admin.elections.store');
    Route::put('/elections/{election}', [App\Http\Controllers\Admin\ElectionController::class, 'update'])->name('admin.elections.update');
    Route::delete('/elections/{election}', [App\Http\Controllers\Admin\ElectionController::class, 'destroy'])->name('admin.elections.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
