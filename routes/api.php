<?php

use App\Http\Controllers\api\AuthController;
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


// auth
Route::prefix('auth')->middleware(['guest'])->controller(AuthController::class)->group(function () {
    Route::post('/login', 'login')->name('login')->name('login');
    Route::post('/register', 'register')->name('register')->name('register');
});
Route::middleware('auth:sanctum')->post('/auth/logout', [AuthController::class, 'logout'])->name('logout');
