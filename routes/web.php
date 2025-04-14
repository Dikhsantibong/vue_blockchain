<?php

use App\Http\Controllers\ProfileController;
use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\VoteController;
use Illuminate\Http\Request;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ActiveElectionController;

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
Route::get('/dashboard', function (Request $request) {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect('/admin/dashboard');
    }

    $user = Auth::user();
    $elections = Election::with('candidates')->get();
    $candidates = Candidate::all();
    $userVotes = Vote::with(['election', 'candidate'])
        ->where('voter_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();

    return Inertia::render('Dashboard', [
        'elections' => $elections,
        'candidates' => $candidates,
        'userVotes' => $userVotes,
        'tab' => $request->get('tab', 'active-elections')
    ]);
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

    // Candidates routes
    Route::get('/elections/{election}/candidates', [App\Http\Controllers\Admin\CandidateController::class, 'index'])->name('admin.elections.candidates.index');
    Route::post('/elections/{election}/candidates', [App\Http\Controllers\Admin\CandidateController::class, 'store'])->name('admin.elections.candidates.store');
    Route::delete('/elections/{election}/candidates/{candidate}', [App\Http\Controllers\Admin\CandidateController::class, 'destroy'])->name('admin.elections.candidates.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Voting routes
    Route::post('/votes', [VoteController::class, 'store'])->name('votes.store');
    Route::get('/elections/{election}/verify', [VoteController::class, 'verify'])->name('votes.verify');
});

// User Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Active Elections
    Route::get('/active-elections', [ActiveElectionController::class, 'index'])->name('user.active-elections.index');
    Route::post('/active-elections/vote', [ActiveElectionController::class, 'vote'])->name('user.active-elections.vote');
});

require __DIR__.'/auth.php';
