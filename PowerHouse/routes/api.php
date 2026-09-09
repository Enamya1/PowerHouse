<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AdminController;

// test 
Route::get('/test', function () {
    return response()->json([
        'message' => 'API is working!'
    ]);
});




Route::get('/test_v2', function (Request $request) {
    dd($request->user());
});


Route::get('/users',[UserController::class,'index']);
Route::POST('/user',[UserController::class,'store']);




Route::post('/sing_up',[AuthController::class,'sing_up']);
Route::post('/login',[AuthController::class,'login']);




Route::middleware(['auth:sanctum', 'admin'])
    ->prefix('admin')
    ->group(function () {

        // Get all users
        Route::get('/users', [AdminController::class, 'get_all_user']);

        // Get one user by ID
        Route::get('/users/{id}', [AdminController::class, 'get_user_by_id']);
        // delet one user by id 
        Route::delete('/delete/{id}', [AdminController::class, 'delete_user_by_id']);

    });