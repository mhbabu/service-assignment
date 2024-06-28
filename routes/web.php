<?php

use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});

Route::resource('categories', CategoryController::class);
Route::resource('profiles', ProfileController::class);

Route::middleware('auth')->group(function () {
    Route::post('/availability/weekly', [AvailabilityController::class, 'setWeeklyAvailability']);
    Route::post('/availability/override', [AvailabilityController::class, 'setOverrideAvailability']);
    Route::get('/availability/{profile}/buyer', [AvailabilityController::class, 'getAvailabilityForBuyer']);
});