<?php
// routes/web.php - COMPLETE WITH DOCUMENT SERVE ROUTES

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\DocumentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes (User Interface)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/gallery', [HomeController::class, 'gallery'])->name('gallery');
Route::get('/maps', [HomeController::class, 'maps'])->name('maps');
Route::get('/halte/{id}', [HomeController::class, 'showHalte'])->name('halte.show');
Route::get('/halte/{id}/detail', [HomeController::class, 'detail'])->name('halte.detail');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Optional Registration Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// User Routes (Protected by auth and user middleware)
Route::prefix('user')->name('user.')->middleware(['auth', 'user'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'dashboard'])->name('dashboard');

    Route::prefix('haltes')->name('haltes.')->group(function () {
        Route::get('/', [UserDashboardController::class, 'haltesList'])->name('index');
        Route::get('/{id}', [UserDashboardController::class, 'halteDetail'])->name('detail');
    });

    Route::get('/map', [UserDashboardController::class, 'mapView'])->name('map');
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserDashboardController::class, 'updateProfile'])->name('profile.update');
});

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

        // Halte Document Management - SIMPLIFIED (Direct View)
        Route::prefix('documents')->name('documents.')->group(function () {
            Route::get('/{id}/view', [DocumentController::class, 'viewHalteDocument'])->name('view');
            Route::get('/{id}/download', [DocumentController::class, 'downloadHalteDocument'])->name('download');
            Route::delete('/{id}', [DocumentController::class, 'deleteHalteDocument'])->name('delete');
            Route::post('/{halteId}/upload', [DocumentController::class, 'uploadHalteDocuments'])->name('upload');
        });
    });

    // Rental History Management
    Route::prefix('rentals')->name('rentals.')->group(function () {
        Route::get('/', [AdminController::class, 'rentalHistory'])->name('index');

        // Rental Document Management - SIMPLIFIED (Direct View)
        Route::prefix('documents')->name('documents.')->group(function () {
            Route::get('/{id}/view', [DocumentController::class, 'viewRentalDocument'])->name('view');
            Route::get('/{id}/download', [DocumentController::class, 'downloadRentalDocument'])->name('download');
            Route::delete('/{id}', [DocumentController::class, 'deleteRentalDocument'])->name('delete');
            Route::post('/{rentalHistoryId}/upload', [DocumentController::class, 'uploadRentalDocuments'])->name('upload');
        });
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

    // Admin Profile Management
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Reports Management
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminController::class, 'reports'])->name('index');
        Route::post('/generate', [AdminController::class, 'generateReport'])->name('generate');
    });
});

// Redirect shortcuts
Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'admin']);

Route::get('/user', function () {
    return redirect()->route('user.dashboard');
})->middleware(['auth', 'user']);
