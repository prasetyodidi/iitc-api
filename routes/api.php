<?php

use App\Http\Controllers\CompetitionCategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\NewPasswordController;
use App\Http\Controllers\PasswordResetLinkController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [LogoutController::class, 'store']);

    Route::prefix('competitions/categories')->group(function () {
        Route::post('', [CompetitionCategoryController::class, 'store']);
    });

    Route::prefix('competitions/categories/{categoryId}')->group(function () {
        Route::put('', [CompetitionCategoryController::class, 'update']);
    });
});

Route::get('competitions/categories', [CompetitionCategoryController::class, 'index']);

Route::post('login', [LoginController::class, 'store'])->name('login');
Route::post('register', [RegisterController::class, 'store'])->name('register');
Route::post('forgot-password', [PasswordResetLinkController::class, 'store']);
Route::post('reset-password', [NewPasswordController::class, 'store']);
