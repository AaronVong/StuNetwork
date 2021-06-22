<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\ToastController;
use App\Http\Controllers\Client\UserController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



/*
|--------------------------------------------------------------------------
| Auth Route
|--------------------------------------------------------------------------
*/

Route::get("/login",[LoginController::class, "index"])->name("login");
Route::post("/login",[LoginController::class, "store"]);
Route::get("/logout", [LoginController::class, function(){return back();}])->name("logout");
Route::post("/logout", [LoginController::class, "destroy"]);
Route::get("/register",[RegisterController::class, "index"])->name("register");
Route::post("/register", [RegisterController::class, "store"]);
Route::get("/email/verify", [VerificationController::class, "index"])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}',[VerificationController::class, "emailVerification"] )->name('verification.verify');
Route::post('/email/verification-notification', [VerificationController::class, "sendEmailVerification"])->name("verification.send");

/*
|--------------------------------------------------------------------------
| Client Page Route
|--------------------------------------------------------------------------
*/
Route::get("/forgot-password", [UserController::class, "forgotPasswordForm"])->name("password.request");
Route::post("/forgot-password", [UserController::class, "forgotPassword"])->name("password.email");
Route::get('/reset-password/{token}',[UserController::class, "resetPasswordForm"] )->name('password.reset');
Route::post("/reset-password",[UserController::class, "resetPassword"])->name("password.update");

Route::middleware(["verified", "auth"])->group(function(){
    Route::get("/", [HomeController::class, "index"])->name("home");
    Route::get("/profile/{user:username}", [ProfileController::class, "index"])->name("profile");
    Route::get("/profile/get/{user:username}", [ProfileController::class, "getProfile"])->name("profile.get");
    Route::put("/profile/{username}", [ProfileController::class, "updateProfile"])->name("profile.update");
    Route::post("/profile/follow", [ProfileController::class, "follow"])->name("profile.follow");
    Route::post("/profile/followed", [ProfileController::class, "followed"]);
    Route::post("/proifle/followings", [UserController::class, "profilesFollowedById"]);

    Route::get("/toast", [ToastController::class, "paginate"])->name("toast.paginate");
    Route::get("/toast/{id}", [ToastController::class, "index"])->name("toast");
    Route::get("/toast/get/{id}", [ToastController::class, "getToast"])->name("toast.get");
    Route::post("/toast", [ToastController::class, "store"])->name("toast.store");
    Route::delete("/toast/{id}",[ToastController::class, "destroy"])->name("toast.destroy");
    Route::put("/toast/{id}", [ToastController::class, "update"])->name("toast.update");
    Route::post("/toast/like", [ToastController::class, "like"])->name("toast.like");
    Route::post("/toast/uploaded", [ToastController::class, "toastsUploadedById"]);
    Route::post("/toast/liked", [ToastController::class, "toastsLikedById"]);
    Route::post("/toast/followed", [UserController::class, "profilesFollowedById"]);
});


