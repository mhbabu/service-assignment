<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ServiceProfileController;
use App\Http\Controllers\Admin\AvailabilityController;
use App\Http\Controllers\Admin\OverrideAvailabilityController;
use Illuminate\Support\Facades\Route;


Route::get('/', function(){
    return auth()->id() ? redirect(url('home')) : redirect(route('login'));
});

Auth::routes();
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('categories/delete/{category}', [CategoryController::class, 'delete'])->name('categories.delete');
    Route::resource('categories', CategoryController::class)->except(['destroy']);

    Route::get('service/profiles/{service_profile}/delete', [ServiceProfileController::class, 'delete'])->name('service-profiles.delete');
    Route::resource('service-profiles', ServiceProfileController::class)->except(['destroy']);

    Route::get('availabilites/{availability}/delete', [AvailabilityController::class, 'delete'])->name('availabilites.delete');
    Route::resource('availabilites', AvailabilityController::class)->except(['destroy']); 

    Route::get('override-availabilites/{availability}/delete', [OverrideAvailabilityController::class, 'delete'])->name('availabilites.delete');
    Route::resource('override-availabilites', OverrideAvailabilityController::class)->except(['destroy']);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
