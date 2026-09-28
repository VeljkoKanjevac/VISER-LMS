<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\FacultyController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('student.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('faculties', FacultyController::class)->except('show');
    Route::patch('faculties/{faculty}/toggle-active', [FacultyController::class, 'toggleActive'])->name('faculties.toggle-active');

    Route::patch('courses/{course}/toggle-active', [CourseController::class, 'toggleActive'])->name('courses.toggle-active');
    Route::resource('courses', CourseController::class)->except('show');

    Route::patch('sections/{section}/toggle-published', [SectionController::class, 'togglePublished'])->name('sections.toggle-published');
    Route::resource('sections', SectionController::class)->except('show');
});

require __DIR__.'/auth.php';
