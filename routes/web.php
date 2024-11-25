<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\BookLibaryController;
use App\Http\Controllers\NearestLibrariesController;
use App\Http\Controllers\ReservationController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Get long,lat by address
Route::post('/get-address', [AddressController::class, 'getAddress'])->name('get-address');

Route::resource('libraries', LibraryController::class);
Route::resource('books', BookController::class);
Route::resource('books-libray', BookLibaryController::class);
Route::get('/nearby-libraries',[NearestLibrariesController::class,'index']);
Route::get('show-books-user', [BookController::class, 'showBooks']);
Route::resource('reservation',ReservationController::class);
// Route::get('/libraries', [LibraryController::class, 'getLibraries']);
Route::post('/nearby-libraries/show', [NearestLibrariesController::class, 'showNearbyLibraries'])->name('nearbylibraries.show');

