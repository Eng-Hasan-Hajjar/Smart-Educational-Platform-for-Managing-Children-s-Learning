<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $role = Auth::user()?->role;

        return match ($role) {
            'super_admin' => redirect()->route('admin.dashboard'),
            'school_admin' => redirect()->route('school.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            'counselor' => redirect()->route('counselor.dashboard'),
            'parent' => redirect()->route('parent.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default => redirect()->route('home'),
        };
    })->name('dashboard.redirect');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
    });

    Route::prefix('school')->name('school.')->group(function () {
        Route::view('/dashboard', 'school-admin.dashboard')->name('dashboard');
    });

    Route::prefix('teacher')->name('teacher.')->group(function () {
        Route::view('/dashboard', 'teacher.dashboard')->name('dashboard');
    });

    Route::prefix('counselor')->name('counselor.')->group(function () {
        Route::view('/dashboard', 'counselor.dashboard')->name('dashboard');
    });

    Route::prefix('parent')->name('parent.')->group(function () {
        Route::view('/dashboard', 'parent.dashboard')->name('dashboard');
    });

    Route::prefix('student')->name('student.')->group(function () {
        Route::view('/dashboard', 'student.dashboard')->name('dashboard');
    });
});