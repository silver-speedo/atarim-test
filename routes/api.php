<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UrlShortenerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'createUser'])->name('register');
Route::post('/auth/login', [AuthController::class, 'loginUser'])->name('login');
Route::delete('/auth/revokeToken', [AuthController::class, 'revokeToken'])
    ->middleware('auth:sanctum')
    ->name('revoke');

Route::post('/encode', [UrlShortenerController::class, 'encode'])
    ->middleware('auth:sanctum')
    ->name('encode');
Route::post('/decode', [UrlShortenerController::class, 'decode'])
    ->middleware('auth:sanctum')
    ->name('decode');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum')->name('user');
