<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/info', function () {
    return view('project.showcase');
})->name('info');
Route::get('/dashboard', [DashboardController::class, 'show'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profiles/{profile}/edit', [ProfileController::class, 'edit'])->name('profiles.edit');
    Route::patch('/profiles/{profile}', [ProfileController::class, 'update'])->name('profiles.update');
    Route::get('profiles/{profile}', [ProfileController::class, 'show'])->name('profiles.show');
    Route::get('profiles/{profile}/pdf', [ProfileController::class, 'generatePdf'])->name('profiles.pdf');
    Route::put('/profiles/{id}/assignLeader', [ProfileController::class, 'assignLeader'])->name('profiles.assign_leader');



    Route::resource('tasks', TaskController::class);
    Route::put('/tasks/{id}/update-status', [TaskController::class, 'update_status'])->name('tasks.update_status');
    Route::resource('feedbacks', FeedbackController::class);


});

require __DIR__.'/auth.php';
