<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\HomeController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
// Route::get('/', fn () => response()->json(['status' => 'OK']));

Route::get('/elections', [ElectionController::class, 'index'])->name('elections.index');
Route::get('/elections/{election}', [ElectionController::class, 'show'])->name('elections.show');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// User Routes (Authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Voting Routes
    Route::prefix('vote')->name('vote.')->group(function () {
        Route::get('/', [VotingController::class, 'index'])->name('index');
        Route::get('/{election}', [VotingController::class, 'show'])->name('show');
        Route::post('/{election}', [VotingController::class, 'submit'])->name('submit');
        Route::get('/{election}/success', [VotingController::class, 'success'])->name('success');
    });
    
    Route::get('/my-votes', [VotingController::class, 'history'])->name('vote.history');
});


Route::get('/ping', function() {
    return response()->json(['status' => 'ok', 'node' => 'node_b']);
});

Route::get('/health', function() {
    return response()->json(['status' => 'healthy', 'timestamp' => now()]);
});