<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::get('/', [PostController::class, 'index']);

// Route::get('/posts', function() { return response()->json(Post::all());}); 


Route::apiResource('posts', PostController::class); 

//edit posts
// Route::put('/posts/{post}', [PostController::class, 'update'])->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');