<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::get('/me', [AuthController::class, 'me']);
// });

Route::get('/', function () {
    return view('welcome');
});

// Sanctum CSRF cookie

Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
})->middleware('web');

Route::prefix('api')->withoutMiddleware([VerifyCsrfToken::class])->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register']);
});
