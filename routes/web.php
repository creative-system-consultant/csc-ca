<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Livewire\Auth\Login;
use App\Livewire\Profile\Index as Profile;
use App\Livewire\Auth\Passwords\Confirm;
use App\Livewire\Auth\Passwords\Email;
use App\Livewire\Auth\Passwords\Reset;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\Verify;
use Illuminate\Support\Facades\Route;

use App\Livewire\Home\Home;
use App\Livewire\Doc\Doc;
use App\Livewire\Report\ReportList;
use App\Livewire\SysAdmin\UserManagement;

Route::middleware('guest')->group(function () {
    Route::get('/', Login::class);
    Route::get('login', Login::class)
        ->name('login');

    Route::get('register', Register::class)
        ->name('register');
});

Route::get('password/reset', Email::class)
    ->name('password.request');

Route::get('password/reset/{token}', Reset::class)
    ->name('password.reset');

Route::middleware('auth')->group(function () {

        Route::get('home', Home::class)->name('home');
        Route::get('profile', Profile::class)->name('profile');

        Route::get('email/verify/{id}/{hash}', EmailVerificationController::class)
            ->middleware('signed')
            ->name('verification.verify');

        Route::post('logout', LogoutController::class)
            ->name('logout');

        Route::get('email/verify', Verify::class)
            ->middleware('throttle:6,1')
            ->name('verification.notice');

        Route::get('password/confirm', Confirm::class)
            ->name('password.confirm');

        //user management
        Route::get('user-management', UserManagement::class)->name('userManagement');

        // roles
        Route::prefix('roles')->as('roles.')->group(
            base_path('routes/web/role.php'),
        );

        // permissions
        Route::prefix('permissions')->as('permissions.')->group(
            base_path('routes/web/permission.php'),
        );
    });
