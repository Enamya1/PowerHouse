<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// test 
Route::get('/test', function () {
    return response()->json([
        'message' => 'API is working!'
    ]);
});


Route::get('/users',[UserController::class,'index']);
Route::POST('/user',[UserController::class,'store']);




Route::post('/sing_up',[AuthController::class,'sing_up']);
Route::post('/login',[AuthController::class,'login']);