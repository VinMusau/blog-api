<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('categories', [CategoryController::class, 'index']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::get('/', [PostController::class, 'index']);

// Route::get('/posts', function() { return response()->json(Post::all());}); 

Route::apiResource('posts', PostController::class); 

// route to get posts by a specific user
Route::get('users/{user}/posts', [PostController::class, 'userPosts']);

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->post('user/avatar', [UserController::class, 'updateAvatar']);

Route::middleware('auth:sanctum')->delete('/user/avatar', [UserController::class, 'deleteAvatar']);

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect(env('APP_URL'). '/login?verified=1');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return response()->json(['message' => 'Verification link sent!']);
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');