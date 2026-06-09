<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;



Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/password/reset', [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

    Route::middleware('role:user')->prefix('user')->name('user.')->group(function () {
        Route::get('/profile', [UserProfileController::class, 'index'])->name('profile');
        Route::patch('/profile', [UserProfileController::class, 'update'])->name('profile.update');
        Route::patch('/avatar', [UserProfileController::class, 'updateAvatar'])->name('avatar.update');
        Route::patch('/preferences', [UserProfileController::class, 'updatePrefs'])->name('preferences.update');
        Route::patch('/password', [UserProfileController::class, 'updatePassword'])->name('password.update');
        Route::get('/cv', [UserProfileController::class, 'cvPage'])->name('cv');
        Route::post('/cv/upload', [UserProfileController::class, 'uploadCv'])->name('cv.upload');
        Route::get('/cv/download', [UserProfileController::class, 'downloadCv'])->name('cv.download');
        Route::delete('/cv', [UserProfileController::class, 'deleteCv'])->name('cv.delete');
        Route::get('/applications', [UserApplicationController::class, 'index'])->name('applications');
        Route::get('/applications/{id}/cv', [UserApplicationController::class, 'downloadCv'])->name('application.cv');
        Route::get('/saved-jobs', [UserProfileController::class, 'savedJobs'])->name('saved-jobs');
    });



// Language switcher (أضف هذا السطر في قسم الروتس المشتركة)
Route::get('/language/{locale}', [\App\Http\Controllers\Shared\LanguageController::class, 'switch'])
    ->name('language.switch')
    ->where('locale', 'ar|en');