<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'signUp']);
Route::post('/signIn', [AuthController::class, 'signIn']);
Route::post('/logOut', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::apiResource('/booksApi', BookController::class)->middleware('auth:sanctum');

