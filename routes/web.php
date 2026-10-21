<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\FacultyController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\LessonMaterialController;
use App\Http\Controllers\Admin\ConsultationController;

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

    Route::patch('lessons/{lesson}/toggle-published', [LessonController::class, 'togglePublished'])->name('lessons.toggle-published');
    Route::resource('lessons', LessonController::class)->except('show');

    Route::get('lesson-materials', [LessonMaterialController::class, 'index'])->name('lesson-materials.index');
    Route::post('lesson-materials', [LessonMaterialController::class, 'store'])->name('lesson-materials.store');
    Route::put('lesson-materials/{lessonMaterial}', [LessonMaterialController::class, 'update'])->name('lesson-materials.update');
    Route::delete('lesson-materials/{lessonMaterial}', [LessonMaterialController::class, 'destroy'])->name('lesson-materials.destroy');
    Route::get('lesson-materials/{lessonMaterial}/download', [LessonMaterialController::class, 'download'])->name('lesson-materials.download');

    Route::patch('consultations/{consultation}/link', [ConsultationController::class, 'updateLink'])->name('consultations.update-link');
    Route::patch('consultations/{consultation}/toggle-active', [ConsultationController::class, 'toggleActive'])->name('consultations.toggle-active');
    Route::resource('consultations', ConsultationController::class)->except('show');
});

require __DIR__.'/auth.php';
