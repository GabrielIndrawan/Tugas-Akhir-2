<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\SubscribtionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoragePageController;
use App\Http\Controllers\AdminHomeController;
use App\Http\Middleware\CheckUserRole;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\FilesController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
})->name("/");

Route::get('/register', function () {
    return view('auth.register');
})->name("register");

Route::get('/test/auth', function () {
    return [
        'check' => Auth::check(),
        'user' => Auth::user(),
        'id' => Auth::id(),
    ];
})->middleware('auth');

Route::post('/stripe/webhook', [StripeWebhookController::class,'handle'])->name('stripe.webhook');

Route::post('/users', [UsersController::class, 'store'])
    ->name('users.store');

Route::middleware(['auth'])->group(function (){
    Route::resource("/users",UsersController::class)->except(["store"]);
    Route::resource("/files", FilesController::class);
    Route::get("/dashboard", [HomeController::class, "index"])->name("dashboard");
    Route::get("/storage", [StoragePageController::class, "index"])->name("storage");
    Route::get("/logout",[AuthenticatedSessionController::class,"destroy"])->name("logout");
    Route::get("/files/download/{filePath}", [FilesController::class, "download"])->name("files.download");
    Route::post("/subscribtion/checkout", [SubscribtionController::class, "checkout"])->name("subscription.checkout");
    Route::get('/payment/success', [SubscribtionController::class, 'success'])->name('subscription.success');
    Route::get('/payment/cancel', [SubscribtionController::class, 'cancel'])->name('subscription.cancel');
    Route::get('/history/',[HistoryController::class,'index'])->name("history");
    Route::middleware(CheckUserRole::class)->group(function (){
        Route::get("/admin/dashboard", [AdminHomeController::class, "index"])->name("dashboard-admin");
        Route::get("/admin/profile", [ProfileController::class, "edit"])->name("profile-admin");
        Route::get("/admin/subscription", [SubscribtionController::class, "index"])->name("subscription");
        Route::post("/admin/subscription/add", [SubscribtionController::class, "addSubscribtion"])->name("subscription.add");
        Route::get("/admin/subscribtion/edit",[SubscribtionController::class, "edit"])->name('subscription.edit');
        Route::get("/admin/history/",[SubscribtionController::class, "edit"])->name('admin.history');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
