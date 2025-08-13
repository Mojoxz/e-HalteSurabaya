<?php
// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('api')->group(function () {
    // Public API Routes (tidak perlu authentication)
    Route::prefix('v1')->group(function () {
        // Halte API
        Route::get('/haltes', [ApiController::class, 'getHaltes']);
        Route::get('/haltes/{id}', [ApiController::class, 'getHalte']);
        Route::get('/haltes/search', [ApiController::class, 'searchHaltes']);
        Route::get('/statistics', [ApiController::class, 'getStatistics']);
    });
});

// Contoh untuk authenticated API routes jika diperlukan di masa depan
/*
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('v1')->group(function () {
        // Protected API routes here
    });
});
*/
