<?php
// routes/web.php - UPDATED WITH USER MANAGEMENT ROUTES

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes (User Interface)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/halte/{id}', [HomeController::class, 'showHalte'])->name('halte.show');
Route::get('/halte/{id}/detail', [HomeController::class, 'detail'])->name('halte.detail');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Optional Registration Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Admin Routes (Protected by auth and admin middleware)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Halte Management
    Route::prefix('haltes')->name('haltes.')->group(function () {
        Route::get('/', [AdminController::class, 'halteList'])->name('index');
        Route::get('/create', [AdminController::class, 'halteCreate'])->name('create');
        Route::post('/', [AdminController::class, 'halteStore'])->name('store');
        Route::get('/{id}', [AdminController::class, 'halteShow'])->name('show');
        Route::get('/{id}/edit', [AdminController::class, 'halteEdit'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'halteUpdate'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'halteDestroy'])->name('destroy');

        // Photo Management
        Route::delete('/photos/{id}', [AdminController::class, 'deletePhoto'])->name('photos.delete');
        Route::patch('/photos/{id}/primary', [AdminController::class, 'setPrimaryPhoto'])->name('photos.primary');
    });

    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Profile Management
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Rental History
    Route::get('/rentals', [AdminController::class, 'rentalHistory'])->name('rentals.index');
});

// Redirect admin to dashboard after login
Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'admin']);
