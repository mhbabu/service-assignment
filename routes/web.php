<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ServiceProfileController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', function(){
    return auth()->id() ? redirect(url('home')) : redirect(route('login'));
});

Auth::routes();
// Route::resource('categories', CategoryController::class);
Route::resource('profiles', ProfileController::class);

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('categories/delete/{category}', [CategoryController::class, 'delete'])->name('categories.delete');
    Route::resource('categories', CategoryController::class)->except(['destroy']);

    Route::get('service/profiles/{service_profile}/delete', [ServiceProfileController::class, 'delete'])->name('service-profiles.delete');
    Route::resource('service-profiles', ServiceProfileController::class)->except(['destroy']); // various service profiles
    
    Route::post('/availability/weekly', [AvailabilityController::class, 'setWeeklyAvailability']);
    Route::post('/availability/override', [AvailabilityController::class, 'setOverrideAvailability']);
    Route::get('/availability/{profile}/buyer', [AvailabilityController::class, 'getAvailabilityForBuyer']);
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
