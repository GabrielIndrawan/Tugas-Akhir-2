<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoragePageController;
use App\Http\Controllers\AdminHomeController;
use App\Http\Middleware\CheckUserRole;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
})->name("/");

Route::get('/test/auth', function () {
    return [
        'check' => Auth::check(),
        'user' => Auth::user(),
        'id' => Auth::id(),
    ];
})->middleware('auth');

Route::middleware(['auth'])->group(function (){
    Route::resource("/users",UsersController::class);
    Route::get("/dashboard", [HomeController::class, "index"])->name("dashboard");
    Route::get("/storage", [StoragePageController::class, "index"])->name("storage");
    Route::get("/logout",[AuthenticatedSessionController::class,"destroy"])->name("logout");
    Route::middleware(CheckUserRole::class)->group(function (){
        Route::get("/admin/dashboard", [AdminHomeController::class, "index"])->name("dashboard-admin");
        Route::get("/admin/profile", [ProfileController::class, "edit"])->name("profile-admin");
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
