<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherAuthController;

Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');
Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');

// Teacher login (must be named "login" so the auth middleware can redirect here)
Route::get('/teacher/login', [TeacherAuthController::class, 'showLogin'])->name('login');
Route::post('/teacher/login', [TeacherAuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('teacher.login');

// Teacher-only pages
Route::middleware('auth')->prefix('teacher')->name('teacher.')->group(function () {
    Route::post('/logout', [TeacherAuthController::class, 'logout'])->name('logout');

    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
});