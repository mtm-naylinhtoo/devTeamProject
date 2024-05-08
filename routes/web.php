<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profiles/{profile}/edit', [ProfileController::class, 'edit'])->name('profiles.edit');
    Route::patch('/profiles/{profile}', [ProfileController::class, 'update'])->name('profiles.update');
    Route::get('profiles/{profile}', [ProfileController::class, 'show'])->name('profiles.show');


    Route::resource('tasks', TaskController::class);
    Route::put('/tasks/{id}/update-status', [TaskController::class, 'update_status'])->name('tasks.update_status');


});

require __DIR__.'/auth.php';
