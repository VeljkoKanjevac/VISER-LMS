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
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReferralCodeController;
use App\Http\Controllers\Student\EnrollmentRequestController;
use App\Http\Controllers\Student\OfferController as StudentOfferController;


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


Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    Route::post('/offers/{offer}/enrollment-request', [EnrollmentRequestController::class, 'store'])->name('enrollment-requests.store');
    Route::get('/offers', [StudentOfferController::class, 'index'])->name('offers.index');

    Route::get('/enrollment-requests', [EnrollmentRequestController::class, 'index'])->name('enrollment-requests.index');
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

    Route::patch('offers/{offer}/toggle-active', [OfferController::class, 'toggleActive'])->name('offers.toggle-active');
    Route::resource('offers', OfferController::class)->except(['show']);

    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');

    Route::get('/referral-codes', [ReferralCodeController::class, 'index'])->name('referral-codes.index');
    Route::get('/referral-codes/create', [ReferralCodeController::class, 'create'])->name('referral-codes.create');
    Route::post('/referral-codes', [ReferralCodeController::class, 'store'])->name('referral-codes.store');
    Route::patch('/referral-codes/{referralCode}/toggle-active', [ReferralCodeController::class, 'toggleActive',])->name('referral-codes.toggle-active');
    Route::delete('/referral-codes/{referralCode}', [ReferralCodeController::class, 'destroy',])->name('referral-codes.destroy');
});

require __DIR__.'/auth.php';
